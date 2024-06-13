import{pluck,parseUnsafeString,datasetFactory}from'../../../fc-core/src/lib';import{TrendSet,VolumeDataset}from'../dataset/candlestick';let arraySpliceByValue=(a,b)=>{let c=a.indexOf(b);-1!==c&&a.splice(c,1)},removeComponents=(a,b)=>{let c;a&&a.iterateComponents(a=>{for(c=0;c<b.length;c++)a.getName()===b[c]&&a.remove()})};export default function(a){let b,c,d,e,f=a.getFromEnv('dataSource'),g=f.dataset,h=f.trendset,i=a.getFromEnv('chart-attrib'),j=a.getFromEnv('chartConfig').showVolumeChart,k=pluck(parseUnsafeString(i.plotpriceas).toLowerCase(),'candlestick'),l=a.getChildren(),m=l.canvas[0],n=m.getChildren('vCanvas')[0],o=['candlestick','candlestickbar','candlestickline','trendset'];return g?void(d=g.slice(0),d[0]&&d[0].data&&d[0].data.sort(function(c,a){return c.x-a.x}),e=a.getDSdef(k),datasetFactory(n,e,'dataset_'+k,g.length,d),'bar'===k?arraySpliceByValue(o,'candlestickbar'):'line'===k?arraySpliceByValue(o,'candlestickline'):arraySpliceByValue(o,'candlestick'),j&&(b=l.canvas[1],c=b.getChildren('vCanvas')[0],datasetFactory(c,VolumeDataset,'dataset_volume',g.length,g)),h&&(datasetFactory(n,TrendSet,'dataset_trendset',h.length,h),arraySpliceByValue(o,'trendset')),removeComponents(n,o)):void a.setChartMessage()}