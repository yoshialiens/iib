<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/RankingModel.class.php';

	$category_id = (int)@$_GET['category_id'];
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	$review_model = new ReviewModel();
	$experience_model = new ExperienceModel();
	$special_model = new SpecialModel();
	$ranking_model = new RankingModel();

	//division_id一覧
	$division_list = array(1);//ブログ
	$division_all = array();
	foreach($division_list as $id){
		$division_all[$id] = $division_model->getDivision($id);
	}
	foreach($division_all as $k => $v){
		$category_all = $category_model->getCategoryAllByDivisionId($v['division_id']);
		$division_all[$k]['category_all'] = array_slice($category_all, 0, 7);
	}

	$article_list = array(0,1,2,3,4,5,6,7,8,9,10);//カテゴリ
	$article_all = array();
	foreach($article_list as $category_id){
		$article_all[$category_id] = $category_model->getCategory($category_id);
	}
	$item_list = array();
	foreach($article_all as $k2 => $v2){
		$item_all[] = $item_model->getItemAllByCategoryId($k2);
	}

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/privacy.php");
	$url = "http://{$server_name}/privacy.php";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="サイトマップ|株式会社いないいないばぁ">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/sitemap.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/page/sitemap.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="サイトマップ,サプライズマーケティング">
<meta name="description" content="日本一のサプライズマーケティング会社である株式会社いないいないばぁのサイトマップページです。口コミやリピートを増やすためにサプライズを提案し、人々に喜んでもらうために動いてもらいます。">
<title>サイトマップ|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/sitemap.php">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="sitemap">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="150" height="40"></a></div>
<div class="h-sec">
<h1>サイトマップ - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=日本一のサプライズマーケティング会社-株式会社いないいいないばぁ &amp;url=http://www.i-i-b.jp/" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?http://www.i-i-b.jp/" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
</ul><!-- /h-sns -->
</div><!-- /PC MENU -->
<div class="spView"><!-- SP MENU -->
<header id="header-sp" class="spView">
<p class="Logo"><a href="/"><img src="common/img/bnr/logo.png" width="155" height="38" alt="株式会社いないいないばぁ"/></a></p>
<?php @include 'header-nav-sp.php'; ?>
</header>
</div><!-- /SP MENU -->
</header>



<div class="contents">
<div id="main">
<!-- パンくず -->
<div class="breadcrumb">
<ul>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">HOME</span></a></li>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/sitemap.php" itemprop="url"><span itemprop="title">サイトマップ</span></a></li>
</ul>
</div><!-- /パンくず -->


<div class="Block Top">
<div class="Cont">
<h2>サイトマップ</h2>
<p class="catch">SITEMAP</p>
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block">
<div class="Cont">
<div class="siteList">
<h3>サイトマップ</h3>
<ul>
<li><a href="index.php">HOME</a></li>
<li><a href="company.php">会社概要</a></li>
<li><a href="activities.php">サプライズマーケティングとは？</a></li>
<li><a href="mission.php">理念</a></li>
<li><a href="campaign201501.php">サプライズマーケティング全国制覇</a></li>
<!--li><a href="campaign/">全国制覇応募フォーム</a></li-->
<li><a href="contact/">お問い合わせ</a></li>
<li><a href="privacy.php">プライバシーポリシー</a></li>
<li><a href="sitemap.php">サイトマップ</a></li>
<li><a href="blog.php">活動報告</a></li>
<?php foreach($division_all[1]['category_all'] as $v): ?>
<li><a href="category.php?category_id=<?php echo $v['category_id']; ?>"><?php echo $v['name']; ?></a></li>
<?php foreach($item_all[$v['category_id']] as $v): ?>
<li class="subList"><a href="article.php?item_id=<?php echo $v['item_id']; ?>"><?php echo $v['name']; ?></a></li>
<?php endforeach; ?>
<?php endforeach; ?>
<ul>


</div>
</div><!-- /Cont -->
</div><!-- Block -->


<?php @include 'more.php'; ?>
<?php @include 'campaign.php'; ?>
</div><!-- /main -->
</div><!-- /contents -->
<?php @include 'footer.php'; ?>
</div><!-- /#wrapper -->

<!-- JS Section -->
<?php @include 'js.php'; ?>
<!-- /JS Section -->
</body>
</html>
