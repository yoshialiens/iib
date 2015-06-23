<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ExperienceModel.class.php';
	
	$session = Session::getInstance();
	$admin = $session->get('admin');
	if($admin === false){
		header("location: login.php");
		exit;
	}
	$server_url = $_SERVER['SERVER_NAME'] . "/";
	if(@$_SERVER['HTTPS']=='on'){
		$server_url = "https://" . $server_url;
	}else{
		$server_url = "http://" . $server_url;
	}
	
	
	$archive_id = (int)@$_GET['id'];
	
	$experience_model = new ExperienceModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'division_id' => @$_POST['division_id'],
			'category_id' => @$_POST['category_id'],
			'item_id'     => @$_POST['item_id'],
			'title'       => @$_POST['title'],
			'osusume'     => @$_POST['osusume'],
			'colume'      => @$_POST['colume'],
			'disp'        => @$_POST['disp'],
			'update_time' => @$_POST['update_time'],
		);
		if($archive_id==0){
			//新規ならレコードを作っておく
			$experience_model->insertReview($data);
			$archive_id = $experience_model->lastInsertId();
		}
		
		$upload_file = "{$archive_id}_image";
		if(($fname = UploadLib::getInstance()->_upload('image', 'experience', $upload_file)) !== false){
			//成功ならphoto1を更新する
			$data['image'] = 'contents/experience/' . $fname;
		}else{
			$data['image'] = @$_POST['old_image'];
		}
		
		$upload_file = "{$archive_id}_photo";
		if(($fname = UploadLib::getInstance()->_upload('photo', 'experience', $upload_file)) !== false){
			//成功ならphoto1を更新する
			$data['photo'] = 'contents/experience/' . $fname;
		}else{
			$data['photo'] = @$_POST['old_photo'];
		}
		
		//画像1のアップロード
		for($i=1;$i<=10;++$i)
		{
			$upload_file = "{$archive_id}_photo".$i;
			if(($fname = UploadLib::getInstance()->_upload('photo'.$i, 'experience', $upload_file)) !== false){
				//成功ならphoto1を更新する
				$data['photo'.$i] = 'contents/experience/' . $fname;
			}else{
				$data['photo'.$i] = @$_POST['old_photo'.$i];
			}
		}
		
		//更新
		$experience_model->updateReview($archive_id, $data);
		header("location: reg-exp.php?id=".$archive_id."#upd");
		exit;
	}
	
	$experience = $experience_model->getReview($archive_id);
	
	$item_model = new ItemModel();
	$item = $item_model->getItem(@$experience['item_id']);
	
	$division_model = new DivisionModel();
	$division_all = $division_model->select();
	foreach($division_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$category_model = new CategoryModel();
	$category_all = $category_model->select(array('division_id'=>$experience['division_id']));
	foreach($category_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$experience['title'] = htmlspecialchars(@$experience['title'], ENT_QUOTES, 'UTF-8');
	$experience['name'] = htmlspecialchars(@$experience['name'], ENT_QUOTES, 'UTF-8');
	$experience['good'] = htmlspecialchars(@$experience['good'], ENT_QUOTES, 'UTF-8');
	$experience['bad'] = htmlspecialchars(@$experience['bad'], ENT_QUOTES, 'UTF-8');
	$experience['text'] = htmlspecialchars(@$experience['text'], ENT_QUOTES, 'UTF-8');
	for($i=1;$i<=3;++$i){
		$experience['alt'.$i] = htmlspecialchars(@$experience['alt'.$i], ENT_QUOTES, 'UTF-8');
	}
	$experience['btn_thanks'] = htmlspecialchars(@$experience['btn_thanks'], ENT_QUOTES, 'UTF-8');
	if(empty($experience['update_time']))$experience['update_time'] = date("Y-m-d H:i:s");
	if(empty($experience['create_time']))$experience['create_time'] = date("Y-m-d H:i:s");
?><!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>特集ページの登録/編集</title>

<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

<body>
<?php require dirname(__FILE__).'/alert.php'; ?>
<div id="wrapper">



<div class="Content">

<div class="PageBtn">
<ul>
<?php require_once 'menu.php'; ?>
</ul>
</div>

<div class="Cnt">

<h2>特集ページの登録/編集</h2>

<div class="MenuBtns">
<ul>
<li><a href="icon/" target="_blank">アイコン一覧はこちら</a></li>
</ul>
</div>

<form method="post" action="reg-exp.php?id=<?php echo @$archive_id; ?>" enctype="multipart/form-data" id="frmReg">

<table class="Reg">
<tr>
<th>大カテゴリー名</th>
<td>
<select name="division_id" onChange="load_category(this.value,0)">
<option value="0">未設定</option>
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['division_id']; ?>" <?php if(@$experience['division_id']==$v['division_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
</td>
</tr>

<tr>
<th>ジャンル名</th>
<td>
<select name="category_id" id="category_id" onChange="load_item(this.value,0)">
<option value="0">未設定</option>
<?php foreach($category_all as $v){ ?>
<option value="<?php echo $v['category_id']; ?>" <?php if(@$experience['category_id']==$v['category_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<th>アイテム名（商品/企業）名</th>
<td>
<select name="item_id" id="item_id">
</select>
</td>
</tr>
</table>
<script>
$(document).ready(function(){
	load_item(<?php echo (int)@$experience['category_id']; ?>, <?php echo (int)@$experience['item_id']; ?>);
});
</script>

<table class="Reg">

<tr>
<th style="width:150px">archive_id</th>
<td><?php echo @$experience['archive_id']==0?'新規':$experience['archive_id']; ?></td>
</tr>

<tr>
<th>おすすめ</th>
<td>
<select name="osusume">
<option value="0" <?php if(@$experience['osusume']==0){echo 'selected="selected"';} ?>>0：無効</option>
<option value="1" <?php if(@$experience['osusume']==1){echo 'selected="selected"';} ?>>1：有効</option>
</select>
</td>
</tr>

<tr>
<th>title</th>
<td><input type="text" name="title" style="width:800px;" value="<?php echo $experience["title"]; ?>"></td>
</tr>

<tr>
<th>colume</th>
<td><textarea name="colume" id="colume" style="width:800px;height:300px;"><?php echo @$experience["colume"]; ?></textarea></td>
</tr>

<tr>
<th>ソースサンプル</th>
<td><p><strong>■ 画像左寄せのパターン</strong></p>
  <p>&lt;div class=&quot;PhotoBlock&quot;&gt;<br />
    &lt;div class=&quot;L-Photo&quot;&gt;&lt;img src=&quot;<span class="fc-red">アップロードした画像のパス</span>&quot; width=&quot;100%&quot; alt=&quot;<span class="fc-red">その画像に関連したタイトル</span>&quot;&gt;&lt;/div&gt;<br />
    &lt;p&gt;テキストを入れてください。&lt;/p&gt;<br />
    &lt;p&gt;テキストを入れてください。&lt;/p&gt;<br />
    &lt;/div&gt;</p>
<p><strong>■ 画像右寄せのパターン</strong></p>
  <p>&lt;div class=&quot;PhotoBlock&quot;&gt;<br />
    &lt;div class=&quot;R-Photo&quot;&gt;&lt;img src=&quot;<span class="fc-red">アップロードした画像のパス</span>&quot; width=&quot;100%&quot; alt=&quot;<span class="fc-red">その画像に関連したタイトル</span>&quot;&gt;&lt;/div&gt;<br />
    &lt;p&gt;テキストを入れてください。&lt;/p&gt;<br />
    &lt;p&gt;テキストを入れてください。&lt;/p&gt;<br />
    &lt;/div&gt;</p>
<p><strong>■ コンテンツ枠フルのパターン</strong></p>
<p>&lt;div class=&quot;MainVisual&quot;&gt;&lt;img src=&quot;<span class="fc-red">アップロードした画像のパス</span>&quot; width=&quot;100%&quot; alt=&quot;<span class="fc-red">その画像に関連したタイトル</span>&quot;&gt;&lt;/div&gt;</p>
<p><strong>■ 文章の終わりを少し空ける。</strong></p>
<p>&lt;p class=&quot;MidNote&quot;&gt;テキスト。テキスト。&lt;/p&gt;</p>
<p><strong>■ 文章の終わりを大きく開ける。</strong></p>
<p>&lt;p class=&quot;EndNote&quot;&gt;テキスト。テキスト。&lt;/p&gt;</p>
</td>
</tr>

<tr>
<th>image<br />(204 x 156)<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($experience["image"])){ ?>
<p><?php echo $server_url.@$experience['image']; ?></p>
<input type="hidden" name="old_image" value="<?php echo @$experience['image']; ?>">
<p><img src="../<?php echo $experience["image"]; ?>" /></p>
<?php } ?>
<input type="file" name="image" id="img1" />
<p id="thum1"></p>
</td>
</tr>

<tr>
<th>photo<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($experience["photo"])){ ?>
<p><?php echo $server_url.@$experience['photo']; ?></p>
<input type="hidden" name="old_photo" value="<?php echo @$experience['photo']; ?>">
<p><img src="../<?php echo $experience["photo"]; ?>"  /></p>
<?php } ?>
<input type="file" name="photo" id="img2" />
<p id="thum2"></p>
</td>
</tr>


<?php for($i=1;$i<=10;++$i){ ?>
<tr>
<th>photo<?php echo $i;?><br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($experience['photo'.$i])){ ?>
<p><?php echo $server_url.@$experience['photo'.$i]; ?></p>
<input type="hidden" name="old_photo<?php echo $i;?>" value="<?php echo @$experience['photo'.$i]; ?>">
<p><img src="../<?php echo @$experience['photo'.$i]; ?>" /></p>
<?php }?>
<input type="file" name="photo<?php echo $i;?>" id="img1<?php echo $i;?>">
<p id="thum1<?php echo $i;?>"></p>
</td>
</tr>
<?php } ?>

<tr>
<th>disp</th>
<td>
<select name="disp">
<option value="0" <?php if(@$experience['disp']==0){echo 'selected="selected"';} ?>>0：非表示</option>
<option value="1" <?php if(@$experience['disp']==1){echo 'selected="selected"';} ?>>1：表示</option>
</select>
</td>
</tr>

<tr>
<th>update_time</th>
<td><input type="text" name="update_time" value="<?php echo @$experience['update_time']; ?>"></td>
</tr>

</table>




<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a></p>
</form>

</div>



</div>

</div>
<script>
initThumbnail(1,'auto','auto');
initThumbnail(2,'auto','auto');

initThumbnail(11,'auto','auto');
initThumbnail(12,'auto','auto');
initThumbnail(13,'auto','auto');
initThumbnail(14,'auto','auto');
initThumbnail(15,'auto','auto');
initThumbnail(16,'auto','auto');
initThumbnail(17,'auto','auto');
initThumbnail(18,'auto','auto');
initThumbnail(19,'auto','auto');
initThumbnail(110,'auto','auto');


function initThumbnail(id,w,h)
{
	var obj1 = document.getElementById('img'+id);
	obj1.addEventListener("change", function(evt){
		var file = evt.target.files;
		var reader = new FileReader();
		reader.readAsDataURL(file[0]);
		reader.onload = function(){
			var dataUrl = reader.result;
			document.getElementById('thum'+id).innerHTML = "<img src='" + dataUrl + "' width='"+w+"' height='"+h+"'>";
		}
	},false);
}

function load_category(division_id, category_id)
{
	$.ajax({
		url: 'ajax.php?type=category&division_id='+division_id+'&category_id='+category_id,
		type: 'GET',
		cache: false,
		success: function(ret){
			$("#category_id").html(ret);
			$("#item_id").html('<option value="0">全て</option>');
		}
	});
}
function load_item(category_id, item_id)
{
	$.ajax({
		url: 'ajax.php?type=item&category_id='+category_id+'&item_id='+item_id+'&all',
		type: 'GET',
		cache: false,
		success: function(ret){
			$("#item_id").html(ret);
		}
	});
}
</script>
</body>
</html>
