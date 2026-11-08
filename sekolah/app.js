// angka statistik naik pelan-pelan
document.querySelectorAll('.stat b').forEach(function(b){
  var t=b.textContent.trim(), n=parseFloat(t);
  if(isNaN(n)||!/^[\d.]+$/.test(t))return;
  var d=t.indexOf('.')>-1?1:0, s=null;
  (function f(ts){s=s||ts;var p=Math.min((ts-s)/1000,1);
    b.textContent=(n*(1-Math.pow(1-p,3))).toFixed(d);
    if(p<1)requestAnimationFrame(f);})(performance.now());
});
// baris tabel muncul satu-satu
document.querySelectorAll('table tr').forEach(function(r,i){
  r.style.animation='naik .45s '+Math.min(i*45,700)+'ms ease both';
});
// efek riak di tombol
document.addEventListener('click',function(e){
  var b=e.target.closest('.btn'); if(!b)return;
  var r=document.createElement('span'),x=b.getBoundingClientRect(),z=Math.max(x.width,x.height);
  r.className='rip';r.style.width=r.style.height=z+'px';
  r.style.left=(e.clientX-x.left-z/2)+'px';r.style.top=(e.clientY-x.top-z/2)+'px';
  b.appendChild(r);setTimeout(function(){r.remove()},600);
});