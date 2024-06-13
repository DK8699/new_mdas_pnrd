import{SmartRenderer}from'../../component-interface';import{pluckNumber,touchEnabled,pluck,convertColor}from'../../lib';import{preConfig as _preConfig}from'./helper';import{UNDEF}from'../../../../fc-features/src/annotation/utils';let _optionSanitization=a=>{for(const b in a)if(a.hasOwnProperty(b)){const c=a[b];(c===UNDEF||''===c)&&delete a[b]}return a};class Tool extends SmartRenderer{constructor(a){super(a),this._listeners={}}getName(){return'button'}getType(){return'tool'}__setDefaultConfig(){var a=this,b=a.config;b.scale=1.15,b.width=touchEnabled?20:15,b.height=touchEnabled?20:15,b.radius=touchEnabled?4:2,b.spacing=2.5,b.marginTop=2.5,b.marginLeft=2.5,b.marginRight=2.5,b.marginBottom=2.5,b.fill='ffffff',b.fillAlpha='cccccc',b.symbolFill='ffffff',b.stroke='bbbbbb',b.symbolStroke='9a9a9a',b.strokeWidth=1,b.symbolStrokeWidth=1,b.symbolPadding=5,b.symbolHPadding=5,b.symbolVPadding=5,b.fillAlpha=100,b.stateStyle={},b.text='',b.marginTop=b.marginLeft=b.marginRight=b.marginBottom=2.5,b.labelFontSize=12}configureAttributes(a={}){let b,c,d,e=this,f=e.config;a=_optionSanitization(a),Object.assign(f,a),f.symbolName=f.name,this.setState('visible',!0!==f.isHidden),f.marginTop=pluckNumber(a.marginTop,a.spacing,f.marginTop),f.marginLeft=pluckNumber(a.marginLeft,a.spacing,f.marginLeft),f.marginRight=pluckNumber(a.marginRight,a.spacing,f.marginRight),f.marginBottom=pluckNumber(a.marginBottom,a.spacing,f.marginBottom),f.fill=pluck(a.fill,f.fill),f.fillAlpha=pluckNumber(a.fillAlpha,f.fillAlpha),f.labelFill=pluck(a.labelFill,f.labelFill),f.symbolFill=pluck(a.symbolFill,f.symbolFill),f.hoverFill=pluck(a.hoverFill,f.hoverFill),f.stroke=pluck(a.stroke,f.stroke),f.symbolStroke=pluck(a.symbolStroke,f.symbolStroke),f.strokeWidth=pluckNumber(a.strokeWidth,f.strokeWidth),f.symbolStrokeWidth=pluckNumber(a.symbolStrokeWidth,f.symbolStrokeWidth),b=f.symbolPadding=pluckNumber(a.symbolPadding,f.symbolPadding),f.symbolHPadding=pluckNumber(a.symbolHPadding,b),f.symbolVPadding=pluckNumber(a.symbolVPadding,b),f.hAlign=pluck(a.hAlign,'center').toLowerCase(),f.vAlign=pluck(a.vAlign,'middle').toLowerCase(),f.containerInfo=a.containerInfo||{id:'group',label:'group',isParent:!0},c=a.x,d=a.y,'undefined'==typeof c||'undefined'==typeof d?f.spaceNotHardCoded=!0:(f.x=c,f.y=d,f.spaceNotHardCoded=!1)}setDimension(a={}){let b=this.config;a.x!==void 0&&(b.x=a.x),a.y!==void 0&&(b.y=a.y),a.width!==void 0&&(b.width=b.width&&b.width>a.width?b.width:a.width),a.height!==void 0&&(b.height=b.height&&b.height>a.height?b.height:a.height)}getAlignment(){return{hAlign:this.config.hAlign,vAlign:this.config.vAlign}}getLogicalSpace(){let{width:a,height:b,marginTop:c,marginLeft:d,marginRight:e,marginBottom:f,scale:g}=this.config;return a*=g,b*=g,(this.config.skipGraphics||this.config.isHidden||this.getState('removed'))&&(a=b=f=d=e=c=0),{width:a,height:b,marginTop:c,marginLeft:d,marginRight:e,marginBottom:f}}setCurrentState(a){this.config.state=a,this.asyncDraw()}getCurrentState(){return this.config.state}draw(){let a,b,c,d=this,e=d.config,f=e,g=e.text,h=e.labelFontSize,i=e.labelFontFamily;e.skipGraphics||(a={width:e.width*e.scale,height:e.height*e.scale,r:e.radius,verticalPadding:e.symbolVPadding*e.scale,horizontalPadding:e.symbolHPadding},b=Object.assign({button:[e.x,e.y,g,e.symbolName,a,h,i],"button-label":g,"button-padding":[e.symbolHPadding,e.symbolVPadding*e.scale],"button-repaint":[f.x,f.y,e.width*e.scale,e.height*e.scale,e.radius*e.scale],fill:convertColor(e.fill),labelFill:convertColor(e.labelFill),symbolFill:convertColor(e.symbolFill),hoverFill:e.hoverFill,stroke:convertColor(e.stroke),"symbol-stroke":convertColor(e.symbolStroke||e.stroke),"stroke-width":e.strokeWidth,"symbol-stroke-width":pluckNumber(e.symbolStrokeWidth,e.strokeWidth)},e.stateStyle[e.state]||_preConfig[e.state]||{}),c=e.btnTextStyle,!e.isHidden&&d.addGraphicalElement({el:'button',attr:b,css:c,component:d,container:e.containerInfo,label:'button',id:'button',tooltext:e.tooltext}))}hide(){this.config.isHidden=!0,this.setState('visible',!1),this.asyncDraw()}show(){this.config.isHidden=!1,this.setState('visible',!0),this.asyncDraw()}}export default Tool;