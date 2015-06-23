<?php
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
	$social_url = urlencode("http://{$server_name}/reputation.php?item_id={$item['item_id']}");
	$url = "http://{$server_name}/reputation.php?item_id={$item['item_id']}";
?>
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
<span typeof="v:Breadcrumb"><a href="field.php?division_id=<?php echo $division['division_id']; ?>" rel="v:url" property="v:title"><?php echo $division['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="ranking.php?category_id=<?php echo $category['category_id']; ?>" rel="v:url" property="v:title"><?php echo $category['name']; ?>のランキング</a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $item['name']; ?>の口コミ評判</a></span>
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
<div class="H-Line <?php echo $category['color']; ?>">
<ul>
<li class="Rank"><span><?php echo $item['rank']; ?></span>位</li>
<li class="Name"><h2><?php echo $item['name']; ?></h2></li>
<li class="Kuchikomi">口コミ：<span><?php echo $review_count; ?></span>件</li>
</ul>
</div><!-- Title Part -->


<div class="ReptationBlock">
<div class="Photo">
<p class="Logo"><img src="<?php echo $item['photo1']; ?>" alt=""></p>
<p class="Site"><img src="<?php echo $item['photo2']; ?>" width="100%"  alt=""></p>
</div>

<div class="Txt">
<div class="Eval">
<dl>
<dt>評価：</dt>
<dd>
<ul>
<?php echo $osusume_star_tag; ?>
</ul>
</dd>
</dl>
<div class="ScoreNumber"><span><?php echo $item['point']; ?></span>/5.0</div>
</div>

<div class="Eval">
<p><a href="<?php echo $item['url']; ?>" target="_blank" rel="nofollow"><?php echo $item['url']; ?></a></p>
</div>

<?php if(!empty($item['company_name'])): ?>
<div class="Eval">
<p><?php echo $item['company_name']; ?></p>
</div>
<?php endif; ?>

<div class="Point">
<h3><?php echo $item['name']; ?>の詳細</h3>
<div class="txt">
<?php echo $item['about']; ?>
</div>
</div><!-- /Point -->
</div><!-- /Txt -->
</div><!-- /ReptationBlock -->
</article>


<article>
<div class="ReptationDetail">
<?php echo $item['prof']; ?>

<p class="Btn"><a href="#"><?php echo $item['name']; ?>の詳細を見る <span class="icon-">&#xe646;</span></a></p>

</div><!-- /ReptationDetail -->
</article>

</div><!-- /Block -->




<?php if(!empty($exp_sp_top)){ ?>
<div class="Block">

<article>
<div class="H-Line Gray Short">
<h2>特集 &amp; 体験談</h2>
</div><!-- Title Part -->

<ul class="ExpList">
<?php foreach($exp_sp_top as $v){ ?>
<a href="<?php echo $v['url']; ?>"><li><p class="Photo"><img src="<?php echo $v['imgsrc']; ?>" width="236" height="178" alt=""></p><h3><?php echo $v['title']; ?></h3></li></a>
<?php } ?>
</ul>

<?php if(!empty($exp_sp_more)){ ?>
<div class="Menu"><!-- More -->
<p class="OpCl"><img src="common/img/menu-open.png" alt="MENU"></p>
<div class="MB">

<ul class="ExpList">
<?php foreach($exp_sp_more as $v){ ?>
<a href="<?php echo $v['url']; ?>"><li><p class="Photo"><img src="<?php echo $v['imgsrc']; ?>" width="236" height="178" alt=""></p><h3><?php echo $v['title']; ?></h3></li></a>
<?php } ?>
</ul>

</div>
</div><!-- /More -->
<?php } ?>

</article>
</div><!-- /Block -->
<?php } ?>


<div class="Block">

<article>
<div class="H-Line Gray Short">
<h2><span class="icon-">&#xe61f; </span><?php echo $item['name']; ?>の口コミ評判</h2>
</div><!-- Title Part -->

<div class="Graphs">
<div class="L">
<?php for($i=5;$i>=1;--$i){ ?>
<dl><dt><a href="javascript:review_point_filter(<?php echo $i;?>);"><span class="icon-">&#xe666;</span><?php echo $i; ?></a></dt><dd><div class="graph"><a href="javascript:review_point_filter(<?php echo $i;?>);"><span class="bar" style="width:<?php echo $point_per_array[$i-1];?>%;">&nbsp;</span></a></div><div class="count"><a href="javascript:review_point_filter(<?php echo $i;?>);">（ <?php echo $point_count_array[$i-1];?> ）</a></div></dd></dl>
<?php } ?>
</div>


<div class="R">
<p>あなたのご意見やご感想を教えてください。</p>
<a href="bush.php?item_id=<?php echo $item['item_id']; ?>"><p class="BushBtn"><?php echo $item['name']; ?>の<br>口コミをする</p></a>
</div>
</div><!-- Graphs -->

<ul class="KuchikomiList">



<?php foreach($review_all as $v){ ?>
<article class="review_point_<?php echo $v['point']; ?>">
<li class="<?php if($v['gender']==1){echo 'Male';}else{echo 'Female';}?>">
<div class="Upper"><!-- Upper -->
<div class="face">
<p class="Img"><img src="common/img/bush/<?php echo $v['face_image']; ?>" width="122" height="122" alt=""></p>
<p class="name"><?php echo $v['name']; ?> さん</p>
<p class="point">（200）</p>
<p class="label">称号名</p>
</div>
<div class="txt">

<?php if($v['pickup']==1){ ?><p class="PickUp"><span class="icon-">&#xe61c;</span> ピックアップ！口コミ</p><?php } ?>

<h3><a href="rep.php?review_id=<?php echo $v['review_id']; ?>"><?php echo $v['title']; ?></a></h3>

<div class="first">
<ul class="rec">
<li class="tx">オススメ度：</li>
<?php echo $v['star']; ?>
</ul>
<div class="date"><?php echo $v['date']; ?></div>
</div><!-- /first -->

<div class="comment">
<?php if(!empty($v['photo1'])){ ?>
<div class="photo"><img src="img.php?id=<?php echo $v['review_id']; ?>&w=236&h=236&cw=100&ch=100" width="236" height="236" alt="<?php echo @htmlspecialchars($v['alt1'],ENT_QUOTES,'UTF-8'); ?>"><p><span>写真あり</span></p></div>
<?php } ?>
<?php echo $v['text']; ?>
<span class="more"><a href="rep.php?review_id=<?php echo $v['review_id']; ?>">続きを見る <span class="icon-">&#xe646;</span></a></span>
</div><!-- /comment -->

<div class="Bottom">
<div class="btn Hv"><a href="#" onclick="return on_btn_thanks(<?php echo $v['review_id']; ?>);"><img src="common/img/bush/btn-01.png" width="300" height="72" alt="参考になった"></a></div>
<div class="right"><span class="count" id="thanks_<?php echo $v['review_id']; ?>"><?php echo $v['btn_thanks']; ?></span><span class="score">点</span></div>
</div><!-- /Bottom -->

</div><!-- /txt -->
</div><!-- /Upper -->
</li>
</article>
<?php } ?>


</ul>


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
function on_btn_thanks(id)
{
	$.ajax({
		type: 'GET',
		url: 'ajax-thanks.php?review_id='+id,
		cache: false,
		success: function(ret){
			$("#thanks_"+id).html(ret);
		}
	});
	return false;
}
</script>
</body>
</html>