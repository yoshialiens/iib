<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	$division_id = (int)@$_GET['division_id'];
	
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	$review_model = new ReviewModel();
	$experience_model = new ExperienceModel();
	$special_model = new SpecialModel();
	
	
	$division = $division_model->getDivision($division_id);
	if(empty($division)){
		header("location: index.php");
		exit;
	}
	
	$category_all = $category_model->getCategoryAllByDivisionId($division_id);
	
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/field.php?division_id={$division['division_id']}");
	$url = "http://{$server_name}/field.php?division_id={$division['division_id']}";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<meta name="robots" content="ALL" />

<title><?php echo $division['name']; ?></title>
<meta name="description" content="<?php echo $item['description']; ?>">

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
<h1><?php echo $division['name']; ?></h1>
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
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $division['name']; ?></a></span>
</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>

<?php @include 'part-search.php'; ?>


<div id="contents">


<div id ="Main"><!-- MAIN -->

<div class="Block">
<div class="H-Line Red Short">
<h2><?php echo $division['name']; ?></h2>
</div><!-- Title Part -->

<div class="FieldDetail"><!-- FieldDetail -->
<div class="Photo"><?php if(!empty($division['image1'])){ ?><img src="<?php echo $division['image1']; ?>" width="236" height="178" alt="[image1]"><?php } ?></div>
<?php echo $division['info']; ?>
</div><!-- /FieldDetail -->

<div class="JenreList"><!-- JenreList -->


<table>
<tbody>

<?php foreach($category_all as $v ): ?>
<tr>
<th><a href="ranking.php?category_id=<?php echo $v['category_id']; ?>"><span class="icon-">&#xe6b0;</span> <?php echo $v['name']; ?></a></th>
<td>
<div class="photo"><a href="ranking.php?category_id=<?php echo $v['category_id']; ?>"><img src="<?php echo $v['image1']; ?>" width="236" height="178" alt=""></a></div>
<div class="txt"><?php echo $v['main_text']; ?></div>
</td>
</tr>
<?php endforeach; ?>


</tbody>
</table>
</div><!-- /JenreList -->








</div><!-- /Block -->












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