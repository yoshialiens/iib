<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ReviewModel.class.php';
	
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
	
	
	$review_id = (int)@$_GET['id'];
	
	$review_model = new ReviewModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'item_id'     => @$_POST['item_id'],
			'name'        => @$_POST['name'],
			'pickup'      => @$_POST['pickup'],
			'gender'      => @$_POST['gender'],
			'age'         => @$_POST['age'],
			'image'       => @$_POST['image'],
			'point'       => @$_POST['point'],
			'title'       => @$_POST['title'],
			'good'        => @$_POST['good'],
			'bad'         => @$_POST['bad'],
			'text'        => @$_POST['text'],
			'alt1'        => @$_POST['alt1'],
			'alt2'        => @$_POST['alt2'],
			'alt3'        => @$_POST['alt3'],
			'enable'      => @$_POST['enable'],
			'update_time' => @$_POST['update_time'],
			'create_time' => @$_POST['create_time'],
			'btn_thanks'  => @$_POST['btn_thanks'],
		);
		if($review_id==0){
			//新規ならレコードを作っておく
			$newdata = $data;
			$review_model->insertReview($data);
			$review_id = $review_model->lastInsertId();
		}
		
		//画像1のアップロード
		for($i=1;$i<=3;++$i)
		{
			$upload_file = "{$review_id}_".$i;
			if(($fname = UploadLib::getInstance()->_upload('photo'.$i, 'review', $upload_file, true)) !== false){
				//成功ならphoto1を更新する
				$data['photo'.$i] = 'contents/review/' . $fname;
			}else{
				$data['photo'.$i] = @$_POST['old_photo'.$i];
			}
		}
		
		//更新
		$review_model->updateReview($review_id, $data);
		header("location: reg-review.php?id=".$review_id."#upd");
		exit;
	}
	
	$review = $review_model->getReview($review_id);
	
	$item_model = new ItemModel();
	$item = $item_model->getItem($review['item_id']);
	
	$division_model = new DivisionModel();
	$division_all = $division_model->select();
	foreach($division_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$category_model = new CategoryModel();
	$category_all = $category_model->select(array('division_id'=>$item['division_id']));
	foreach($category_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$review['title'] = htmlspecialchars(@$review['title'], ENT_QUOTES, 'UTF-8');
	$review['name'] = htmlspecialchars(@$review['name'], ENT_QUOTES, 'UTF-8');
	$review['good'] = htmlspecialchars(@$review['good'], ENT_QUOTES, 'UTF-8');
	$review['bad'] = htmlspecialchars(@$review['bad'], ENT_QUOTES, 'UTF-8');
	$review['text'] = htmlspecialchars(@$review['text'], ENT_QUOTES, 'UTF-8');
	for($i=1;$i<=3;++$i){
		$review['alt'.$i] = htmlspecialchars(@$review['alt'.$i], ENT_QUOTES, 'UTF-8');
	}
	$review['btn_thanks'] = htmlspecialchars(@$review['btn_thanks'], ENT_QUOTES, 'UTF-8');
	if(empty($review['update_time']))$review['update_time'] = date("Y-m-d H:i:s");
	if(empty($review['create_time']))$review['create_time'] = date("Y-m-d H:i:s");
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>口コミの登録/編集</title>

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

<h2>口コミの登録/編集</h2>

<div class="MenuBtns">
<ul>
<li><a href="icon/" target="_blank">アイコン一覧はこちら</a></li>
</ul>
</div>

<form method="post" action="reg-review.php?id=<?php echo $review_id; ?>" enctype="multipart/form-data" id="frmReg">

<table class="Reg">
<tr>
<th>大カテゴリー名</th>
<td>
<select name="division_id" onChange="load_category(this.value,0)">
<option value="0">未設定</option>
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['division_id']; ?>" <?php if(@$item['division_id']==$v['division_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
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
<option value="<?php echo $v['category_id']; ?>" <?php if(@$item['category_id']==$v['category_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
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
	load_item(<?php echo (int)@$item['category_id']; ?>, <?php echo (int)@$item['item_id']; ?>);
});
</script>


<h2>口コミの詳細</h2>

<table class="Reg">

<tr>
<th>口コミタイトル<br>[title]</th>
<td><input type="text" name="title" value="<?php echo @$review['title']; ?>"></td>
</tr>

<tr>
<th>投稿者名<br>[name]</th>
<td><input type="text" name="name" value="<?php echo @$review['name']; ?>"></td>
</tr>

<tr>
<th>ピックアップ<br>[pickup]</th>
<td>
<select name="pickup">
<option value="0" <?php if(@$review['pickup']==0){echo 'selected="selected"';} ?>>0: 無効</option>
<option value="1" <?php if(@$review['pickup']==1){echo 'selected="selected"';} ?>>1: 有効</option>
</select>
</td>
</tr>

<tr>
<th>性別<br>[gender]</th>
<td>
<input type="radio" name="gender" value="1" id="radio01" <?php if(@$review['gender']==1)echo 'checked="checked"'; ?> />
<label for="radio01" class="radio">男性</label>

<input type="radio" name="gender" value="2" id="radio02" <?php if(@$review['gender']==2)echo 'checked="checked"'; ?> />
<label for="radio02" class="radio">女性</label>
</td>
</tr>

<tr>
<th>年代<br>[age]</th>
<td>
<select name="age">
<option value="1" <?php if(@$review['age']==1)echo 'selected="selected"'; ?>>10代前半</option>
<option value="2" <?php if(@$review['age']==2)echo 'selected="selected"'; ?>>10代後半</option>
<option value="3" <?php if(@$review['age']==3)echo 'selected="selected"'; ?>>20代前半</option>
<option value="4" <?php if(@$review['age']==4)echo 'selected="selected"'; ?>>20代後半</option>
<option value="5" <?php if(@$review['age']==5)echo 'selected="selected"'; ?>>30代前半</option>
<option value="6" <?php if(@$review['age']==6)echo 'selected="selected"'; ?>>30代後半</option>
<option value="7" <?php if(@$review['age']==7)echo 'selected="selected"'; ?>>40代前半</option>
<option value="8" <?php if(@$review['age']==8)echo 'selected="selected"'; ?>>40代後半</option>
<option value="9" <?php if(@$review['age']==9)echo 'selected="selected"'; ?>>50代前半</option>
<option value="10" <?php if(@$review['age']==10)echo 'selected="selected"'; ?>>50代後半</option>
<option value="11" <?php if(@$review['age']==11)echo 'selected="selected"'; ?>>60代前半</option>
<option value="12" <?php if(@$review['age']==12)echo 'selected="selected"'; ?>>60代後半</option>
<option value="13" <?php if(@$review['age']==13)echo 'selected="selected"'; ?>>70代～</option>
</select>
</td>
</tr>

<tr>
<th>表情イメージ<br>[image]</th>
<td>
<select name="image">
<option value="5" <?php if(@$review['image']==5)echo 'selected="selected"'; ?>>5 大変良い</option>
<option value="4" <?php if(@$review['image']==4)echo 'selected="selected"'; ?>>4 良い</option>
<option value="3" <?php if(@$review['image']==3)echo 'selected="selected"'; ?>>3 普通</option>
<option value="2" <?php if(@$review['image']==2)echo 'selected="selected"'; ?>>2 悪い</option>
<option value="1" <?php if(@$review['image']==1)echo 'selected="selected"'; ?>>1 非常に悪い</option>
</select>
</td>
</tr>

<tr>
<th>point</th>
<td><input type="text" name="point" value="<?php echo @$review['point']; ?>"></td>
</tr>

<tr>
<th>good<br>[good]</th>
<td><textarea class="S01" name="good"><?php echo @$review['good']; ?></textarea></td>
</tr>

<tr>
<th>bad<br>[bad]</th>
<td><textarea class="S01" name="bad"><?php echo @$review['bad']; ?></textarea></td>
</tr>

<tr>
<th>text<br>[text]</th>
<td><textarea class="S02" name="text"><?php echo @$review['text']; ?></textarea></td>
</tr>

<?php for($i=1;$i<=3;++$i){ ?>
<tr>
<th>投稿された写真<?php echo $i;?><br />[photo<?php echo $i;?>]<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($review['photo'.$i])){ ?>
<p style="text-align:right;"><input type="button" value="削除" onClick="onRemovePhoto(<?php echo $i; ?>);" /></p>
<p id="photo_url<?php echo $i; ?>"><?php echo $server_url.@$review['photo'.$i]; ?></p>
<input type="hidden" name="old_photo<?php echo $i;?>" id="old_photo<?php echo $i;?>" value="<?php echo @$review['photo'.$i]; ?>">
<p id="photo_view<?php echo $i;?>"><img src="../<?php echo @$review['photo'.$i]; ?>" /></p>
<?php }?>
<input type="file" name="photo<?php echo $i;?>" id="img<?php echo $i;?>">
<p id="thum<?php echo $i;?>"></p>
</td>
</tr>

<tr>
<th>altタグ用テキスト<?php echo $i;?><br />[alt<?php echo $i;?>]</th>
<td><input type="text" name="alt<?php echo $i;?>" id="alt<?php echo $i;?>" value="<?php echo @$review['alt'.$i]; ?>"></td>
</tr>
<?php } ?>


<tr>
<th>enable</th>
<td>
<input type="radio" name="enable" value="1" id="radio03" <?php if(@$review['enable']==1)echo 'checked="checked"'; ?> />
<label for="radio03" class="radio">有効</label>

<input type="radio" name="enable" value="0" id="radio04" <?php if(@$review['enable']==0)echo 'checked="checked"'; ?> />
<label for="radio04" class="radio">無効</label>
</td>
</tr>

<tr>
<th>update_time</th>
<td><input type="text" name="update_time" value="<?php echo @$review['update_time']; ?>"></td>
</tr>

<tr>
<th>create_time</th>
<td><input type="text" name="create_time" value="<?php echo @$review['create_time']; ?>"></td>
</tr>

<tr>
<th>btn_thanks(数字)</th>
<td><input type="text" name="btn_thanks" value="<?php echo @$review['btn_thanks']; ?>"></td>
</tr>

</table>
</form>





<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a></p>


</div>



</div>

</div>
<script>
initThumbnail(1,'auto','auto');
initThumbnail(2,'auto','auto');
initThumbnail(3,'auto','auto');

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
			$("#item_id").html('<option value="0">未設定</option>');
		}
	});
}
function load_item(category_id, item_id)
{
	$.ajax({
		url: 'ajax.php?type=item&category_id='+category_id+'&item_id='+item_id,
		type: 'GET',
		cache: false,
		success: function(ret){
			$("#item_id").html(ret);
		}
	});
}
function onRemovePhoto(id)
{
	if(!confirm("本当に削除しますか？\n（削除後に「登録」ボタンで反映します）")){
		return;
	}
	
	$("#old_photo"+id).val("");
	$("#img"+id).val("");
	$("#alt"+id).val("");
	$("#photo_url"+id).html("");
	$("#photo_view"+id).html("");
	$("#thum"+id).html("");
}
</script>
</body>
</html>
