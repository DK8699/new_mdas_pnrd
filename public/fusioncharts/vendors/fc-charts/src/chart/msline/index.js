import AreaBase from'../_internal/areabase';import LineDataset from'../../dataset/line';let UNDEF;class MSLine extends AreaBase{static getName(){return'MSLine'}constructor(){super(),this.defaultPlotShadow=1,this.axisPaddingLeft=0,this.axisPaddingRight=0}getName(){return'MSLine'}__setDefaultConfig(){super.__setDefaultConfig();let a=this.config;a.friendlyName='Multi-series Line Chart',a.defaultDatasetType='line',a.zeroplanethickness=1,a.zeroplanealpha=40,a.showzeroplaneontop=0,a.enablemousetracking=!0,a.defaultcrosslinethickness=1}getDSdef(){return LineDataset}getDSGroupdef(){return UNDEF}}export default MSLine;