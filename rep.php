<?php 
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CommentModel.class.php';
	
	$review_id = (int)@$_GET['review_id'];
	$review_model = new ReviewModel();
	$review_data = $review_model->getReview($review_id);
	if(empty($review_data)){
		header("location: index.php");
		exit;
	}
	//掲載不可ならindex.phpへリダイレクト
	if($review_data['enable'] != 1){
		header("location: index.php");
		exit;
	}
	
	//side.phpでitem_idを参照
	$item_id = $review_data['item_id'];
	
	
	$comment_model = new CommentModel();
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//POSTリクエストでコメントの書き込み
		$comment_data = array(
			'review_id' => $review_id,
			'name' => $_POST['name'],
			'comment' => $_POST['comment'],
		);
		
		
		//荒らし対策
		mb_regex_encoding('UTF-8');
		$error = false;
		if(mb_ereg_match(".*href",$comment_data['comment']))$error = true;
		if(!mb_ereg_match(".*[あ-ん]",$comment_data['comment']))$error = true;
		
		if(!$error){
			$comment_model->setComment($comment_data);
			$review_model->updateReview($review_id, array('update_time'=>BaseModel::NOW));
		}
		
		//元のクチコミ画面にリダイレクト
		header("location: rep.php?review_id={$review_id}");
		exit;
	}
	$comment_all = $comment_model->select(array('review_id'=>$review_id, 'enable'=>1), array('comment_id'=>BaseModel::ORDER_DESC));
	foreach($comment_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		$v['comment'] = htmlspecialchars($v['comment'], ENT_QUOTES, 'UTF-8');
		$v['date'] = date("Y/m/d", strtotime($v['update_time']));
		unset($v);
	}
	
	//会社データの取得
	$item_model = new ItemModel();
	$item = $item_model->getItem($review_data['item_id']);
	if(empty($item)){
		header("location: index.php");
		exit;
	}
	
	
	//順位の取得
	$item['rank'] = $item_model->getRank($review_data['item_id']);
		
	//レビュー数
	$review_count = $review_model->getReviewCount($review_data['item_id']);
	
	//オススメ度
	$review_data['star'] = ItemModel::getOsusumeStarTag($review_data['point']);
	
	//表情アイコン
	$review_data['face_image'] = ReviewModel::getFaceImage($review_data['image']);
	
	//その他のレビュー
	$other_review_all = $review_model->getReviewAllByItemId($item['item_id']);
	foreach($other_review_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		$v['title'] = htmlspecialchars($v['title'], ENT_QUOTES, 'UTF-8');
		$text = mb_strimwidth($v['text'], 0, 210, '･･･', 'UTF-8');
		$v['text'] = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		$v['date'] = date("Y/m/d", strtotime($v['update_time']));
		$v['face_image'] = ReviewModel::getFaceImage($v['image']);
		$v['star'] = ItemModel::getOsusumeStarTag($v['point']);
		unset($v);
	}
	

	
	//HTML表示用
	$review_data['name'] = htmlspecialchars($review_data['name'], ENT_QUOTES, 'UTF-8');
	$review_data['title'] = htmlspecialchars($review_data['title'], ENT_QUOTES, 'UTF-8');
	$review_data['good'] = htmlspecialchars($review_data['good'], ENT_QUOTES, 'UTF-8');
	$review_data['bad'] = htmlspecialchars($review_data['bad'], ENT_QUOTES, 'UTF-8');
	$review_data['text'] = htmlspecialchars($review_data['text'], ENT_QUOTES, 'UTF-8');
	$review_data['date'] = date("Y/m/d", strtotime($review_data['update_time']));
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/rep.php?review_id={$review_data['review_id']}");
	$url = "http://{$server_name}/rep.php?review_id={$review_data['review_id']}";

	//総合満足度★タグの取得
	$osusume_star_tag = ItemModel::getOsusumeStarTag($item['point']);	
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
<link rel="stylesheet" href="common/css/magnific-popup.css" type="text/css" media="all">
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
<span typeof="v:Breadcrumb"><a href="field.php?division_id=<?php echo $item['division_id']; ?>" rel="v:url" property="v:title"><?php echo $item['division_name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="ranking.php?category_id=<?php echo $item['category_id']; ?>" rel="v:url" property="v:title"><?php echo $item['category_name']; ?>のランキング</a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="reputation.php?item_id=<?php echo $item['item_id']; ?>" rel="v:url" property="v:title"><?php echo $item['name']; ?>の口コミ評判</a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $review_data['name']; ?>さんの口コミ</a></span>

</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>


<?php @include 'part-search.php'; ?>


<div id="contents">

<div id ="Main"><!-- MAIN -->

<div class="Block"><!-- Block -->

<div class="H-Line Blue">
<ul>
<li class="Rank"><span><?php if($item['rank']<=10){ ?><?php echo $item['rank']; ?><?php } ?></span>位</li>
<li class="Name"><h2><span itemprop="itemreviewed"><a href="reputation.php?item_id=<?php echo $item['item_id']; ?>"><?php echo $item['name']; ?></a></span></h2></li>
</ul>
</div><!-- Title Part -->


<section>
<div class="RepBlock"><!-- RepBlock -->

<div class="User"><!-- User -->
<div class="icons">
<ul>
<li class="photo"><img src="common/img/photo.png" width="150" height="150" alt=""></li>
<li class="feeling"><img src="common/img/bush/<?php echo $review_data['face_image']; ?>" width="122" height="122" alt="口コミ評判の表情"></li>
</ul>
</div>
<div class="txt">
<p class="name">ユーザー名:</p>
<p class="detail">
<span itemprop="reviewer"><?php echo $review_data['name']; ?></span> さん
<span class="count">（200）</span>
<span class="label">称号名</span>
</p>
</div>
</div><!-- /User -->




<div class="ReviewBlock <?php if($review_data['gender']==1){echo 'Male';}else{echo 'Female';}?>"><!-- ReviewBlock -->

<h2><span itemprop="summary"><?php echo $review_data['title']; ?></span></h2>

<div class="Details">

<div class="lodestar"><!-- LODESTAR -->
<div class="RecommendValue">
<ul>
<li class="txt">評価：</li>
<li class="star"><?php echo $review_data['star']; ?></li>
<li class="score"><span><?php echo $review_data['point']; ?></span>点</li>
</ul>
</div>

<div class="Date">口コミ日：<time itemprop="dtreviewed" datetime="<?php echo $review_data['date']; ?>"><?php echo $review_data['date']; ?></time></div>
</div><!-- LODESTAR -->

<div class="Particular">
<?php if(!empty($review_data['good'])): ?>
<dl><dt><img src="common/img/good.png" width="239" height="72" alt="良い点"></dt>
<dd class="good"><?php echo nl2br($review_data['good']); ?></dd></dl>
<?php endif; ?>
<?php if(!empty($review_data['bad'])): ?>
<dl><dt><img src="common/img/bad.png" width="239" height="72" alt="悪い点"></dt>
<dd class="bad"><?php echo nl2br($review_data['bad']); ?></dd></dl>
<?php endif; ?>
</div>

<div class="Comment">
<span itemprop="description"><?php echo nl2br($review_data['text']); ?></span>
</div>

<div class="Bottom"><!-- Bottom -->
<div class="btn Hv"><a href="#" onclick="return on_btn_thanks(<?php echo $review_data['review_id']; ?>);"><img src="common/img/bush/btn-01.png" width="300" height="72" alt="参考になった"></a></div>
<div class="right"><span class="count" id="thanks_<?php echo $review_data['review_id']; ?>"><?php echo $review_data['btn_thanks']; ?></span><span class="score">点</span></div>

<div class="Photo">
<ul>
<?php if(!empty($review_data['photo1'])){ ?><li><div class="div-1105-single Hv"><a href="img.php?id=<?php echo $review_data['review_id']; ?>&p=1"><img src="img.php?id=<?php echo $review_data['review_id']; ?>&p=1" width="236" height="178" alt="<?php echo @htmlspecialchars($review_data['alt1'],ENT_QUOTES,'UTF-8'); ?>"></a></div></li><?php } ?>
<?php if(!empty($review_data['photo2'])){ ?><li><div class="div-1105-single Hv"><a href="img.php?id=<?php echo $review_data['review_id']; ?>&p=2"><img src="img.php?id=<?php echo $review_data['review_id']; ?>&p=2" width="236" height="178" alt="<?php echo @htmlspecialchars($review_data['alt2'],ENT_QUOTES,'UTF-8'); ?>"></a></div></li><?php } ?>
<?php if(!empty($review_data['photo3'])){ ?><li><div class="div-1105-single Hv"><a href="img.php?id=<?php echo $review_data['review_id']; ?>&p=3"><img src="img.php?id=<?php echo $review_data['review_id']; ?>&p=3" width="236" height="178" alt="<?php echo @htmlspecialchars($review_data['alt3'],ENT_QUOTES,'UTF-8'); ?>"></a></div></li><?php } ?>
</ul>
</div>

</div><!-- /Bottom -->

<a href="<?php echo $item['url']; ?>" class="glow" rel="nofollow" target="_blank"><div class="ProD-Btn"><span class="pcView"><?php echo $item['name']; ?>の</span>詳細を見る <span class="icon-">&#xe646;</span></div></a>
<a href="#AddComment" class="glow"><div class="DownBtn"><span class="pcView">この</span>口コミにコメント<span class="pcView">する <span class="icon-">&#xe643;</span></div></a>

<!-- SOCIAL BUTTONS START -->
<div class="snsb">
<ul>
<li class="hatena">
<a href="http://b.hatena.ne.jp/entry/http://<?php echo $server_name; ?>/rep.php?review_id=<?php echo $review_data['review_id']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo $item['name']; ?>｜脱毛サロンチェキ" data-hatena-bookmark-layout="standard" title="このエントリーをはてなブックマークに追加">
<img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" />
</a>
<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async></script></li>

<li class="btns">
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $social_url; ?>&amp;send=false&amp;layout=button_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=20" scrolling="no" frameborder="0" style="border:none;overflow:hidden;width:100px;height:20px" allowTransparency="true"></iframe>
</li>

<li class="tw">
<a href="https://twitter.com/share" class="twitter-share-button" data-via="脱毛サロンスタッフ語る裏事情" data-lang="ja">ツイート</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>

<li class="google">
<div class="g-plusone" data-size="medium" data-href="<?php echo $social_url; ?>"></div>
<script type="text/javascript">
  window.___gcfg = {lang: 'ja'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</li>
</ul>
</div>
<!-- SOCIAL BUTTONS END -->

</div><!-- /Details-->

</div><!-- /ReviewBlock-->

</div><!-- /RepBlock -->
</div><!-- /Block -->
</section>



<a href="bush.php?item_id=<?php echo $item['item_id']; ?>" class="glow"><div class="ProK-Btn"><span class="pcView"><?php echo $item['name']; ?>の</span>口コミをする <span class="icon-">&#xe646;</span></div></a>




<?php if(!empty($comment_all)){ ?>
<section>
<div class="ComBlock"><!-- BUSH -->
<h2><span class="icon-">&#xe602; </span>寄せられたコメント</h2>

<ul class="Bush">
<?php foreach($comment_all as $v){ ?>
<li>
<div class="Person"><p class="Name"><?php echo $v['name']; ?><br class="TabOnly">さん</p></div>
<div class="Txt">
<p class="Up"><?php echo nl2br($v['comment']); ?></p>
<div class="Date-R"><?php echo $v['date']; ?></div>
</div>
</li>
<?php } ?>
</ul>

</div><!-- BUSH -->
</section>
<?php } ?>






<section>
<div class="ComBlock" id="AddComment"><!-- BUSH -->
<h2><span class="icon-">&#xe602; </span>コメントはこちらから</h2>

<div class="Bush">

<!-- Form -->
<form id="contact-form" action="rep.php?review_id=<?php echo $review_data['review_id']; ?>" method="post">
<h3>■ 投稿者名</h3>
<label>
<input placeholder="投稿者名" name="name" type="text" tabindex="1" required>
</label>
<h3>■ コメント</h3>
<label>
<textarea placeholder="お気軽にコメントしてください。" name="comment" tabindex="5" required></textarea>
</label>

<button name="submit" type="submit">投稿する</button>

</form>
<!-- /Form -->
<p class="NoticeTitle"><img src="common/img/bush/notice.png" width="460" height="34" alt=""></p>
<p class="Note">
※ <a href="/manner.php" onClick="window.open(this.href, 'window', 'width=990, height=600,personalbar=0,toolbar=0,scrollbars=1,resizable=1'); return false;">書き込みマナー</a>に基づいた書き込みを行ってください。<br>
※主観的・感情的な表現だけでなく、できる限り第三者の参考になる根拠を含めた表現でお願いいたします。</p>
</div>
</div><!-- BUSH -->
</section>




<section>
<div class="Block">

<article>
<div class="H-Line Gray Short">
<h2><span class="icon-">&#xe65f; </span><span class="pcView"><?php echo $item['name']; ?>の</span>その他の口コミ評判</h2>
</div><!-- Title Part -->
<ul class="KuchikomiList">
<?php foreach($other_review_all as $v){ ?>
<?php if($v['review_id']==$review_data['review_id'])continue; //このページで表示しているクチコミは除く ?>
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
<div class="photo"><img src="img.php?id=<?php echo $v['review_id']; ?>&w=236&h=236&cw=100&ch=100" width="236" height="236" alt="<?php echo @htmlspecialchars($v['alt1'],ENT_QUOTES,'UTF-8'); ?>"></div>
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
</article>






</div>


























<?php @include 'side.php'; ?>
<!-- /#contents --></div>
<?php @include 'footer.php'; ?>
<!-- /#wrapper --></div>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/common/js/jquery.magnific-popup.js"></script>
<script tye="javascript/text">
$(document).ready(function() {
    $('.div-1105-single').magnificPopup({
        delegate: 'a', // ポップアップを開く子要素
        type: 'image',
        image: {
            titleSrc: 'title'  // キャプションとして表示する属性を指定(titleなど)
        }
    });
});
</script>
<script src="/common/js/script.js"></script>
<script src="/common/js/smoothscroll.js"></script>
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