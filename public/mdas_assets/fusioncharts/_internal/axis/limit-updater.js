import ComponentInterface from'../../core/component-interface';import domEvtHandler from'../dom/dom-event';import{createElement,hashify,extend2,isIE,hasSVG,rgbaToHex,hasTouch}from'../lib/lib';const ENTER_CODE=13,ESCAPE_CODE=27;let linkedAxis;function getCharCode(a){return'number'==typeof a.which?a.which:a.keyCode}function onMouseUp(){var a=this;a.justFocussed&&(a.justFocussed=!1,!hasTouch&&a.select())}function doAxisUpdate(a,b,c){var d=!1,e=linkedAxis.getLinkedParent();if(a!==b+'')return d=c?e.changeUpperLimits&&e.changeUpperLimits(+a):e.changeLowerLimits&&e.changeLowerLimits(+a),d}function onFocus(){var a,b=this,c={opacity:1,filter:'alpha(opacity=100)',color:rgbaToHex(b.axisLabel.attr('fill'))};for(a in c)b.style[a]=c[a];b.value=b.dataValue,b.justFocussed=!0,b.hasFocus=!0,b.axisLabel&&b.axisLabel.hide()}function onBlur(){var a=this,b=a.value,c=a.oldValue,d=a.isMaxLabel;doAxisUpdate(b,c,d),a.style.opacity=0,a.style.filter='alpha(opacity=0)',a.axisLabel&&a.axisLabel.show(),isIE&&document.getElementsByTagName('body')[0].focus&&document.getElementsByTagName('body')[0].focus(),a.justFocussed=!1,a.hasFocus=!1}function onKeyUp(a){var b,c=this,d=getCharCode(a.originalEvent),e=c.value,f=c.oldValue,g=c.isMaxLabel;d===ENTER_CODE?(b=doAxisUpdate(e,f,g),!1===b?c.style.color='#dd0000':domEvtHandler.fire(c,'blur',a)):d===ESCAPE_CODE&&(c.value=f,domEvtHandler.fire(c,'blur',a))}function defaultHandler(a){return function(b){a.parentNode&&domEvtHandler.fire(a,'blur',b)}}function defaultIEHandler(a){return function(b){b.target!==a&&a.hasFocus&&domEvtHandler.fire(a,'blur',b)}}function destroyHandler(a,b){return function(){domEvtHandler.unlisten(linkedAxis.getLinkedParent(),'defaultprevented',b),a.parentNode.removeChild(a)}}function destroyIEHandler(a,b){return function(){domEvtHandler.unlisten(linkedAxis.getLinkedParent().getLinkedItem('container'),'mousedown',b),a.parentNode.removeChild(a)}}class LimitUpdater extends ComponentInterface{configureAttributes(){this.config.linkedAxis=this.getLinkedParent(),linkedAxis=this.config.linkedAxis}getType(){return'helper'}getName(){return'limitUpdater'}draw(){let a,b,c,d,e,f,g,h,i,j,k,l,m=this,n=m.getFromEnv('chartConfig'),o=m.config.linkedAxis,p=o.getAxisConfig('extremeLabels'),q=o.getLimit(),r=m.getFromEnv('chart-container'),s=m.getFromEnv('style').inCanvasStyle,t=extend2({outline:'none',"-webkit-appearance":'none',filter:'alpha(opacity=0)',position:'absolute',background:'transparent',border:'1px solid #cccccc',textAlign:'right',top:0,left:0,width:50,zIndex:20,opacity:0,borderRadius:0,display:'block'},s),u={max:{element:p.lastLabel.graphic,value:q.max},min:{element:p.firstLabel.graphic,value:q.min}};for(d in t.color=hashify(t.color),t.fontSize=`${t.fontSize}px`,u)if(u.hasOwnProperty(d)){if(c=u[d].element,f=c&&c.getBBox(),h=u[d].value,g='max'===d,b=`${d}Input`,a=m.getGraphicalElement(b),!(f&&c)){a&&(a.style.display='none');continue}for(e in a||(a=m.addGraphicalElement(b,createElement('input',{type:'text',value:h,id:`fc-updater-${d}`},r))),domEvtHandler.listen(a,['focus','mouseup','blur','keyup'],[onFocus,onMouseUp,onBlur,onKeyUp]),hasSVG?(domEvtHandler.listen(r,'defaultprevented',i=defaultHandler(a)),domEvtHandler.listen(r,'destroy',destroyHandler(a,i))):(domEvtHandler.listen(r,'mousedown',l=defaultIEHandler(a)),domEvtHandler.listen(r,'destroy',destroyIEHandler(a,l))),j=f.x+f.width-n.marginLeft,k=n.marginLeft,t.top=`${f.y}px`,t.left=`${k}px`,t.width=`${j}px`,t)t.hasOwnProperty(e)&&(a.style[e]=t[e]);a.dataValue=h,a.value=h,a.oldValue=h,a.name=h||'',a.axisLabel=c,a.isMaxLabel=g}}removingDraw(){let a,b,c=this.getGraphicalElement();for(a in c)c.hasOwnProperty(a)&&(b=c[a],b&&b.parentNode&&b.parentNode.removeChild(b),delete c[a])}}export default LimitUpdater;