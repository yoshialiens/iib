<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/UploadLib.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/AuthorModel.class.php';
	
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
	
	
	$author_id = (int)@$_GET['id'];
	
	$author_model = new AuthorModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'division_id' => @$_POST['division_id'],
			'sort_no'     => (int)@$_POST['sort_no'],
			'name'        => @$_POST['name'],
			//'color'       => @$_POST['color'],
			//'title'       => @$_POST['title'],
			'description' => @$_POST['description'],
			//'h1'          => @$_POST['h1'],
			//'h2'          => @$_POST['h2'],
			//'main_text'   => @$_POST['main_text'],
		);
		/*
		for($i=1;$i<=AuthorModel::SIDE_AUTHOR_SIZE;++$i){
			$data['author_id_'.$i]=(int)@$_POST['author_id_'.$i];
		}
		*/
		if($author_id==0){
			//新規ならレコードを作っておく
			$author_model->insertAuthor($data);
			$author_id = $author_model->lastInsertId();
		}
		
		//更新
		$author_model->updateAuthor($author_id, $data);
		
		header("location: reg-author.php?id=".$author_id."#upd");
		exit;
	}
	
	$division_model = new DivisionModel();
	$division_all = $division_model->select();
	foreach($division_all as $author)
	{
		$author['name'] = htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8');
		unset($author);
	}
	
	$author = $author_model->getAuthor($author_id);
	$author['name'] = htmlspecialchars(@$author['name'], ENT_QUOTES, 'UTF-8');
	$author['description'] = htmlspecialchars(@$author['description'], ENT_QUOTES, 'UTF-8');
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

<h2>ライターの登録/編集</h2>

<form method="post" action="reg-author.php?id=<?php echo $author_id; ?>" enctype="multipart/form-data" id="frmReg">

<!--<p>大カテゴリーを選択してください。</p>-->
<table class="Reg">

<tr>
<th>大カテゴリー名</th>
<td>
<select name="division_id">
<!--<option value="0">未設定</option>-->
<?php foreach($division_all as $v){ ?>
<option value="<?php echo $v['division_id']; ?>" <?php if(@$author['division_id']==$v['division_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
</td>
</tr>

</table>

<p>カテゴリの詳細を入力してください。</p>
<table class="Reg">

<tr>
<th>カテゴリ名</th>
<td><input type="text" name="name" value="<?php echo $author['name'];?>"></td>
</tr>

<tr>
<th>順番[sort_no]</th>
<td><input type="text" name="sort_no" value="<?php echo $author['sort_no'];?>"></td>
</tr>

<tr>
<th>meta [description]</th>
<td><input type="text" name="description" value="<?php echo $author['description'];?>"></td>
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
