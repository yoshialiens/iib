
$(function(){
	$('nav ul li img').hover(
		function(){$(this).fadeTo(400, 0);},
		function(){$(this).fadeTo(400, 1.0);}
	);
	$('#Offer .btn a img').hover(
		function(){$(this).fadeTo(0, 0);},
		function(){$(this).fadeTo(0, 1.0);}
	);
	$('#Offer .toTop a img').hover(
		function(){$(this).fadeTo(200, 0);},
		function(){$(this).fadeTo(200, 1.0);}
	);
});
function sound(){
	//[ID:sound-file]の音声ファイルを再生[play()]する
	$("#sound-file").get(0).play();
}


//カウントダウン
function CountdownTimer(elm,tl,mes){
 this.initialize.apply(this,arguments);
}
CountdownTimer.prototype={
 initialize:function(elm,tl,mes) {
  this.elem = document.getElementById(elm);
  this.tl = tl;
  this.mes = mes;
 },countDown:function(){
  var timer='';
  var today=new Date();
  var day=Math.floor((this.tl-today)/(24*60*60*1000));
  var hour=Math.floor(((this.tl-today)%(24*60*60*1000))/(60*60*1000));
  var min=Math.floor(((this.tl-today)%(24*60*60*1000))/(60*1000))%60;
  var sec=Math.floor(((this.tl-today)%(24*60*60*1000))/1000)%60%60;
  // var milli=Math.floor(((this.tl-today)%(24*60*60*1000))/10)%100;
  var me=this;

  if( ( this.tl - today ) > 0 ){
   if (day) timer += '<span class="day">'+day+'</span>';
   if (hour) timer += '<span class="hour">'+hour+'</span>';
   timer += '<span class="min">'+this.addZero(min)+'</span><span class="sec">'+this.addZero(sec)+'</span>';
   this.elem.innerHTML = timer;
   tid = setTimeout( function(){me.countDown();},10 );
  }else{
   this.elem.innerHTML = this.mes;
   return;
  }
 },addZero:function(num){ return ('0'+num).slice(-2); }
}
function CDT(){
 var tl = new Date('2015/7/20 23:59:59');
 var timer = new CountdownTimer('CDT',tl,'終了しました');
 timer.countDown();
}
window.onload=function(){
 CDT();
}