<?php 
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/scripts/Session.class.php';
	
	$item_model = new ItemModel();
	
	//アイテムデータの取得
	$item_id = (int)@$_GET['item_id'];
	$item = $item_model->getItem($item_id);
	if(empty($item)){
		//$company_modelデータが無いのでindex.phpにリダイレクト
		header("location: index.php");
		exit;
	}
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$division = $division_model->getDivision($item['division_id']);
	$category = $category_model->getCategory($item['category_id']);
	
	//感情アイコン
	$feelingImageList = array();
	$feelingImageList[1] = 'common/img/bush/'.ReviewModel::getFaceImage(1);
	$feelingImageList[2] = 'common/img/bush/'.ReviewModel::getFaceImage(2);
	$feelingImageList[3] = 'common/img/bush/'.ReviewModel::getFaceImage(3);
	$feelingImageList[4] = 'common/img/bush/'.ReviewModel::getFaceImage(4);
	$feelingImageList[5] = 'common/img/bush/'.ReviewModel::getFaceImage(5);
	
	
	$bush = Session::getInstance()->get('bush');
	if(empty($bush)){
		header("location: bush.php?item_id={$item_id}");
		exit;
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//エラーがなかったのでクチコミ書き込み
		$review_data = $bush['review_data'];
		
		$review_model = new ReviewModel();
		$review_model->setReview($review_data);
		
		//画像ファイル名を仮review_idから本review_idでリネーム
		$review_id = $review_model->lastInsertId();
		$update_data = $bush['update_data'];
		for($i=1;$i<=3;++$i)
		{
			$temp_upload_file = "{$bush['temp_review_id']}_".$i;
			$upload_file = "{$review_id}_".$i;
			
			if(isset($update_data['photo'.$i])){
				$oldname = $update_data['photo'.$i];
				$newname = str_replace($temp_upload_file, $upload_file, $update_data['photo'.$i]);
				$update_data['photo'.$i] = $newname;
				@rename(dirname(__FILE__) . '/' . $oldname, dirname(__FILE__) . '/' . $newname);
			}
		}
		if(!empty($update_data)){
			$review_model->updateReview($review_id, $update_data);
		}
		
		Session::getInstance()->destroy();
		
		header("location: bush-thnks.php?item_id={$item_id}"); //書き込み完了画面へリダイレクト
		exit;
	}
	
	$confirm_data = array();
	$confirm_data['name']   = htmlspecialchars($bush['review_data']['name'], ENT_QUOTES, 'UTF-8');
	$confirm_data['gender'] = $bush['review_data']['gender']==1 ? '男性' : '女性';
	
	switch($bush['review_data']['age'])
	{
		case 1: $confirm_data['age']='10代前半'; break;
		case 2: $confirm_data['age']='10代後半'; break;
		case 3: $confirm_data['age']='20代前半'; break;
		case 4: $confirm_data['age']='20代後半'; break;
		case 5: $confirm_data['age']='30代前半'; break;
		case 6: $confirm_data['age']='30代後半'; break;
		case 7: $confirm_data['age']='40代前半'; break;
		case 8: $confirm_data['age']='40代後半'; break;
		case 9: $confirm_data['age']='50代前半'; break;
		case 10: $confirm_data['age']='50代後半'; break;
		case 11: $confirm_data['age']='60代前半'; break;
		case 12: $confirm_data['age']='60代後半'; break;
		case 13: $confirm_data['age']='70代～'; break;
	}
	
	switch($bush['review_data']['point'])
	{
		case 5: $confirm_data['point']='最高'; break;
		case 4: $confirm_data['point']='なかなか良い'; break;
		case 3: $confirm_data['point']='普通'; break;
		case 2: $confirm_data['point']='やや悪い'; break;
		case 1: $confirm_data['point']='悪い'; break;
	}
	
	$confirm_data['image'] = 'common/img/bush/'.ReviewModel::getFaceImage($bush['review_data']['image']);
	
	$confirm_data['title']   = htmlspecialchars($bush['review_data']['title'], ENT_QUOTES, 'UTF-8');
	$confirm_data['good']   = htmlspecialchars($bush['review_data']['good'], ENT_QUOTES, 'UTF-8');
	$confirm_data['bad']   = htmlspecialchars($bush['review_data']['bad'], ENT_QUOTES, 'UTF-8');
	$confirm_data['text']   = nl2br(htmlspecialchars($bush['review_data']['text'], ENT_QUOTES, 'UTF-8'));
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/bush.php?item_id={$item['item_id']}");
	$url = "http://{$server_name}/bush.php?item_id={$item['item_id']}";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,user-scalable=no" />
<meta name="robots" content="noindex" />


<title><?php echo $item['name']; ?>への口コミ内容確認 - 婚活サイト</title>
<meta name="description" content="<?php echo $item['name']; ?>の口コミ投稿ページ。これまで体験した<?php echo $item['name']; ?>の情報をお書きください。">

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
<?php @include 'part-special.php'; ?></div>
</article>
<!-- /#header --></header>

<div id="BreadScrumb">
<div class="Cnt">
<article>
<h2 class="TopicPath">
<div xmlns:v="http://rdf.data-vocabulary.org/#">
<span typeof="v:Breadcrumb"><a href="/" rel="v:url" property="v:title"><span class="icon-">&#xe609;</span> ランクルーホーム</a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $division['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $category['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $item['name']; ?></a> &gt; </span>
<span typeof="v:Breadcrumb"><a href="<?php echo $url; ?>" rel="v:url" property="v:title"><?php echo $item['title']; ?></a></span>
</div>
</h2>
</article>
</div>
<!-- /#BreadScrumb --></div>

<?php @include 'part-search.php'; ?>

<div id="contents">

<div id ="Main"><!-- MAIN -->


<div class="Block"><!-- BUSH -->
<div class="H-Line Short Gray">
<h2><span class="icon-">&#xe60c; </span><?php echo $item['name']; ?>に関する口コミ</h2>
</div><!-- Title Part -->

<div class="Bush">
<p class="Note">※ 以下の内容で問題ないかご確認ください。</p>

<!-- Form -->
<form id="contact-form" action="bush-conf.php?item_id=<?php echo $item['item_id']; ?>" method="post" enctype="multipart/form-data">


<div class="sect">
<h3>■ 投稿者名</h3>
<span>投稿者名（ニックネーム）: <?php echo $confirm_data['name']; ?></span>

</div>

<div class="sect">
<span>性別: <?php echo $confirm_data['gender']; ?></span>

</div>

<div class="sect">
<span>年代: <?php echo $confirm_data['age']; ?></span>

</div>


<div class="sect">
<h3>■ 総合評価</h3>
<?php echo $confirm_data['point']; ?>
</div>

<div class="sect">
<h3>■ 口コミの感情</h3>
<img src="<?php echo $confirm_data['image']; ?>" />
</div>

<div class="sect">
<h3>■ タイトル</h3>
<?php echo $confirm_data['title']; ?>

<h3>■ 良い点</h3>
<?php echo $confirm_data['good']; ?>

<h3>■ 悪い点</h3>
<?php echo $confirm_data['bad']; ?>

<h3>■ 画像１</h3>
<p id="thum1"><?php if(!empty($bush['update_data']['photo1'])){ ?><img src="<?php echo $bush['update_data']['photo1']; ?>" /><?php } ?></p>

<h3>■ 画像２</h3>
<p id="thum2"><?php if(!empty($bush['update_data']['photo2'])){ ?><img src="<?php echo $bush['update_data']['photo2']; ?>" /><?php } ?></p>

<h3>■ 画像３</h3>
<p id="thum3"><?php if(!empty($bush['update_data']['photo3'])){ ?><img src="<?php echo $bush['update_data']['photo3']; ?>" /><?php } ?></p>


<h3>■ 口コミ文章</h3>
<?php echo $confirm_data['text']; ?>

</div>

<div class="BlinkBtn">
<button type="submit" id="contact-submit"><span class="icon-">&#xe618; </span>投稿する</button>
</div>
</form>
<!-- /Form -->


<p class="NoticeTitle"><img src="common/img/bush/notice.png" width="460" height="34" alt=""></p>
<p class="Note">
※ 口コミの内容によっては、掲載不可または会社名を表示しない場合がございますのえ、ご了承ください。<br>
※ <a href="/manner.php" onClick="window.open(this.href, 'window', 'width=990, height=600,personalbar=0,toolbar=0,scrollbars=1,resizable=1'); return false;">書き込みマナーについてはこちらをご参照ください。</a></p>

</div>
</div><!-- BUSH -->




</div><!-- MAIN -->
<?php @include 'side.php'; ?>
<!-- /#contents --></div>
<?php @include 'footer.php'; ?>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function onTextCount()
{
	var text = $("#text").val();
	$("#NumCount").html(text.length);
}
</script>
<script>
$(function () {
//IE Label img
        //if ($.browser.msie) {
                $('label').click(function () {
                        $('#' + $(this).attr('for')).focus().click();
                });
        //}
});


initThumbnail(1);
initThumbnail(2);
initThumbnail(3);
function initThumbnail(id,w,h)
{
	var obj1 = document.getElementById('photo'+id);
	obj1.addEventListener("change", function(evt){
		var file = evt.target.files;
		var fname = file[0].name;
		var ext = fname.match(/(.+)(\.[^.]+$)/);
		
		if(ext != null && (ext[2].toLowerCase() == '.jpg' || ext[2].toLowerCase() == '.png')){
			$('#thum'+id).html('');
		}else{
			$('#photo'+id).val('');
			$('#thum'+id).html('<span class="fc-red">jpg, pngファイルを指定してください</span>');
		}
	},false);
}
</script>
</body>
</html>
