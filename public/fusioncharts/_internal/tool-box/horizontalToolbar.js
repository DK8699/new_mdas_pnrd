import{ToolBarFactoryHelper,defaultHToolbarConf}from'./toolBarFactoryHelper';import{StoreInstance}from'./storeInstance';import ComponentInterface from'../../core/component-interface';let UNDEF;class HorizontalToolbar extends ComponentInterface{configure(a,b,c){var d=this.config;d.id='TB_MASTER'+a,StoreInstance.setNewInstance(d.id,this),d.toolbarRect=UNDEF,d.componentGroups=[],d.toolbarConfig=defaultHToolbarConf,d.group=UNDEF,d.pId=b,d.x=0,d.y=0,d.chartId=c,d.configured=!0}getId(){return this.config.id}getName(){return'horizontalToolbar'}getType(){return'toolbar'}addComponent(a){this.config.componentGroups.push(a)}setMenuBarDimension(a,b){let c=this.config;a&&(c.x=a),b&&(c.y=b)}draw(a){var b,c,d,e,f,g,h,i,j,k,l,m=this.config,n=m.x,o=m.y,p=m.componentGroups,q=m.id,r=n,s=o,t=m.toolbarConfig,u=Number.NEGATIVE_INFINITY,v=ToolBarFactoryHelper.getComponentPool(m.chartId),w=v.getKeys();for(a=a||{},e=m.parentLayer||a.parentGroup||{},l=v.getComponent(m.id,m.pId,w.KEY_GROUP,!0),m.group=f=l(q,e),l=v.getComponent(m.id,m.pId,w.KEY_RECT,!0),g=m.toolbarRect=l(f).attr({height:0,width:0,x:r,y:s}),r+=t.hPadding,s+=t.vPadding,(c=0,d=p.length);c<d;c++)b=p[c],k=b.config.groupConfig.spacing||1,j=b.draw(f,{x:r,y:s},c,!m.configured),r+=j.width+k,u=u>j.height?u:j.height;return i=u,h=r-k-t.hPadding-n,isFinite(i)||(i=0),isFinite(h)||(h=0),g.attr({height:i+=2*t.vPadding,width:h+=2*t.hPadding}).attr({fill:t.fill,r:t.radius,stroke:t.borderColor,"stroke-width":t.borderThickness}),this.drawn=!0,m.configured=!1,{height:i,width:h}}getLogicalSpace(){var a,b,c,d,e,f=this.config,g=f.componentGroups,h=0,i=0,j=Number.NEGATIVE_INFINITY,k=0;for(a=0,b=g.length;a<b;a++)c=g[a],e=c.getLogicalSpace(),d=c.config.groupConfig.spacing||1,i+=e.width+d,k+=e.width,j=j<e.height?e.height:j;return k?(i-=d-2*f.toolbarConfig.hPadding,h=j+2*f.toolbarConfig.vPadding,{width:i,height:h}):{width:0,height:0}}dispose(){for(var a=this.config,b=ToolBarFactoryHelper.getComponentPool(a.chartId),c=a.componentGroups,d=0,e=c.length;d<e;d++)c[d].dispose();c.length=0,a.toolbarRect&&a.toolbarRect.remove(),b.emptyPool(a.pId)}_dispose(){this.dispose(),super._dispose()}}export default HorizontalToolbar;