import AreaDataset from'./area';import radarAnimation from'../animation-rules/radar-animation';import{addDep}from'../dependency-manager';import{getCoordinates,getPlotFromPixel}from'../axis/utils/polar-util';import{BLANKSTRING,pluck,toRaphaelColor,getFirstValue,regex,HASHSTRING,extend2,pluckNumber}from'../lib/lib';import{getLightColor}from'../lib/lib-graphics';var UNDEF,COMMASTRING=',',M='M',L='L',Z='Z',dropHash=regex.dropHash;addDep({name:'radarAnimation',type:'animationRule',extension:radarAnimation});class RadarDataset extends AreaDataset{getName(){return'radar'}createCoordinates(){var a,b,c,d,e,f,g,h,j,k=this,l=k.components,m=l.data,n=k.getFromEnv('chart'),o=n.config,p=k.getFromEnv('yAxis'),q=k.getFromEnv('xAxis'),r=m.length,s=k.components,t=s.data;for(d=0;d<r;d++)(a=t[d],b=a&&a.config,j=b.setValue,a!==UNDEF)&&(h=p.getPixel(j+(c||0)),g=getCoordinates({radius:o.canvasTop+o.canvasHeight/2-h,theta:d},q),f=g.y,e=g.x,b._Px=e,b._Py=f,b._Pbx=e,b._Pby=f)}getLinePath(a,b,c){var d,e,f,g,h,j,k=this,l=k.getFromEnv('chart'),m=l.config,n={},o=n.lastValidValue||!1,p=n.temp||[],q=n.temp2||[],r=n.pathArr||[],s=a.length,t=n.pointsJoined||0,u=b&&b.begin||0,v=b&&b.end||s,w=m.viewPortConfig.step||1,x=k.getFromEnv('yAxis'),y=k.getFromEnv('xAxis'),z=x.getPixel(0),A=k.removeDataLen||0,B=[];for(A=0,B=B.concat(a),d=u;d<v+A;d+=w)(h=B[d],!!h)&&(e=h.config,j=e.setValue,null===j||e&&!0===e.isSkipped?(f=getCoordinates({theta:y.getLimit().min},y).x,g=x.getPixel(x.config.axisRange.min)):(f=e._Px,g=e._Py),'zero'===c?g=z:'base'==c&&(g=e._Pby),o?(p.length&&(r=r.concat(p),p=[],t++),r.push([L,f,g])):(p.push([M,f,g]),t=0,o=!0));return r[r.length-1]!==Z&&0<t&&r.push(Z),{pathArr:r,path2Arr:[],lastValidValue:o,pointsJoined:t,temp:p,temp2:q,getPathArr:function(){var a=this,b=a.pathArr,c=a.path2Arr;return b.length||c.length?b.concat(c):[]}}}configureAttributes(a){if(!a)return!1;this.trimData(a),this.JSONData=a;var b=this,c=b.config,d=b.getFromEnv('chart'),e=b.index,f=d.getFromEnv('dataSource').chart,g=b.getFromEnv('color-manager'),h=b.JSONData,i=g.getPlotColor(e);super.configureAttributes(a),c.defaultPadding={left:0,right:0},c.plotfillcolor=pluck(h.color,f.plotfillcolor,i),c.plotbordercolor=pluck(h.plotbordercolor,f.plotbordercolor,f.areabordercolor,i).split(COMMASTRING)[0],c.fillColor={color:c.plotfillcolor+(c.plotgradientcolor?COMMASTRING+c.plotgradientcolor:BLANKSTRING),alpha:c.plotfillalpha,angle:c.plotfillangle},c.legendSymbolColor=c.plotfillcolor}_getHoveredPlot(a,b){var c,d,e,f,g,h=Math.floor,j=Math.max,k=this,l=k.getFromEnv('xAxis'),m=k.components.data,n=m.length,o=k.config,p=360/n;for(d=h(j(getPlotFromPixel({x:a-o.maxRadius,y:b},l)/p-1,0)),e=h(j(getPlotFromPixel({x:a+o.maxRadius,y:b},l)/p,n-1)),g=e;g>=d&&(c=m[g],!(c&&(f=k.isWithinShape(c,g,a,b),f)));g--);return f}_contextChanged(){this.config.context||(this.config.context={});var a,b,c=this,d=c.config.context,e=c.getFromEnv('xAxis'),f=d.axisCenterX;return b=e.config.axisDimention.centerX,a=b!==f,d.axisCenterX=b,a||super._contextChanged()}getPlotInCategoryAt(a,b){let c=this.components.data,d=this.getFromEnv('xAxis'),e=this.getState('visible'),f=Math.round(getPlotFromPixel({x:a,y:b},d)),g=c.find((a,b,c)=>{let e,g,h,i,j,k;return 0===b?(j=c[b+1],k=c[c.length-1]):b===c.length-1?(j=c[0],k=c[c.length-2]):(j=c[b+1],k=c[b-1]),e=getPlotFromPixel({x:a.config._Px,y:a.config._Py},d),g=b===c.length-1?360:getPlotFromPixel({x:j.config._Px,y:j.config._Py},d),h=getPlotFromPixel({x:k.config._Px,y:k.config._Py},d),i=(g-e)/2,f<=0+i?0<=f&&f<=0+i:f>360-i?f>=360-i&&360>=f:f>=h+i&&f<=g-i}),h=this._getHoveredPlot(a,b);return e&&h?h:!!(e&&g)&&{pointIndex:g._index,hovered:!1,pointObj:g}}_addLegend(){var a,b,c,d,e,f=this,g=f.config,h=f.getFromEnv('chart-attrib'),i=f.getFromEnv('legend'),j=g.legendSymbolColor,k=pluckNumber(h.use3dlighting,h.useplotgradientcolor,1);a=getLightColor(j,60).replace(dropHash,HASHSTRING),k?(e=getLightColor(j,40),b={FCcolor:{color:j+','+j+','+e+','+j+','+j,ratio:'0,70,30',angle:270,alpha:'100,100,100,100,100'}}):b={FCcolor:{color:j,angle:0,ratio:'0',alpha:'100'}},c={enabled:g.includeInLegend,type:f.type,label:getFirstValue(f.JSONData.seriesname)},g.includeinlegend?(d=i.getItem(f.config.legendItemId),!d&&(f.config.legendItemId=i.createItem(f),d=i.getItem(f.config.legendItemId),f.addExtEventListener('fc-click',function(){d.itemClickFn()},d)),d.configure(c),d.setStateCosmetics('default',{symbol:{fill:toRaphaelColor(b),rawFillColor:j,stroke:toRaphaelColor(a)}}),f.getState('visible')?d.removeLegendState('hidden'):d.setLegendState('hidden')):f.config.legendItemId&&i.disposeItem(f.config.legendItemId)}getOldPath(a,b){var c,d=this,e=b.x,f=b.y,g=d.config&&d.config.prevLim,h=g.x,j=g.y,k=0,l=a.pathArr.length,m=function(a){return a=(a-h.minPixel.x)/(h.maxPixel.x-h.minPixel.x),a=a*(h.max-h.min)+h.min,a=(a-e.min)/(e.max-e.min),a*(e.maxPixel.x-e.minPixel.x)+e.minPixel.x+1},n=function(a){return(a<j.base&&a>f.base||a>j.base&&a<f.base)&&(a=f.base),a-1},o=function(a){return a===j.base?f.base:n(a)};if(j.min===f.min&&h.min===e.min&&j.max===f.max&&h.max===e.max)return a;if(a=extend2({},a),!g)return[];for(a.pathArr=a.pathArr.slice(0),a.path2Arr=a.path2Arr.slice(0),k=l;k--;)(c=a.pathArr[k].slice(0),c[1]&&c.join)&&(c[1]=m(c[1]),c[2]=o(c[2]),a.pathArr[k]=c);for(k=a.path2Arr.length;k--;)(c=a.path2Arr[k].slice(0),c[1]&&c.join)&&(c[1]=m(c[1]),c[2]=o(c[2]),a.path2Arr[k]=c);return a}_setConfigure(){var a,b,c,d,e=this,f=e.config,g=e.config.JSONData,h=g.data||[],j=e.getFromEnv('xAxis').getTicksLen();for(f.imageCount=0,c=e.components.data,c||(c=e.components.data=[]),f.maxRadius=-Infinity,d=0;d<j;d++)b=c[d],a=h&&h[d]||{},b||(b=c[d]={}),b.config||(c[d].config={}),b.graphics||(b.graphics={}),e._plotConfigure(d,a)}}export default RadarDataset;