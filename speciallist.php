<?php 
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	//1ページに表示するコラム数
	$PAGE_SIZE = 12;
	
	$special_model = new SpecialModel();
	
	//ページ番号 0～
	$p = (int)@$_GET['p'];
	
	//体験談一覧の取得
	$special_list = $special_model->getReviewAll($p, $PAGE_SIZE);
	
	//全ページ数
	$page_count = ceil($special_model->getReviewAllCount() / $PAGE_SIZE);
	
	$navi_button_list = array();
	for($i=$p-5;$i<=$p+5;$i++)
	{
		if($i<0)continue;
		if($i>=$page_count)continue;
		
		if($p==$i){
			$navi_button_list[$i] = '';
		}else{
			$navi_button_list[$i] = 'speciallist.php?p='.$i;
		}
	}
	
	//TODO: 仮定義
	$url = "#";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<title>特集一覧｜Rankroo</title>
<meta name="description" content="">
<meta name="robots" content="ALL" />
<meta name="keywords" content="★★★★★★★★★" />
<meta property="og:title" content="★★★★★★★★★★★★★" />
<meta property="og:type" content="article" />
<meta property="og:image" content="http://rankroo.jp/common/img/logo.png" />
<meta property="og:url" content="http://rankroo.jp/speciallist.php" />
<meta property="og:site_name" content="Rankroo" />
<meta property="og:description" content="★★★★★★★★★★★★★" />
<link rel="canonical" href="http://rankroo.jp/speciallist.php" />
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
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$division['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$category['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$item['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo @$item['title']; ?></a></span>
</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>

<?php @include 'part-search.php'; ?>



<div id="contents">


<div id ="Main"><!-- MAIN -->

<div class="ExpList">
<ul>

<?php foreach($special_list as $special){ ?>
<a href="special.php?archive_id=<?php echo $special['archive_id']; ?>">
<li>
<div class="Thumb"><img src="<?php echo $special['photo']; ?>" width="204" height="156" alt="<?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?>のイメージ画像"></div>
<div class="Title"><?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?></div>
</li>
</a>
<?php } ?>

</ul>

</div>

<div class="Pager">
<ul class="paginate pag clearfix">
<?php foreach($navi_button_list as $no => $link){ ?>
<?php if(empty($link)){ ?>
<li class="current"><?php echo $no+1; ?></li>
<?php }else{ ?>
<li><a href="<?php echo $link; ?>"><?php echo $no+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>
</div>



</div><!-- MAIN -->
<?php @include 'side.php'; ?>
<!-- /#contents --></div>
<?php @include 'footer.php'; ?>
</body>
</html>
