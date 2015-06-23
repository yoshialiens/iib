<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ItemModel.class.php';
	
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
	
	
	$item_id = (int)@$_GET['id'];
	
	$item_model = new ItemModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'division_id' => @$_POST['division_id'],
			'category_id' => @$_POST['category_id'],
			'author_id' => @$_POST['author_id'],
			'name'        => @$_POST['name'],
			'company_name'=> @$_POST['company_name'],
			'url'         => @$_POST['url'],
			'myurl'       => @$_POST['myurl'],
			'title'       => @$_POST['title'],
			'description' => @$_POST['description'],
			'h1'          => @$_POST['h1'],
			'h2'          => @$_POST['h2'],
			'rank_info'   => @$_POST['rank_info'],
			'field_info'  => @$_POST['field_info'],
			'banner'      => @$_POST['banner'],
			'about'       => @$_POST['about'],
			'prof'        => @$_POST['prof'],
			'prof2'       => @$_POST['prof2'],
			'point'       => @$_POST['point'],
		);
		if($item_id==0){
			//新規ならレコードを作っておく
			$item_model->insertItem($data);
			$item_id = $item_model->lastInsertId();
		}
		
		//画像1のアップロード
		$upload_file = "{$item_id}_1";
		if(($fname = UploadLib::getInstance()->_upload('photo1', 'item', $upload_file)) !== false){
			//成功ならphoto1を更新する
			$data['photo1'] = 'contents/item/' . $fname;
		}else{
			$data['photo1'] = @$_POST['old_photo1'];
		}
		
		//画像2のアップロード
		$upload_file = "{$item_id}_2";
		if(($fname = UploadLib::getInstance()->_upload('photo2', 'item', $upload_file)) !== false){
			//成功ならphoto1を更新する
			$data['photo2'] = 'contents/item/' . $fname;
		}else{
			$data['photo2'] = @$_POST['old_photo2'];
		}
		
		for($i=1;$i<=6;++$i)
		{
			$upload_file = "{$item_id}_3_".$i;
			if(($fname = UploadLib::getInstance()->_upload('manual_image'.$i, 'item', $upload_file)) !== false){
				//成功ならphoto1を更新する
				$data['manual_image'.$i] = 'contents/item/' . $fname;
			}else{
				$data['manual_image'.$i] = @$_POST['old_manual_image'.$i];
			}
		}
		
		
		//更新
		$item_model->updateItem($item_id, $data);
		header("location: reg-item.php?id=".$item_id."#upd");
		exit;
	}
	
	$division_model = new DivisionModel();
	$division_all = $division_model->select();
	foreach($division_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$item = $item_model->getItem($item_id);
	$item['name'] = htmlspecialchars(@$item['name'], ENT_QUOTES, 'UTF-8');
	$item['company_name'] = htmlspecialchars(@$item['company_name'], ENT_QUOTES, 'UTF-8');
	$item['url'] = htmlspecialchars(@$item['url'], ENT_QUOTES, 'UTF-8');
	$item['myurl'] = htmlspecialchars(@$item['myurl'], ENT_QUOTES, 'UTF-8');
	$item['title'] = htmlspecialchars(@$item['title'], ENT_QUOTES, 'UTF-8');
	$item['h1'] = htmlspecialchars(@$item['h1'], ENT_QUOTES, 'UTF-8');
	$item['h2'] = htmlspecialchars(@$item['h2'], ENT_QUOTES, 'UTF-8');
	$item['rank_info'] = htmlspecialchars(@$item['rank_info'], ENT_QUOTES, 'UTF-8');
	$item['banner'] = htmlspecialchars(@$item['banner'], ENT_QUOTES, 'UTF-8');
	$item['about'] = htmlspecialchars(@$item['about'], ENT_QUOTES, 'UTF-8');
	$item['prof'] = htmlspecialchars(@$item['prof'], ENT_QUOTES, 'UTF-8');
	$item['prof2'] = htmlspecialchars(@$item['prof2'], ENT_QUOTES, 'UTF-8');
	$item['description'] = htmlspecialchars(@$item['description'], ENT_QUOTES, 'UTF-8');
	$item['point'] = htmlspecialchars(@$item['point'], ENT_QUOTES, 'UTF-8');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

<body>
<?php require dirname(__FILE__).'/alert.php'; ?>
<div class="Content">

<div class="PageBtn">
<ul>
<?php require_once 'menu.php'; ?>
</ul>
</div>

<div class="Cnt">

<h2>ブログ記事の登録/編集</h2>

<!--
<div class="MenuBtns">
<ul>
<li><a href="icon/" target="_blank">アイコン一覧はこちら</a></li>
</ul>
</div>
-->

<form method="post" action="reg-item.php?id=<?php echo $item_id; ?>" enctype="multipart/form-data" id="frmReg">

<table class="Reg">
<tr>
<th>大カテゴリー名</th>
<td>
<select name="division_id" onchange="load_category(this.value,1);">
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['division_id']; ?>" <?php if(@$item['division_id']==$v['division_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
</td>
</tr>
</table>

<p>カテゴリ・ライターを選択してください。</p>
<table class="Reg">
<tr>
<th>カテゴリ名</th>
<td>

<!--<select name="category_id" id="category_id" onchange="load_category(this.value,1);">
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['category_id']; ?>" <?php if(@$item['category_id']==$v['category_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
-->
<select name="category_id" id="category_id">
</select>
</td>
</tr>
<script>
$(document).ready(function(){
	load_category(<?php echo (int)@$item['division_id']; ?>, <?php echo (int)@$item['category_id']; ?>);
});
</script>

<tr>
<th>ライター名</th>
<td>
<!--<select name="category_id" id="category_id" onchange="load_category(this.value,1);">
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['category_id']; ?>" <?php if(@$item['category_id']==$v['category_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
-->
<select name="author_id" id="author_id">
</select>
</td>
</tr>
<script>
$(document).ready(function(){
	load_author(<?php echo (int)@$item['division_id']; ?>, <?php echo (int)@$item['author_id']; ?>);
});
</script>

</table>


<p>ブログ記事の詳細を入力してください。</p>
<table class="Reg">
<tr>
<th>記事タイトル<br />[name]</th>
<td><input type="text" name="name" value="<?php echo @$item['name']; ?>"></td>
</tr>

<!--
<tr>
<th>ロゴ<br />[photo1]<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($item['photo1'])){ ?>
<p><?php echo $server_url.@$item['photo1']; ?></p>
<input type="hidden" name="old_photo1" value="<?php echo @$item['photo1']; ?>">
<p><img src="../<?php echo @$item['photo1']; ?>" /></p>
<?php }?>
<input type="file" name="photo1" id="img1">
<p id="thum1"></p>
</td>
</tr>
-->

<!--
<tr>
<th>会社名(筆者名に変更)<br />[company_name]</th>
<td><input type="text" name="company_name" value="<?php echo @$item['company_name']; ?>"></td>
</tr>
-->

<!--
<tr>
<th>表示用のURL<br />[url]</th>
<td><input type="text" name="url" value="<?php echo @$item['url']; ?>"></td>
</tr>

<tr>
<th>転送用のURL<br />[myurl]</th>
<td><input type="text" name="myurl" value="<?php echo @$item['myurl']; ?>"></td>
</tr>
-->

<tr>
<th>titleタグ<br />[title]</th>
<td><input type="text" name="title" value="<?php echo @$item['title']; ?>"></td>
</tr>

<tr>
<th>discriptionタグ<br />[description]</th>
<td><input type="text" name="description" value="<?php echo @$item['description']; ?>"></td>
</tr>

<tr>
<th>h1タグ<br />[h1]</th>
<td><input type="text" name="h1" value="<?php echo @$item['h1']; ?>"></td>
</tr>
<!--
<tr>
<th>h2タグ<br />[h2]</th>
<td><input type="text" name="h2" value="<?php echo @$item['h2']; ?>"></td>
</tr>
-->
<tr>
<th>TOP/一覧ページの抜粋<br />[rank_info]</th>
<td><textarea class="S01" name="rank_info"><?php echo @$item['rank_info']; ?></textarea></td>
</tr>
<!--
<tr>
<th>fieldプロフィール<br />[field_info]</th>
<td><textarea class="S01" name="field_info"><?php echo @$item['field_info']; ?></textarea></td>
</tr>
-->
<!--
<tr>
<th>バナー用<br />[banner]</th>
<td><input type="text" name="banner" value="<?php echo @$item['banner']; ?>"></td>
</tr>
-->

<tr>
<th>サイトのキャプチャー画像<br />[photo1]<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td class="photoblock">
<?php if(!empty($item['photo1'])){ ?>
<p><?php echo $server_url.@$item['photo1']; ?></p>
<input type="hidden" name="old_photo1" value="<?php echo @$item['photo1']; ?>">
<p><img src="../<?php echo @$item['photo1']; ?>" /></p>
<?php }?>
<input type="file" name="photo1" id="img1">
<p id="thum1"></p>
</td>
</tr>

<tr>
<th>記事本文<br />[about]</th>
<td><textarea class="S07" name="about"><?php echo @$item['about']; ?></textarea></td>
</tr>

<!--
<tr>
<th>reputation用プロフィール<br />[prof]</th>
<td><textarea class="S07" name="prof"><?php echo @$item['prof']; ?></textarea></td>
</tr>
<tr>
<th>予備<br />[prof2]</th>
<td><textarea class="S01" name="prof2"><?php echo @$item['prof2']; ?></textarea></td>
</tr>
-->

<tr>
<th>プロフ用画像<!--<?php echo $i; ?>--><br />[manual_image]<br /><span style="color:#F19292; font-size:11px;">※最大7枚まで設定可能。</span></th>
<td class="photoblock">


<?php for($i=1;$i<=6;++$i){ ?>


<?php if(!empty($item['manual_image'.$i])){ ?>
<p><?php echo $server_url.@$item['manual_image'.$i]; ?></p>
<input type="hidden" name="old_manual_image<?php echo $i; ?>" value="<?php echo @$item['manual_image'.$i]; ?>">
<p><img src="../<?php echo @$item['manual_image'.$i]; ?>" /></p>
<?php }?>
<input type="file" name="<?php echo 'manual_image'.$i; ?>" id="img1<?php echo $i; ?>">
<p id="thum1<?php echo $i; ?>"></p>
<?php } ?>
</td>
</tr>


<!--<tr>
<th>Point<br />int(11)</th>
<td><input type="text" name="point" value="<?php echo @$item['point']; ?>"></td>
</tr>
-->
</table>
</form>


<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a></p>


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
		}
	});
}
function load_author(division_id, author_id)
{
	$.ajax({
		url: 'ajax.php?type=author&division_id='+division_id+'&author_id='+author_id,
		type: 'GET',
		cache: false,
		success: function(ret){
			$("#author_id").html(ret);
		}
	});
}
</script>
</body>
</html>
