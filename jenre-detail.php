<!--?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	$item_id = (int)@$_GET['item_id'];
	
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
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
	
	//レビュー数の取得
	$review_count = $review_model->getReviewCount($item_id);
	
	$division = $division_model->getDivision($item['division_id']);
	$category = $category_model->getCategory($item['category_id']);
	
	//体験談と特集を取得
	$experience_all = $experience_model->getReviewAllByItemId($item_id);
	$special_all = $special_model->getReviewAllByItemId($item_id);
	//2つをマージ
	$merge_all = array();
	foreach($experience_all as $v){
		$v['update_time'] = strtotime($v['update_time']);
		$v['url'] = 'exp.php?review_id='.$v['review_id'];
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
	$social_url = urlencode("http://{$server_name}/reputation.php?item_id={$item['item_id']}");
	$url = "http://{$server_name}/reputation.php?item_id={$item['item_id']}";
?-->
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<meta name="robots" content="ALL" />

<title><?php echo $item['name']; ?></title>
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
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title">大カテゴリー名</a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title">ジャンル名</a></span>
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
<h2>ジャンルの詳細</h2>
</div><!-- Title Part -->

<div class="JenreDetail">
<div class="Img"><img src="common/img/sample.png" width="236" height="178" alt=""></div>
<div class="Txt">
<h3><span class="icon-">&#xe68f;</span> ジャンル名</h3>
<h1>[h1]タグ</h1>
<h2>[h2]タグ</h2>
<p>[main_text][main_text][main_text][main_text][main_text][main_text][main_text][main_text][main_text]</p>
</div>
</div>

</div><!-- /Block -->



<div class="Block">
<div class="H-Line Gray Short">
<h2>そのジャンルのランキング表示？</h2>
</div><!-- Title Part -->

あああああああああああああああああああああああああああああああああああああ
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