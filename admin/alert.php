<div id="alert_complete" style="position:absolute;left:0;top:0;overflow:hidden;width:100%;height:0;background-color:#3A5795">
<p style="text-align:center;color:#ffffff;font-size:24px;">完了しました</p>
</div>
<script src="common/js/jquery-1.6.3.min.js"></script>
<script>
$(document).ready(function(){
	if(location.hash=="#upd")
	{
		$("#alert_complete").animate({"height":"40px"}, 500).delay(1000).animate({"height":"0px"}, 300, function(){
			$(this).hide();
			location.href = location.protocol + "//" + location.host + location.pathname + location.search;
		});
	}
});
</script>
