import ColumnMultiSeriesgroup from'../../../../../fc-charts/src/dataset/groups/column-multiseries';import{extend2}from'../../../../../fc-core/src/lib';class DragColumnMultiseriesGroup extends ColumnMultiSeriesgroup{getJSONData(){var a,b,c,d,e=this,f=e.getFromEnv('dataSource').dataset,g=e.getChildren('dataset'),h=[],j=g.length;for(d=0;d<j;d++)a=g[d],c=extend2({},f[d]),delete c.data,b=a.getJSONData(),h.push(extend2(c,b));return h}}export default DragColumnMultiseriesGroup;