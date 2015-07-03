<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/AuthorModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';

	$category_id = (int)@$_GET['category_id'];
	//$author_id = (int)@$_GET['author_id'];
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$author_model = new AuthorModel();
	$item_model = new ItemModel();
	//division_id一覧
	$division_list = array(1); //ブログ
	$division_all = array();
	foreach($division_list as $id){
		$division_all[$id] = $division_model->getDivision($id);
	}
	foreach($division_all as $k => $v){
		$category_all = $category_model->getCategoryAllByDivisionId($v['division_id']);
		$division_all[$k]['category_all'] = array_slice($category_all, 0, 7);
	}
	$PAGE_ITEM_COUNT = 9;//1ページに表示する数
	//$filter = $session->get('filter');
	$category_id2 = (int)@$filter['category_id'];
	//ページ番号
	$page_num = @$_GET['p'];
	$item_count = $item_model->getItemCountByFilter($category_id);
	//最大ページ数
	$page_max = ceil($item_count / $PAGE_ITEM_COUNT);
	$item_all2 = $item_model->getItemAllByFilter($category_id, $page_num, $PAGE_ITEM_COUNT);
	foreach($item_all2 as &$v2)
	{
		$v2['division_name'] = htmlspecialchars($v2['division_name'], ENT_QUOTES, 'UTF-8');
		$v2['category_name'] = htmlspecialchars($v2['category_name'], ENT_QUOTES, 'UTF-8');
		$v2['name'] = htmlspecialchars($v2['name'], ENT_QUOTES, 'UTF-8');
		unset($v2);
	}


	$category = $category_model->getCategory($category_id);
	/*
	if(empty($category)){
		//データが無い場合はリダイレクト
		header("location: index.php");
		exit;
	}
	*/
	$division = $division_model->getDivision($category['division_id']);
	$item_all = $item_model->getItemAllByCategoryId($category['category_id']);


	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/category.php?category_id={$category['category_id']}");
	$url = "http://{$server_name}/category.php?category_id={$category['category_id']}";
?>
<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="<?php echo $category['name']; ?>|株式会社いないいないばぁ">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/category.php?category_id=<?php echo $category['category_id']; ?>">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/common/fb.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="<?php echo $category['name']; ?>,サプライズマーケティング,いないいないばぁ">
<meta name="description" content="<?php echo $category['description']; ?>">
<title><?php echo $category['name']; ?>|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/category.php?category_id=<?php echo $category['category_id']; ?>">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="category">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://www.i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="147" height="50"></a></div>
<div class="h-sec">
<h1><?php echo $category['name']; ?> - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=<?php echo $category['name']; ?>-株式会社いないいいないばぁ &amp;url=<?php echo $social_url; ?>" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?<?php echo $social_url; ?>" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
</ul><!-- /h-sns -->
</div><!-- /PC MENU -->
<div class="spView"><!-- SP MENU -->
<header id="header-sp" class="spView">
<p class="Logo"><a href="/"><img src="common/img/bnr/logo.png" width="147" height="50" alt="株式会社いないいないばぁ"/></a></p>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/category.php?category_id=<?php echo $category['category_id']; ?>" itemprop="url"><span itemprop="title"><?php echo $category['name']; ?></span></a></li>
</ul>
</div><!-- /パンくず -->

<div class="Block Top btm-lll">
<div class="Cont">
<h2><?php echo $category['name']; ?></h2>
<p class="catch"><?php echo $category['h1']; ?></p>
</div><!-- /Cont -->
</div><!-- Block -->

<div class="Block">
<div class="Cont">
<div class="catList">
<ul>
<li><a href="blog.php">全体一覧</a></li>
<li><a href="category.php?category_id=1">プレスリリース</a></li>
<li><a href="category.php?category_id=2">マーケティング</a></li>
<li><a href="category.php?category_id=3">ニュース</a></li>
<li><a href="category.php?category_id=4">知っておきたいこと</a></li>
<li><a href="category.php?category_id=5">サプライズ</a></li>
</ul>
</div>
<ul class="blog">
<?php foreach($item_all2 as $v): ?>
<li>
<div class="blogImg shake"><div class="blogTag pcView bg<?php echo $category['category_id']; ?>"><p><?php echo $category['name']; ?></p></div><a href="article.php?item_id=<?php echo $v['item_id']; ?>"><?php if(!empty($v['photo1'])){ ?><img src="<?php echo $v['photo1']; ?>" width="300" height="300" alt="<?php echo $v['name']; ?>"><?php } ?></a></div>

<div class="blogDate"><?php echo $v['posted_date']; ?></div>
<div class="blogTitle"><a href="article.php?item_id=<?php echo $v['item_id']; ?>"><?php echo $v['name']; ?></a></div>
<div class="blogEx"><p><?php echo $v['rank_info']; ?></p></div>
<div class="moreBtn pcView"><a href="article.php?item_id=<?php echo $v['item_id']; ?>">MORE ＞</a></div>

</li>

<?php endforeach; ?>


</ul>
<div class="paginate">
<ul>
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="category.php?category_id=<?php echo $category_id ;?>&p=<?php echo $p; ?>" class="active"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="category.php?category_id=<?php echo $category_id ;?>&p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>
</div>

</div><!-- /Cont -->
</div><!-- Block -->

<?php @include 'more.php'; ?>
<?php @include 'campaignSec.php'; ?>
</div><!-- /main -->
</div><!-- /contents -->
<?php @include 'footer.php'; ?>
</div><!-- /#wrapper -->

<!-- JS Section -->
<?php @include 'js.php'; ?>
<!-- /JS Section -->
</body>
</html>
