<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	$category_id = (int)@$_GET['category_id'];
	
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	$review_model = new ReviewModel();
	$experience_model = new ExperienceModel();
	$special_model = new SpecialModel();
	
	
	$category = $category_model->getCategory($category_id);
	if(empty($category)){
		//データが無い場合はリダイレクト
		header("location: index.php");
		exit;
	}
	
	$division = $division_model->getDivision($category['division_id']);
	
	$rank_index=1;
	$item_all = $item_model->getItemAllByCategoryId($category['category_id']);
	foreach($item_all as &$v)
	{
		$v['star'] = ItemModel::getOsusumeStarTag($v['point']);
		
		switch($rank_index)
		{
			case 1: $v['rank_class']='First'; $v['rank_icon']='common/img/ranking/rank01.png'; break;
			case 2: $v['rank_class']='Second'; $v['rank_icon']='common/img/ranking/rank02.png'; break;
			case 3: $v['rank_class']='Third'; $v['rank_icon']='common/img/ranking/rank03.png'; break;
			default: $v['rank_class'] = ''; $v['rank_icon']='common/img/ranking/rank-other.png'; break;
		}
		++$rank_index;
		
		unset($v);
	}
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/ranking.php?category_id={$category['category_id']}");
	$url = "http://{$server_name}/ranking.php?category_id={$category['category_id']}";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<meta name="robots" content="ALL" />

<title><?php echo $category['name']; ?></title>
<meta name="description" content="<?php echo $category['description']; ?>">

<meta property="fb:app_id" content="〇〇〇〇" />
<meta property="og:title" content="<?php echo $category['name']; ?>｜Rankroo" />
<meta property="og:type" content="article" />
<meta property="og:image" content="rankroo.jp/<?php echo $category['image1']; ?>" />
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
<h1><?php echo $category['h1']; ?></h1>
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
<span typeof="v:Breadcrumb"><a href="field.php?division_id=<?php echo $division['division_id']; ?>" rel="v:url" property="v:title"><?php echo $division['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $category['name']; ?>のランキング</a></span>
</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>

<?php @include 'part-search.php'; ?>


<div id="contents">


<div id ="Main"><!-- MAIN -->

<div class="Block">
<div class="H-Line Gray Short">
<h2><span class="icon-">&#xe68f;</span> <?php echo $category['name']; ?></h2>
</div><!-- Title Part -->

<div class="JenreDetail"><!-- JenreDetail -->
<div class="Photo"><?php if(!empty($category['image1'])){ ?><img src="<?php echo $category['image1']; ?>" width="236" height="178" alt="[image1]"><?php } ?></div>
<?php echo $category['main_text']; ?>
</div><!-- /JenreDetail -->

</div><!-- /Block -->






<div class="Block">
<div class="H-Line Blue Short">
<h2><span class="icon-">&#xe62b;</span> <?php echo $category['name']; ?>のランキング</h2>
</div><!-- Title Part -->


<div class="JenreRanking"><!------------ JenreRanking -->



<?php foreach($item_all as $v): ?>
<div class="RankBlock <?php echo $v['rank_class']; ?>"><!------- RANK -->

<div class="RankingHead">
<div class="L"><img src="<?php echo $v['rank_icon']; ?>" width="122" height="78" alt=""><p><span><?php echo $v['rank']; ?></span>位</p></div>
<div class="R">
<h2><?php echo $v['name']; ?></h2>
<p><a href="<?php echo $v['myurl']; ?>"><?php echo $v['url']; ?></a></p>
</div>
</div>

<div class="Detail">
<div class="L">
<div class="Eval">
<dl>
<dt>評価：</dt>
<dd>
<ul>
<?php echo $v['star']; ?>
</ul>
</dd>
</dl>
<div class="ScoreNumber"><span><?php echo $v['point']; ?></span>/5.0</div>
</div>
<p><?php echo $v['rank_info']; ?></p>
</div>
<div class="R"><?php if(!empty($v['photo1'])){ ?><img src="<?php echo $v['photo2']; ?>" width="236" height="178" alt=""><?php } ?></div>
</div>

<div class="BtnBlock">
<a href="<?php echo $v['url']; ?>" rel="nofollow" target="_blank"><div class="SiteDetailBtn"><span class="TabNone spNone"><?php echo $v['name']; ?>の</span>詳細 <span class="icon-">&#xe646;</span></div></a>
<a href="reputation.php?item_id=<?php echo $v['item_id']; ?>"><div class="KuchikomiBtn"><span class="TabNone spNone"><?php echo $v['name']; ?>の</span>口コミ <span class="icon-">&#xe646;</span></div></a>
</div>

</div><!------- /RANK -->
<?php endforeach; ?>



</div><!------- /RANK -->
</div><!------------ JenreRanking -->


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
function review_point_filter(point)
{
	for(i=1;i<=5;i++){
		if(i==point){
			$(".review_point_"+i).show();
			continue;
		}
		$(".review_point_"+i).hide();
	}
}
</script>
</body>
</html>