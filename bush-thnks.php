<?php 
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';
	
	$item_model = new ItemModel();
	
	//アイテムデータの取得
	$item_id = (int)@$_GET['item_id'];
	$item = $item_model->getItem($item_id);
	if(empty($item)){
		//$company_modelデータが無いのでindex.phpにリダイレクト
		header("location: index.php");
		exit;
	}
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$division = $division_model->getDivision($item['division_id']);
	$category = $category_model->getCategory($item['category_id']);
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/bush.php?item_id={$item['item_id']}");
	$url = "http://{$server_name}/bush.php?item_id={$item['item_id']}";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<meta name="robots" content="noindex" />


<title><?php echo $item['name']; ?>の口コミをする - 婚活サイト</title>
<meta name="description" content="<?php echo $item['name']; ?>の口コミ投稿ページ。これまで体験した<?php echo $item['name']; ?>の情報をお書きください。">

<meta property="fb:app_id" content="〇〇〇〇" />
<meta property="og:title" content="<?php echo $item['title']; ?>｜Rankroo" />
<meta property="og:type" content="article" />
<meta property="og:image" content="rankroo.jp/<?php echo $item['photo1']; ?>" />
<meta property="og:url" content="<?php echo $social_url; ?>" />
<meta property="og:site_name" content="ランクルー(Rankroo)" />
<meta property="og:description" content="" />

<link rel="shortcut icon" href="common/img/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
<?php @include 'analyticstracking.php'; ?>
</head>

<body>
<div id="wrapper">

<!--header -->
<header id="header">
<div class="inline">
<div class="L">
<p class="SiteLogo"><img src="/common/img/logo.png" width="303" height="77" alt="ランクルー(Rankroo)"></p>
</div>

<article>
<div class="R">
<h1><?php echo $item['h1']; ?></h1>
<?php @include 'part-special.php'; ?>
</div>
</article>
<!-- /#header --></header>

<div id="BreadScrumb">
<div class="Cnt">
<article>
<h2 class="TopicPath">
<div xmlns:v="http://rdf.data-vocabulary.org/#">
<span typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title"><span class="icon-">&#xe609;</span> ランクルーホーム</a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $division['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $category['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $item['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $item['title']; ?></a></span>
</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>

<?php @include 'part-search.php'; ?>

<div id="contents">

<div id ="Main"><!-- MAIN -->


<div class="Block"><!-- BUSH -->

<div class="Block"><!-- BUSH -->
<div class="H-Line Short Gray">
<h2><span class="icon-">&#xe60c; </span><?php echo $item['name']; ?>に関する口コミ完了</h2>
</div><!-- Title Part -->

<div class="Bush">
<p>口コミの投稿が完了しました。</p>

<form method="POST" action="reputation.php?item_id=<?php echo $item_id; ?>">
<div class="BlinkBtn">
<button type="submit" id="contact-submit"><span class="icon-">&#xe618; </span>投稿したクチコミに戻る</button>
</div>
</form>
<!-- /Form -->




</div>
</div><!-- BUSH -->




</div><!-- MAIN -->
<?php @include 'side.php'; ?>
<!-- /#contents --></div>
<?php @include 'footer.php'; ?>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function onTextCount()
{
	var text = $("#text").val();
	$("#NumCount").html(text.length);
}
</script>
<script>
$(function () {
//IE Label img
        //if ($.browser.msie) {
                $('label').click(function () {
                        $('#' + $(this).attr('for')).focus().click();
                });
        //}
});


initThumbnail(1);
initThumbnail(2);
initThumbnail(3);
function initThumbnail(id,w,h)
{
	var obj1 = document.getElementById('photo'+id);
	obj1.addEventListener("change", function(evt){
		var file = evt.target.files;
		var fname = file[0].name;
		var ext = fname.match(/(.+)(\.[^.]+$)/);
		
		if(ext != null && (ext[2].toLowerCase() == '.jpg' || ext[2].toLowerCase() == '.png')){
			$('#thum'+id).html('');
		}else{
			$('#photo'+id).val('');
			$('#thum'+id).html('<span class="fc-red">jpg, pngファイルを指定してください</span>');
		}
	},false);
}
</script>
</body>
</html>
