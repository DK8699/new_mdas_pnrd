import MSBar2D from'../msbar2d/';import CartesianStackGroup from'../../dataset/groups/cartesian-stack';class StackedBar2D extends MSBar2D{static getName(){return'StackedBar2D'}getName(){return'StackedBar2D'}__setDefaultConfig(){super.__setDefaultConfig();let a=this.config;a.friendlyName='Stacked Bar Chart',a.enablemousetracking=!0,a.maxbarheight=50,a.isstacked=!0}getDSGroupdef(){return CartesianStackGroup}}export default StackedBar2D;