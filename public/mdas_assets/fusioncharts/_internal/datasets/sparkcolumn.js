import{BLANKSTRING,pluck,pluckNumber,COMMASTRING,parseTooltext,toRaphaelColor,HUNDREDSTRING}from'../lib/lib';import{getLightColor,getColumnColor}from'../lib/lib-graphics';import{addDep}from'../dependency-manager';import ColumnDataset from'./column';import sparkcolumnAnimation from'../animation-rules/sparkcolumn-animation';var UNDEF,math=Math,mathMin=math.min;addDep({name:'sparkcolumnAnimation',type:'animationRule',extension:sparkcolumnAnimation});class SparkColumnDataset extends ColumnDataset{getType(){return'dataset'}getName(){return'sparkColumn'}parseAttributes(){super.parseAttributes();var a=this,b=a.getFromEnv('chart'),c=a.config,d=a.config.JSONData,e=b.getFromEnv('chart-attrib');c.showValues=pluckNumber(d.showvalues,e.showvalues,0)}_setConfigure(a){var b,c,d,e,f,g,h,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H=this,I=H.getFromEnv('chart'),J=I.getFromEnv('dataSource').chart,K=H.config,L=I.config,M=H.config.JSONData,N=a||M.data,O=N&&N.length,P=H.getFromEnv('xAxis'),Q=P.getTicksLen(),R=a&&a.data.length||mathMin(Q,O),S=L.plothovereffect,T=H.getFromEnv('color-manager'),U=L.useroundedges;for(K.plotgradientcolor=BLANKSTRING,K.showvalues=pluckNumber(M.showvalues,J.showvalues,0),K.showShadow=pluckNumber(J.showshadow,0),super._setConfigure(),E=H.components.data,F=K.maxValue,G=K.minValue,b=pluck(J.plotfillcolor,T.getColor('plotFillColor')),m=pluck(J.plotfillalpha,HUNDREDSTRING),n=pluck(J.plotborderalpha,HUNDREDSTRING),o=pluck(J.plotbordercolor,b),p=pluck(J.highcolor,'000000'),q=pluck(J.lowcolor,'000000'),r=pluck(J.highbordercolor,J.plotbordercolor,p),s=pluck(J.lowbordercolor,J.plotbordercolor,q),B=0;B<R;B++){if(x=N[B],y=E[B],l=y.config,z=null,c=b,d=o,l.setValue==F&&(c=p,d=r,t=l.displayValue),y.config.setValue==G&&(c=q,d=s,u=l.displayValue),l.colorArr=z=getColumnColor(c+COMMASTRING+K.plotgradientcolor,m,'0','90',U,d,n,0,0),0!==S&&z){for(e=f=UNDEF,l.setValue==F&&(e=J.highhovercolor,f=J.highhoveralpha),y.config.setValue==G&&(e=J.lowhovercolor,f=J.lowhoveralpha),e=pluck(x.hovercolor,M.hovercolor,e,J.plotfillhovercolor,J.columnhovercolor,z[0].FCcolor.color),e=e.split(/\s{0,},\s{0,}/),C=e.length,D=0;D<C;D++)e[D]=getLightColor(e[D],70);e=e.join(','),f=pluck(x.hoveralpha,M.hoveralpha,f,J.plotfillhoveralpha,J.columnhoveralpha,m),g=pluck(x.hovergradientcolor,M.hovergradientcolor,J.plothovergradientcolor,K.plotgradientcolor),g||(g=BLANKSTRING),h=pluck(x.borderhovercolor,M.borderhovercolor,J.plotborderhovercolor,K.plotbordercolor),j=pluck(x.borderhoveralpha,M.borderhoveralpha,J.plotborderhoveralpha,J.plotfillhoveralpha,n,m),1==S&&e===z[0].FCcolor.color&&(e=getLightColor(e,70)),A=getColumnColor(e+COMMASTRING+g,f,'0','90',U,h,j.toString(),0,0),l.setRolloutAttr={fill:toRaphaelColor(z[0]),stroke:k&&toRaphaelColor(z[1]),"stroke-width":k},l.setRolloverAttr={fill:toRaphaelColor(A[0]),stroke:k&&toRaphaelColor(A[1]),"stroke-width":k}}l._x=B,l._y=l.setValue}for(B=0;B<R;B++){let a=E[B],b=a.config;b.setTooltext!==UNDEF&&(v=[56,57,60,61],w={highValue:t,highDataValue:t,lowValue:u,lowDataValue:u},b.toolText=parseTooltext(b.setTooltext,v,w,x,J,M),b.finalTooltext=b.setTooltext=b.toolText)}}}export default SparkColumnDataset;