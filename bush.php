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
	
	
	//入力データ
	$input_data = array();
	//入力エラー
	$errors = array();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//投稿ボタンが押された
		$input_data['name'] = trim(@$_POST['name']);
		$input_data['age'] = @$_POST['age'];
		$input_data['gender'] = (int)@$_POST['gender'];
		$input_data['point'] = @$_POST['point'];
		$input_data['image-feeling'] = (int)@$_POST['image-feeling'];
		$input_data['good'] = trim(@$_POST['good']);
		$input_data['bad'] = trim(@$_POST['bad']);
		$input_data['title'] = trim(@$_POST['title']);
		$input_data['text'] = trim(@$_POST['text']);
		
		//改行コードを\nだけにする
		$input_data['text'] = str_replace("\r", "", $input_data['text']);
		
		/*** エラーチェック ***/
		if(empty($input_data['name'])){
			$errors['name'] = true;
		}
		if(empty($input_data['point'])){
			//未入力
			$errors['point'] = true;
		}
		if(empty($input_data['image-feeling']) || !isset($feelingImageList[ $input_data['image-feeling'] ])){
			//未選択
			$errors['image-feeling'] = true;
		}
		if(empty($input_data['title'])){
			//未入力
			$errors['title_0'] = true;
		}
		if(mb_strlen($input_data['title'], 'UTF-8') > 30){
			//タイトルが30字を超えてた
			$errors['title_1'] = true;
		}
		if(empty($input_data['text'])){
			//未入力
			$errors['text_0'] = true;
		}
		if(mb_strlen($input_data['text'], 'UTF-8') < 350){
			//口コミ文章が350字より少なかった
			$errors['text_1'] = true;
		}
		
		if(empty($errors)){
			//エラーがなかったのでクチコミ書き込み
			$review_data = array(
				'item_id'=> $item['item_id'],
				'name'=> $input_data['name'],
				'gender'=> $input_data['gender'],
				'age'=> $input_data['age'],
				'image'=> $input_data['image-feeling'],
				'point'=> $input_data['point'],
				'title'=> $input_data['title'],
				'good'=> $input_data['good'],
				'bad'=> $input_data['bad'],
				'text'=> $input_data['text'],
				'enable'=> 0,
			);
			
			//画像のアップロード
			$update_data = array();
			
			//画像ファイル名の仮review_id
			//bush-conf.phpでOKなら本review_idでリネームする
			$temp_review_id = 'temp_' . microtime(true);
			
			for($i=1;$i<=3;++$i)
			{
				$upload_file = "{$temp_review_id}_".$i;
				if(($fname = UploadLib::getInstance()->_upload('photo'.$i, 'review', $upload_file, true)) !== false){
					//成功ならphotoを更新する
					$update_data['photo'.$i] = 'contents/review/' . $fname;
				}
			}
			
			Session::getInstance()->set('bush', array(
				'temp_review_id' => $temp_review_id,
				'review_data'    => $review_data,
				'update_data'    => $update_data,
			));
			
			header("location: bush-conf.php?item_id={$item_id}"); //書き込み確認画面へリダイレクト
			exit;
		}
	}
	
	Session::getInstance()->destroy();
	
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


<title><?php echo $item['name']; ?>の口コミをする - 婚活サイト</title>
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

<article>
<div class="H-Line Short Gray">
<h2><span class="icon-">&#xe604; </span><?php echo $item['name']; ?>に関する口コミ</h2>
</div><!-- Title Part -->

<div class="Bush">

<p class="Note">※ Rankrooはみなさまからの口コミ情報を元に運営しております。ご協力よろしくお願いいたします。</p>

<!-- Form -->
<form id="contact-form" action="bush.php?item_id=<?php echo $item['item_id']; ?>" method="post" enctype="multipart/form-data">
<?php if(!empty($errors['text_1'])){ ?><div class="BushAlert"><span class="icon-">&#xe60a; </span>口コミ文章が350字未満です。350字以上のクチコミをお願いします。</div><?php } ?>


<div class="sect">
<h3>■ 投稿者名を入力してください。</h3>
<?php if(!empty($errors['name'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>未入力です。</p><?php } ?>
<label>
<span>投稿者名（ニックネーム）:</span>
<input placeholder="投稿者名（ニックネーム）" type="text" name="name" id="name" value="<?php echo @htmlspecialchars($input_data['name'],ENT_QUOTES,'UTF-8'); ?>" tabindex="1" required>
</label>
</div>

<div class="sect">
<span>性別:</span>
<?php if(!empty($errors['gender'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>未選択です</p><?php } ?>
<span class="Break"><input type="radio" name="gender" id="select1" value="1" checked <?php if(@$input_data['gender'] === '1'){ ?>checked<?php }?> >
<label for="select1">男性</label>　</span>
<span class="Break"><input type="radio" name="gender" id="select2" value="2" <?php if(@$input_data['gender'] === '2'){ ?>checked<?php }?> >
<label for="select2">女性</label>　</span>
</div>


<div class="sect">
<label>
年代: 
<select name="age"id="age">
<option value="1" <?php if(@$input_data['age']==1)echo 'selected="selected"'; ?>>10代前半</option>
<option value="2" <?php if(@$input_data['age']==2)echo 'selected="selected"'; ?>>10代後半</option>
<option value="3" <?php if(@$input_data['age']==3)echo 'selected="selected"'; ?>>20代前半</option>
<option value="4" <?php if(@$input_data['age']==4)echo 'selected="selected"'; ?>>20代後半</option>
<option value="5" <?php if(@$input_data['age']==5)echo 'selected="selected"'; ?>>30代前半</option>
<option value="6" <?php if(@$input_data['age']==6)echo 'selected="selected"'; ?>>30代後半</option>
<option value="7" <?php if(@$input_data['age']==7)echo 'selected="selected"'; ?>>40代前半</option>
<option value="8" <?php if(@$input_data['age']==8)echo 'selected="selected"'; ?>>40代後半</option>
<option value="9" <?php if(@$input_data['age']==9)echo 'selected="selected"'; ?>>50代前半</option>
<option value="10" <?php if(@$input_data['age']==10)echo 'selected="selected"'; ?>>50代後半</option>
<option value="11" <?php if(@$input_data['age']==11)echo 'selected="selected"'; ?>>60代前半</option>
<option value="12" <?php if(@$input_data['age']==12)echo 'selected="selected"'; ?>>60代後半</option>
<option value="13" <?php if(@$input_data['age']==13)echo 'selected="selected"'; ?>>70代～</option>
</select>
</label>
</div>


<div class="sect">
<h3>■ 総合評価はいかがですか？</h3>
<?php if(!empty($errors['point'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>未選択です</p><?php } ?>
<span class="Break"><input type="radio" name="point" id="select1" value="5" <?php if(@$input_data['point'] === '5'){ ?>checked<?php }?> >
<label for="select1">5 (最高)</label>　</span>
<span class="Break"><input type="radio" name="point" id="select2" value="4" <?php if(@$input_data['point'] === '4'){ ?>checked<?php }?> >
<label for="select2">4 (なかなか良い)</label>　</span>
<span class="Break"><input type="radio" name="point" id="select3" value="3" <?php if(@$input_data['point'] === '3'){ ?>checked<?php }?> >
<label for="select3">3 (普通)</label>　</span>
<span class="Break"><input type="radio" name="point" id="select4" value="2" <?php if(@$input_data['point'] === '2'){ ?>checked<?php }?> >
<label for="select4">2 (やや悪い)</label>　</span>
<span class="Break"><input type="radio" name="point" id="select5" value="1" <?php if(@$input_data['point'] === '1'){ ?>checked<?php }?> >
<label for="select5">1 (悪い)</label>　</span>
</div>

<div class="sect">
<h3>■ 口コミの感情は下記のどれにあたりますか？</h3>
<?php if(!empty($errors['image-feeling'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>未選択です</p><?php } ?>
<ul class="Feeling">
<?php for($i=5;$i>=1;--$i){ ?>
<li style="text-align:center;">
<label for="image-feeling_<?php echo $i; ?>" ><img src="<?php echo $feelingImageList[$i]; ?>" width="122" height="122" alt="" id="f<?php echo $i; ?>" class="img-feeling"></label>
<input type="radio" value="<?php echo $i; ?>" name="image-feeling" id="image-feeling_<?php echo $i; ?>" <?php if(@$input_data['image-feeling']==$i)echo 'checked="checked"'; ?> />
</li>
<?php } ?>
</ul>
</div>

<div class="sect">
<h3>■ タイトル（30字以内でお書きください。）</h3>
<?php if(!empty($errors['title_0'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>未入力です</p><?php } ?>
<?php if(!empty($errors['title_1'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>30字を超えています</p><?php } ?>
<input placeholder="タイトル" type="text" name="title" id="title" value="<?php echo @htmlspecialchars($input_data['title'],ENT_QUOTES,'UTF-8'); ?>" tabindex="1" required>
</div>

<div class="sect">
<h3>■ 良い点</h3>
<input placeholder="良い点" type="text" name="good" id="good" value="<?php echo @htmlspecialchars($input_data['good'],ENT_QUOTES,'UTF-8'); ?>" tabindex="1" >
</div>

<div class="sect">
<h3>■ 悪い点</h3>
<input placeholder="悪い点" type="text" name="bad" id="bad" value="<?php echo @htmlspecialchars($input_data['bad'],ENT_QUOTES,'UTF-8'); ?>" tabindex="1" >
</div>

<div class="sect">
<h3>■ 画像1 ※jpg, gifのみ</h3>
<input type="file" name="photo1" id="photo1" accept="image/jpeg,image/png">
<p id="thum1"></p>
</div>

<div class="sect">
<h3>■ 画像2 ※jpg, gifのみ</h3>
<input type="file" name="photo2" id="photo2" accept="image/jpeg,image/png">
<p id="thum2"></p>
</div>

<div class="sect">
<h3>■ 画像3 ※jpg, gifのみ</h3>
<input type="file" name="photo3" id="photo3" accept="image/jpeg,image/png">
<p id="thum3"></p>
</div>

<div class="sect">
<h3>■ 口コミ文章</h3>
<?php if(!empty($errors['text_0'])){ ?><p class="fc-red"><span class="icon-">&#xe60a; </span>未入力です</p><?php } ?>
<span>※質の高い体験談を読んでもらうために、<font color="red"><b>350文字以上</b></font>にて投稿をお願いします。</span>
<?php if(!empty($errors['text_1'])){ ?><p class="fc-red Bold"><span class="icon-">&#xe60a; </span>口コミ文章が350字未満です。350字以上のクチコミをお願いします。</p><?php } ?>
<label>
<textarea placeholder="口コミ文章" name="text" id="text" tabindex="5" onKeyUp="onTextCount();" required pattern=".{10,}" title="このフィールドは350文字以上です"><?php echo @htmlspecialchars($input_data['text'],ENT_QUOTES,'UTF-8'); ?></textarea>
</label>
<p class="NumCount">現在の文字数：<span id="NumCount"><?php echo @mb_strlen($input_data['text'], 'UTF-8'); ?></span></p>
</div>

<button type="submit" id="contact-submit">内容確認</button>

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
