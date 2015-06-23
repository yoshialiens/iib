<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	
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
	
	
	$category_id = (int)@$_GET['id'];
	
	$category_model = new CategoryModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'division_id' => @$_POST['division_id'],
			'name'        => @$_POST['name'],
			'sort_no'     => (int)@$_POST['sort_no'],
			'color'       => @$_POST['color'],
			'title'       => @$_POST['title'],
			'description' => @$_POST['description'],
			'h1'          => @$_POST['h1'],
			'h2'          => @$_POST['h2'],
			'main_text'   => @$_POST['main_text'],
		);
		for($i=1;$i<=CategoryModel::SIDE_CATEGORY_SIZE;++$i){
			$data['category_id_'.$i]=(int)@$_POST['category_id_'.$i];
		}
		
		if($category_id==0){
			//新規ならレコードを作っておく
			$category_model->insertCategory($data);
			$category_id = $category_model->lastInsertId();
		}
		
		//画像1のアップロード
		$upload_file = "{$category_id}_1";
		if(($fname = UploadLib::getInstance()->_upload('image1', 'category', $upload_file)) !== false){
			//成功ならphoto1を更新する
			$data['image1'] = 'contents/category/' . $fname;
		}else{
			$data['image1'] = @$_POST['old_image1'];
		}
		
		//画像2のアップロード
		$upload_file = "{$category_id}_2";
		if(($fname = UploadLib::getInstance()->_upload('image2', 'category', $upload_file)) !== false){
			//成功ならphoto1を更新する
			$data['image2'] = 'contents/category/' . $fname;
		}else{
			$data['image2'] = @$_POST['old_image2'];
		}
		
		
		//更新
		$category_model->updateCategory($category_id, $data);
		
		header("location: reg-category.php?id=".$category_id."#upd");
		exit;
	}
	
	$division_model = new DivisionModel();
	$division_all = $division_model->select();
	foreach($division_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$category = $category_model->getCategory($category_id);
	$category['name'] = htmlspecialchars(@$category['name'], ENT_QUOTES, 'UTF-8');
	$category['title'] = htmlspecialchars(@$category['title'], ENT_QUOTES, 'UTF-8');
	$category['description'] = htmlspecialchars(@$category['description'], ENT_QUOTES, 'UTF-8');
	$category['h1'] = htmlspecialchars(@$category['h1'], ENT_QUOTES, 'UTF-8');
	$category['h2'] = htmlspecialchars(@$category['h2'], ENT_QUOTES, 'UTF-8');
	$category['main_text'] = htmlspecialchars(@$category['main_text'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">

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

<h2>ジャンルの登録/編集</h2>

<form method="post" action="reg-category.php?id=<?php echo $category_id; ?>" enctype="multipart/form-data" id="frmReg">

<p>大カテゴリーを選択してください。</p>
<table class="Reg">

<tr>
<th>大カテゴリー名</th>
<td>

<select name="division_id">
<option value="0">未設定</option>
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['division_id']; ?>" <?php if(@$category['division_id']==$v['division_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
</td>
</tr>

</table>

<p>ジャンルの詳細を入力してください。</p>
<table class="Reg">

<tr>
<th>ジャンル名</th>
<td><input type="text" name="name" value="<?php echo @$category['name'];?>"></td>
</tr>

<tr>
<th>順番[sort_no]</th>
<td><input type="text" name="sort_no" value="<?php echo @$category['sort_no'];?>"></td>
</tr>

<tr>
<th>meta[title]</th>
<td><input type="text" name="title" value="<?php echo @$category['title'];?>"></td>
</tr>

<tr>
<th>meta [description]</th>
<td><input type="text" name="description" value="<?php echo @$category['description'];?>"></td>
</tr>

<tr>
<th>カテゴリーのイメージ<br />[image1]<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($category['image1'])){ ?>
<p><?php echo $server_url.@$category['image1']; ?></p>
<input type="hidden" name="old_image1" value="<?php echo @$category['image1']; ?>">
<p><img src="../<?php echo @$category['image1']; ?>" /></p>
<?php }?>
<input type="file" name="image1" id="img1">
<p id="thum1"></p>
</td>
</tr>

<tr>
<th>タイトル背景色</th>
<td><select name="color">
<option value="Blue" <?php if(@$category['color']=='Blue')echo 'selected="selected"'; ?>>Blue</option>
<option value="Green" <?php if(@$category['color']=='Green')echo 'selected="selected"'; ?>>Green</option>
<option value="Gray" <?php if(@$category['color']=='Gray')echo 'selected="selected"'; ?>>Gray</option>
<option value="Pink" <?php if(@$category['color']=='Pink')echo 'selected="selected"'; ?>>Pink</option>
<option value="Orange" <?php if(@$category['color']=='Orange')echo 'selected="selected"'; ?>>Orange</option>
<option value="Red" <?php if(@$category['color']=='Red')echo 'selected="selected"'; ?>>Red</option>
</select></td>
</tr>

<tr>
<th>[h1]タグ</th>
<td><input type="text" name="h1" value="<?php echo @$category['h1'];?>"></td>
</tr>

<tr>
<th>[h2]タグ</th>
<td><input type="text" name="h2" value="<?php echo @$category['h2'];?>"></td>
</tr>

<tr>
<th>[main_text]タグ</th>
<td><textarea name="main_text"><?php echo @$category['main_text'];?></textarea></td>
</tr>

<tr>
<th>予備イメージ<br />[image2]</th>
<td>
<?php if(!empty($category['image2'])){ ?>
<p><?php echo $server_url.@$category['image2']; ?></p>
<input type="hidden" name="old_image2" value="<?php echo @$category['image2']; ?>">
<p><img src="../<?php echo @$category['image2']; ?>" /></p>
<?php }?>
<input type="file" name="image2" id="img2">
<p id="thum2"></p>
</td>
</tr>

<tr>
<th>サイドメニュー</th>
<td>
<?php for($i=1;$i<=CategoryModel::SIDE_CATEGORY_SIZE;++$i): ?>
<input class="mini" type="text" name="category_id_<?php echo $i; ?>" value="<?php echo @$category['category_id_'.$i]; ?>"> 
<?php endfor; ?>
</td>
</tr>


</table>
</form>

<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a></p>


</div>



</div>

<script>
initThumbnail(1,'auto','auto');
initThumbnail(2,'auto','auto');

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
</script>
</body>
</html>
