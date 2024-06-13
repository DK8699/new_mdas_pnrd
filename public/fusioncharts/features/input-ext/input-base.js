import{priorityList}from'../../_internal/schedular';import ComponentInterface from'../../core/component-interface';var UNDEF,COLOR_WHITE='#ffffff',COLOR_E3E3E3='#e3e3e3',STR_DEF='default',COLOR_EFEFEF='#efefef',COLOR_C2C2C2='#c2c2c2',preConfig={activated:{config:{hover:{fill:'#ffffff',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#aaaaaa',cursor:'pointer'},normal:{fill:'#ffffff',"fill-symbol":'#ffffff',stroke:'#c2c2c2',"stroke-width":1,cursor:'pointer'},disable:{fill:'#ffffff',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#e3e3e3',"stroke-opacity":1,cursor:'pointer'},pressed:{fill:'#efefef',"fill-symbol":'#efefef',"stroke-width":1,stroke:'#c2c2c2',cursor:'pointer'}},"button-disabled":!1,fill:['#ffffff','#ffffff','#ffffff','#ffffff',!0],stroke:'#c2c2c2',"stroke-opacity":1},disabled:{config:{hover:{fill:'#ffffff',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#e3e3e3',cursor:'default'},normal:{fill:'#ffffff',"fill-symbol":'#ffffff',stroke:'#e3e3e3',"stroke-width":1,cursor:'default'},disable:{fill:'#ffffff',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#e3e3e3',"stroke-opacity":1,cursor:'default'},pressed:{fill:'#ffffff',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#e3e3e3',cursor:'default'}},fill:['#ffffff','#ffffff','#ffffff','#ffffff',!0],"button-disabled":!1,stroke:'#e3e3e3',"stroke-opacity":1},pressed:{config:{hover:{fill:'#dcdcdc',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#b7b7b7',cursor:'pointer'},normal:{fill:'#dcdcdc',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#b7b7b7',cursor:'pointer'},pressed:{fill:'#dcdcdc',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#b7b7b7',cursor:'pointer'}},fill:'#dcdcdc',"fill-symbol":'#ffffff',"stroke-width":1,stroke:'#b7b7b7',cursor:'pointer'}};class InputBase extends ComponentInterface{configure(){var a=this,b=a.config={},c=a.getFromEnv('inputOptions')||{};Object.assign(b,c[a.getName()])}_checkStackLen(){var a=this.getFromEnv('axesObArr'),b=0;return a&&a.forEach(function(a){b+=a.stack.length}),b}static _getZoomInfo(a,b,c){var d={startIndex:Math.ceil(a),endIndex:Math.floor(b)};return c.getLabel&&(d.startLabel=c.getLabel(d.startIndex).label,d.endLabel=c.getLabel(d.endIndex).label),d}_raiseZoomEvents(a,b,c,d){var e=this,f=e.getFromEnv('chart');a&&e.fireEvent(a),b&&f.fireChartInstanceEvent(b,c,d),b&&f.fireChartInstanceEvent('zoomed',c,d)}getType(){return'canvasInput'}enable(a){var b=this;!0!==b.config.enabled&&(b.config.enabled=!0,a&&b.fireEvent(a),b.addJob('settingControl',()=>{b.setControl()},priorityList.draw))}disable(a){var b=this;!1!==b.config.enabled&&(b.config.enabled=!1,a&&b.fireEvent(a),b.addJob('settingControl',()=>{b.setControl()},priorityList.draw))}toggle(a){this.isEnabled()?this.disable(a):this.enable(a)}isEnabled(){return this.config.enabled}setControl(){var a=this.getGraphicalElement('button');a&&(this.isEnabled()?a.enable():a.disable())}createButton(a){var b,c,d,e,f,g,h=this,i=h.getLinkedParent(),j=h.config,k=i.getFromEnv('chart');return c=k.getFromEnv('toolbox'),d=k.getFromEnv('toolBoxAPI'),f=d.Symbol,e=(k.getChildren('chartMenuBar')||k.getChildren('actionBar')).config.componentGroups[0],g=new f,g.configure(a.icon,UNDEF,c.idCount++,c.pId,k.getFromEnv('chartInstance').id),g.addToEnv('toolTipController',h.getFromEnv('toolTipController')),g.attachEventHandlers(a.handlers),e.addSymbol(g,!0),b=g.config,setTimeout(function(){b.node&&b.node.attr(preConfig.activated)}),g.isEnabled=!0,g.enable=function(){g.isEnabled=!0,setTimeout(function(){b.node&&b.node.attr(preConfig[j.state]||preConfig.activated),j.state=UNDEF})},g.disable=function(){g.isEnabled=!1,setTimeout(function(){b.node&&b.node.attr(preConfig[j.state]||preConfig.disabled),j.state=UNDEF})},g}}export default InputBase;