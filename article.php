<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReccomendModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	$item_id = (int)@$_GET['item_id'];
	
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	$reccomend_model = new ReccomendModel();
	$review_model = new ReviewModel();
	$experience_model = new ExperienceModel();
	$special_model = new SpecialModel();
	

	$item = $item_model->getItem($item_id);
	if(empty($item)){
		//データが無い場合はリダイレクト
		exit;
	}
	//順位の取得
	$item['rank'] = $item_model->getRank($item_id);



	//追加 - 関連記事
	$reccomend_id = $reccomend_model->getReccomend($reccomend_id);//reccomend番号の配列取得
	foreach ($reccomend_id as $k => $v) {
		$reccomend_num = $v;
	}
	/*
	foreach ($recitem as $k2 => $v2) {
		$recitem = $item_model->getItem($v2);
	}
	*/
	$rec1 = $reccomend_num['reccomend_num_1'];
	$rec2 = $reccomend_num['reccomend_num_2'];
	$rec3 = $reccomend_num['reccomend_num_3'];
	$recitem_1 = $item_model->getItem($rec1);
	$recitem_2 = $item_model->getItem($rec2);
	$recitem_3 = $item_model->getItem($rec3);
	
	//レビュー数の取得
	$review_count = $review_model->getReviewCount($item_id);
	
	$division = $division_model->getDivision($item['division_id']);
	$category = $category_model->getCategory($item['category_id']);
	if(empty($category['color'])){
		$category['color']='Blue';
	}
	
	//体験談と特集を取得
	$experience_all = $experience_model->getReviewAllByCategoryId_ItemId($item['category_id'], $item_id);
	$special_all = $special_model->getReviewAllByCategoryId_ItemId($item['category_id'], $item_id);
	//2つをマージ
	$merge_all = array();
	foreach($experience_all as $v){
		$v['update_time'] = strtotime($v['update_time']);
		$v['url'] = 'exp.php?archive_id='.$v['archive_id'];
		$v['imgsrc'] = $v['photo1'];
		$merge_all[] = $v;
	}
	foreach($special_all as $v){
		$v['update_time'] = strtotime($v['update_time']);
		$v['url'] = 'special.php?archive_id='.$v['archive_id'];
		$v['imgsrc'] = $v['photo'];
		$merge_all[]=$v;
	}
	//ソートするキーを抽出
	$osusume_keys = array();
	$create_time_keys = array();
	foreach($merge_all as $v){
		$osusume_keys[] = $v['osusume'];
		$create_time_keys[] = $v['update_time'];
	}
	//ソート
	array_multisort(
		$osusume_keys, SORT_DESC, SORT_NUMERIC,
		$create_time_keys, SORT_DESC, SORT_NUMERIC,
		$merge_all
	);
	
	//トップ3つとMOREに分ける
	$exp_sp_top = array();
	$exp_sp_more = array();
	foreach($merge_all as $k => $v)
	{
		$v['title'] = htmlspecialchars($v['title'], ENT_QUOTES, 'UTF-8');
		if($k < 3){
			$exp_sp_top[] = $v;
		}else{
			$exp_sp_more[] = $v;
		}
	}
	
	
	
	$review_all = $review_model->getReviewAllByItemId($item['item_id']);
	$point_per_array = array(0,0,0,0,0); //各ポイント比率
	$point_count_array = array(0,0,0,0,0); //各ポイントカウント
	foreach($review_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		$v['title'] = htmlspecialchars($v['title'], ENT_QUOTES, 'UTF-8');
		$text = mb_strimwidth($v['text'], 0, 210, '･･･', 'UTF-8');
		$v['text'] = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		$v['date'] = date("Y/m/d", strtotime($v['create_time']));
		$v['star'] = ReviewModel::getOsusumeStarTag($v['point']);
		$v['face_image'] = ReviewModel::getFaceImage($v['image']);
		
		$point_count_array[$v['point']-1] += 1;
		
		unset($v);
	}
	//各ポイントの比率の計算
	if(count($review_all)>0){
		for($i=1;$i<=5;++$i){
			$point_per_array[$i-1] = (int)($point_count_array[$i-1] * 100 / count($review_all));
		}
	}
	
	$osusume_star_tag = ItemModel::getOsusumeStarTag($item['point']);
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/article.php?item_id={$item['item_id']}");
	$url = "http://{$server_name}/article.php?item_id={$item['item_id']}";
?>
<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="<?php echo $item['title']; ?>|株式会社いないいないばぁ">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/article.php?item_id=<?php echo $item['item_id']; ?>">
<meta property="og:image" content="http://www.i-i-b.jp/<?php echo $item['photo1']; ?>">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="いないいないばぁ,サプライズマーケティング">
<meta name="description" content="<?php echo $item['description']; ?>">
<title><?php echo $item['title']; ?>|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/article.php?item_id=<?php echo $item['item_id']; ?>">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="blog">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://www.i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="147" height="50"></a></div>
<div class="h-sec">
<h1><?php echo $item['name']; ?> - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=<?php echo $item['title']; ?>|株式会社いないいいないばぁ &amp;url=<?php echo $social_url; ?>" target="_blank"><span class="icon-">&#xea92;</span></a></li>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/article.php?item_id=<?php echo $item['item_id']; ?>" itemprop="url"><span itemprop="title"><?php echo $item['name']; ?></span></a></li>
</ul>
</div><!-- /パンくず -->

<div class="Block">
<div class="Cont">
<article>
<p class="date"><span class="icon-">&#xe905; </span><?php echo $item['posted_date']; ?></p>
<h1 class="title"><?php echo $item['name']; ?></h1>
<figure class="thumbnail">
<img src="<?php echo $item['photo1']; ?>" alt="<?php echo $item['name']; ?>" width="800">
</figure>
<?php echo $item['about']; ?>
</article>
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block Blue">
<div class="Cont">
<h3>RECOMMEND</h3>
<p class="subTitle">よく読まれている記事です。</p>
<ul class="blog">
<li>
<div class="blogImg shake"><a href="article.php?item_id=<?php echo $recitem_1['item_id']; ?>"><?php if(!empty($recitem_1['photo1'])){ ?><img src="<?php echo $recitem_1['photo1']; ?>" width="300" height="300" alt="<?php echo $recitem_1['name']; ?>"><?php } ?></a></div>
<div class="blogTitle"><a href="article.php?item_id=<?php echo $recitem_1['item_id']; ?>"><?php echo $recitem_1['name']; ?></a></div>
<div class="blogEx"><p><?php echo $recitem_1['rank_info']; ?></p></div>
<div class="moreBtn pcView"><a href="article.php?item_id=<?php echo $recitem_1['item_id']; ?>">MORE ＞</a></div></li>

<li>
<div class="blogImg shake"><a href="article.php?item_id=<?php echo $recitem_2['item_id']; ?>"><?php if(!empty($recitem_2['photo1'])){ ?><img src="<?php echo $recitem_2['photo1']; ?>" width="300" height="300" alt="<?php echo $recitem_2['name']; ?>"><?php } ?></a></div>
<div class="blogTitle"><a href="article.php?item_id=<?php echo $recitem_2['item_id']; ?>"><?php echo $recitem_2['name']; ?></a></div>
<div class="blogEx"><p><?php echo $recitem_2['rank_info']; ?></p></div>
<div class="moreBtn pcView"><a href="article.php?item_id=<?php echo $recitem_2['item_id']; ?>">MORE ＞</a></div></li>

<li>
<div class="blogImg shake"><a href="article.php?item_id=<?php echo $recitem_3['item_id']; ?>"><?php if(!empty($recitem_3['photo1'])){ ?><img src="<?php echo $recitem_3['photo1']; ?>" width="300" height="300" alt="<?php echo $recitem_3['name']; ?>"><?php } ?></a></div>
<div class="blogTitle"><a href="article.php?item_id=<?php echo $recitem_3['item_id']; ?>"><?php echo $recitem_3['name']; ?></a></div>
<div class="blogEx"><p><?php echo $recitem_3['rank_info']; ?></p></div>
<div class="moreBtn pcView"><a href="article.php?item_id=<?php echo $recitem_3['item_id']; ?>">MORE ＞</a></div></li>


</ul>
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
