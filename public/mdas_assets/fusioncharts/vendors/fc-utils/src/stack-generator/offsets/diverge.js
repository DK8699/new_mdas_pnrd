export default((a,b)=>{const c=a.length;if(1<c){let d,e,f,g,h=a[b[0]].length,k=0,i=0;for(i=0;i<h;++i)for(g=0,f=g,k=0;k<c;++k)d=a[b[k]][i],e=d[1]-d[0],0<=e?(d[0]=f,f+=e,d[1]=f):0>e?(d[1]=g,g+=e,d[0]=g):d[0]=f}});