import{pluckNumber,pluck,pluckFontSize,hiddenStr,visibleStr}from'../../../../fc-core/src/lib';import{pluckBoolean}from'../utils';import AnnotationBaseShape from'./annotation-base';import mergeDeepRight from'ramda/src/mergeDeepRight';const DEFAULT_IS_VISIBLE=!0,DEFAULT_OPACITY=100,DEFAULT_COLOR='#FF0000',DEFAULT_SHOW_SHADOW=0,DEFAULT_POSITION=0;class AnnotationGroup extends AnnotationBaseShape{constructor(a={}){super(a),this.groups=[],this.items=[]}getName(){return'group'}configureAttributes(a){var b=this,c={},d=b.config;b.rawConfig={},c=mergeDeepRight(b.rawConfig,a),b.rawConfig=c,d.id=b.rawConfig.id||b.getId('group'),d.autoScale=pluckNumber(b.rawConfig.autoScale,1),d.scaleText=pluckNumber(b.rawConfig.scaleText,0),d.scaleX=d.origScaleX=pluckNumber(b.rawConfig.xScale)/100,d.scaleY=d.origScaleY=pluckNumber(b.rawConfig.yScale)/100,d.scaleImages=pluckNumber(b.rawConfig.scaleImages,0),d.constrainedScale=pluckNumber(b.rawConfig.constrainedScale,1),d.origH=+b.rawConfig.origH,d.origW=+b.rawConfig.origW,d.link=b.rawConfig.link,d.color=b.rawConfig.color||DEFAULT_COLOR,d.alpha=pluckNumber(parseFloat(b.rawConfig.alpha),DEFAULT_OPACITY),d.showShadow=pluckNumber(b.rawConfig.showShadow,DEFAULT_SHOW_SHADOW),d.x=pluckNumber(b.rawConfig.x,b.rawConfig.xPos,DEFAULT_POSITION),d.y=pluckNumber(b.rawConfig.y,b.rawConfig.yPos,DEFAULT_POSITION),d.font=pluck(b.rawConfig.font,'Verdana, sans'),d.fontSize=pluckFontSize(b.rawConfig.fontSize,10),d.textAlign=pluck(b.rawConfig.textAlign),d.textVAlign=pluck(b.rawConfig.textVAlign),d.rotateText=pluck(b.rawConfig.rotateText),d.wrapText=pluck(b.rawConfig.wrapText),d.grpXShift=pluckNumber(b.rawConfig.grpXShift,0),d.grpYShift=pluckNumber(b.rawConfig.grpYShift,0),d.xShift=pluckNumber(b.rawConfig.xShift,0),d.yShift=pluckNumber(b.rawConfig.yShift,0),d.toolText=b.rawConfig.toolText,d.isVisible=pluckBoolean(b.rawConfig.isVisible,DEFAULT_IS_VISIBLE),d.elementType='group',d.containerConfiguration={id:b.rawConfig.showBelow?'lowerAnnotationGroup':'upperAnnotationGroup',label:'group',isParent:!0},d.animationLabel='group'}updateScale(){var a=Math.min;let b,c,d,e,f,g=this,h=g.config,i=g._getConfig('origScaleX'),j=g._getConfig('origScaleY'),k=g.getFromEnv('chart'),l=+k.getFromEnv('chartHeight'),m=+k.getFromEnv('chartWidth'),n=g._getConfig('autoScale'),o=g._getConfig('constrainedScale');g._setConfig('origW',+g._getConfig('origW')||m),g._setConfig('origH',+g._getConfig('origH')||l),n?(c=m/g._getConfig('origW')*(+i||1),d=l/g._getConfig('origH')*(+j||1)):c=d=1,b=a(c,d),g._setConfig('scaleValue',b),(0<n||isNaN(g._getConfig('scaleX')))&&(e=o?b:c,g._setConfig('scaleX',e)),(0<n||isNaN(g._getConfig('scaleY')))&&(f=o?b:d,g._setConfig('scaleY',f)),g._setConfig('scaleFont',g._getConfig('scaleText')?a(g._getConfig('scaleX'),g._getConfig('scaleY')):1),g._setConfig('scaleImageX',g._getConfig('scaleImages')?g._getConfig('scaleX'):1),g._setConfig('scaleImageY',g._getConfig('scaleImages')?g._getConfig('scaleY'):1),h.scaleInfo={scaleX:h.scaleX,scaleY:h.scaleY,scaleValue:h.scaleValue}}updateAttr(){let a,b,c,d=this,e=d.config,f=e.calculatedAttrs;for(a in f)f[a]&&(e[a]=f[a]);b=d.getScaledVal(d._getConfig('grpXShift'))+d._getConfig('xShift'),c=d.getScaledVal(d._getConfig('grpYShift'))+d._getConfig('yShift'),d._setConfig('attr',{transform:`T${b},${c}`})}retrieveItem(a,b){let c,d,e=this;if(b)e.detachChild(a);else for(c=0,d=e.items.length;c<d;c++)if(e.items[c].getId()===a)return e.items[c]}getScaledVal(a,b){let c,d=this;return d?(c=b?d._getConfig('scaleX'):(!1===b?d._getConfig('scaleY'):d._getConfig('scaleValue'))||1,a*c):a}getScaledFont(a){let b=this;return b?a*b._getConfig('scaleFont'):a}getScaledImageVal(a,b){let c,d=this;return d?(c=(b?d._getConfig('scaleImageX'):d._getConfig('scaleImageY'))||1,a*c):a}getScaleInfo(){return this.config.scaleInfo}draw(){var a,b,c=this,d=c._getConfig('attr')||{};c.updateScale(),c.parseAndSetAttribute(),a=c.getScaledVal(c._getConfig('grpXShift'))+c._getConfig('xShift'),b=c.getScaledVal(c._getConfig('grpYShift'))+c._getConfig('yShift'),d.name=c._getConfig('id'),d.transform=d.transform||`T${a},${b}`,d.visibility=c.config.isVisible?visibleStr:hiddenStr,c.addGraphicalElement({el:'group',attr:d,container:c.config.containerConfiguration,component:c._getFromStore('component')||c,label:c.config.animationLabel,id:c.config.id})}dispose(){let a,b,c=this._getFromStore('originalComponent'),d=c.retrieveGroup(this.config.id,!0),e=d.items||[];for(b=0;b<e.length;b++)a=c.retrieveItem(e[b].getId(),!0),a&&a.dispose();c.removeGraphicalElement(d.config.id),d.items=[]}}export default AnnotationGroup;