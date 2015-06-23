<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CommentModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	$archive_id = (int)@$_GET['archive_id'];
	
	$special_model = new SpecialModel();
	$special = $special_model->getArchive($archive_id);
	if($special === false){
		header("location: index.php");
		exit;
	}
	if($special['disp'] == 0){
		//dispが0なら非表示なコラム
		header("location: index.php");
		exit;
	}
	
	$item_model = new ItemModel();
	$item = $item_model->getItem($special['item_id']);
	
	//$item_idはside.phpで参照する
	$item_id = 0;
	if(!empty($item)){
		$item_id = $item['item_id'];
	}
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$division = $division_model->getDivision($special['division_id']);
	$category = $category_model->getCategory($special['category_id']);
	
	//TODO: 仮定義
	$url = "#";
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/special.php?archive_id={$special['archive_id']}");
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<meta name="robots" content="ALL" />
<title><?php echo @$special['name']; ?></title>
<meta name="description" content="<?php echo @$special['description']; ?>">


<meta property="og:title" content="<?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?>｜-Rankroo" />
<meta property="og:type" content="article" />
<meta property="og:image" content="http://rankroo.jp/<?php echo $special['photo']; ?>" />
<meta property="og:url" content="<?php echo $social_url; ?>" />
<meta property="og:site_name" content="Rankroo" />
<meta property="og:description" content="<?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?>」" />
<meta property="fb:app_id" content="〇〇〇〇" />

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
<h1><?php echo $special['h1']; ?></h1>
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
<span typeof="v:Breadcrumb"><a href="field.php?division_id=<?php echo $division['division_id']; ?>" rel="v:url" property="v:title"><?php echo @$division['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$category['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$special['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$special['title']; ?></a></span>
</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>

<?php @include 'part-search.php'; ?>



<div id="contents">


<div id ="Main"><!-- MAIN -->

<div class="Block">

<article>
<div class="H-Line Blue">
<h1 class="Title"><?php echo $special['title']; ?></h1>
</div><!-- Title Part -->

<div class="ExpBlock"><!-- ExpBlock -->


<?php echo $special['colume']; ?>



<!-- SOCIAL BUTTONS START -->
<div class="snsb">
<ul>
<li class="hatena"><a href="http://b.hatena.ne.jp/entry/http://<?php echo $server_name; ?>/special.php?archive_id=<?php echo $special['archive_id']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?>｜Rankroo" data-hatena-bookmark-layout="standard" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async></script></li>
<li class="btns"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $social_url; ?>&amp;send=false&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=20" scrolling="no" frameborder="0" style="border:none;overflow:hidden;width:100px;height:20px" allowTransparency="true"></iframe></li>
<li class="tw"><a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?>｜Rankroo" data-lang="ja">ツイート</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
<li class="google"><div class="g-plusone" data-size="medium" data-href="<?php echo $social_url; ?>"></div>
<script type="text/javascript">
  window.___gcfg = {lang: 'ja'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
</ul>
</div>
<!-- SOCIAL BUTTONS END -->



</div><!-- /SpecialBlock -->

</article>
</div><!-- /Block -->



<div class="Block" id="explist">
</div>



</div><!-- MAIN -->



<?php @include 'side.php'; ?>
<!-- /#contents --></div>
<?php @include 'footer.php'; ?>
<!-- /#wrapper --></div>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/common/js/smoothscroll.js"></script>
<script src="/common/js/script.js"></script>
<script>
$(document).ready(function(){
	get_speciallist(0);
});
function get_speciallist(p)
{
	$.ajax({
		type: 'GET',
		url: 'speciallist-ajax.php?archive_id=<?php echo $archive_id; ?>&p='+p,
		cache: false,
		success: function(columnlist){
			$("#explist").html(columnlist);
		}
	});
}
</script>
</body>
</html>