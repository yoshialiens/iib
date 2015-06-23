<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	
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
	
	
	$division_id = (int)@$_GET['id'];
	
	$division_model = new DivisionModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'name' => @$_POST['name'],
			'info' => @$_POST['info'],
		);
		if($division_id==0){
			//新規ならレコードを作っておく
			$division_model->insertDivision($data);
			$division_id = $division_model->lastInsertId();
		}
		
		//画像１のアップロード
		if(($fname = UploadLib::getInstance()->_upload('image1', 'division', $division_id)) !== false){
			//成功ならphoto1を更新する
			$data['image1'] = 'contents/division/' . $fname;
		}else{
			$data['image1'] = @$_POST['old_image1'];
		}
		
		
		//更新
		$division_model->updateDivision($division_id, $data);
		
		header("location: reg-division.php?id=".$division_id."#upd");
		exit;
	}
	
	
	$division = $division_model->getDivision($division_id);
	$division['name'] = htmlspecialchars(@$division['name'], ENT_QUOTES, 'UTF-8');
	$division['info'] = htmlspecialchars(@$division['info'], ENT_QUOTES, 'UTF-8');
	
	
	
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

<h2>大カテゴリーの登録/編集</h2>
<form method="post" action="reg-division.php?id=<?php echo $division_id; ?>" enctype="multipart/form-data" id="frmReg">
<table class="Reg">

<tr>
<th>大カテゴリー名<br />[name]</th>
<td><input type="text" name="name" value="<?php echo $division['name']; ?>"></td>
</tr>

<tr>
<th>カテゴリーのイメージ<br />[image1]<br /><span style="color:#F19292; font-size:11px;">※画像は縮小されて表示されます。</span></th>
<td>
<?php if(!empty($division['image1'])){ ?>
<p><?php echo $server_url.$division['image1']; ?></p>
<input type="hidden" name="old_image1" value="<?php echo $division['image1']; ?>">
<p><img src="../<?php echo $division['image1']; ?>" /></p>
<?php }?>
<input type="file" name="image1" id="img1">
<p id="thum1"></p>
</td>
</tr>

<tr>
<th>カテゴリーの情報<br />[info]</th>
<td><input type="text" name="info" value="<?php echo $division['info']; ?>"></td>
</tr>

</table>
</form>

<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a></p>


</div>



</div>

<script>
initThumbnail(1,'auto','auto');

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
