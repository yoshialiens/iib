<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CommentModel.class.php';
	
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
	
	
	$comment_id = (int)@$_GET['id'];
	
	$comment_model = new CommentModel();
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$data = array(
			'review_id'   => @$_POST['review_id'],
			'name'        => @$_POST['name'],
			'comment'     => @$_POST['comment'],
			'enable'      => @$_POST['enable'],
			'update_time' => @$_POST['update_time'],
			'btn_yes'     => @$_POST['btn_yes'],
		);
		if($comment_id==0){
			//新規ならレコードを作っておく
			$newdata = $data;
			$newdata['comment_id'] = BaseModel::IS_NULL;
			$comment_model->insert($newdata);
			$comment_id = $comment_model->lastInsertId();
		}
		
		//更新
		$comment_model->update($data, array('comment_id'=>$comment_id));
		header("location: reg-comment.php?id=".$comment_id."#upd");
		exit;
	}
	
	$comment = $comment_model->getComment($comment_id);
	
	$review_model = new ReviewModel();
	$review = $review_model->getReview(@$comment['review_id']);
	
	$item_model = new ItemModel();
	$item = $item_model->getItem((int)@$review['item_id']);
	$item_all = $item_model->select(array('division_id'=>@$item['division_id'], 'category_id'=>@$item['category_id']), array('item_id'=>BaseModel::ORDER_ASC));
	
	$division_model = new DivisionModel();
	$division_all = $division_model->select();
	foreach($division_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$category_model = new CategoryModel();
	$category_all = $category_model->select(array('division_id'=>(int)@$item['division_id']));
	foreach($category_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
	$comment['name'] = htmlspecialchars(@$comment['name'], ENT_QUOTES, 'UTF-8');
	$comment['comment'] = htmlspecialchars(@$comment['comment'], ENT_QUOTES, 'UTF-8');
	$comment['btn_yes'] = (int)@$comment['btn_yes'];
	if(empty($comment['update_time']))$comment['update_time'] = date("Y-m-d H:i:s");
?><!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>コメントの編集</title>

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

<h2>コメントの編集</h2>

<form method="post" action="reg-comment.php?id=<?php echo $comment_id; ?>" enctype="multipart/form-data" id="frmReg">

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
<select name="item_id" id="item_id" onChange="load_review(this.value,0)">
<option value="0">未設定</option>
<?php foreach($item_all as $v){ ?>
<option value="<?php echo $v['item_id']; ?>" <?php if(@$review['item_id']==$v['item_id'])echo 'selected="selected"' ?>><?php echo $v['name']; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<th>口コミタイトル</th>
<td>
<select name="review_id" id="review_id">
</select>
</td>
</tr>
</table>
<script>
$(document).ready(function(){
	load_review(<?php echo (int)@$review['item_id']; ?>, <?php echo (int)@$comment['review_id']; ?>);
});
</script>

<h2>コメントの詳細</h2>

<table class="Reg">

<tr>
<th>投稿者名<br>[name]</th>
<td><input type="text" name="name" value="<?php echo $comment['name']; ?>"></td>
</tr>

<tr>
<th>コメント<br>[comment]</th>
<td><textarea class="S02" name="comment"><?php echo $comment['comment']; ?></textarea></td>
</tr>

<tr>
<th>表示<br>[enable]</th>
<td>
<select name="enable">
<option value="0" <?php if(@$comment['enable']==0)echo 'selected="selected"' ?>>無効</option>
<option value="1" <?php if(@$comment['enable']==1)echo 'selected="selected"' ?>>有効</option>
</select>
</td>
</tr>

<tr>
<th>私もそう思うボタン<br>[btn_yes]</th>
<td><input type="text" name="btn_yes" value="<?php echo $comment['btn_yes']; ?>"></td>
</tr>

<tr>
<th>update_time</th>
<td><input type="text" name="update_time" value="<?php echo $comment['update_time']; ?>"></td>
</tr>

</table>
</form>





<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a></p>


</div>



</div>

</div>
<script>
function load_category(division_id, category_id)
{
	$.ajax({
		url: 'ajax.php?type=category&division_id='+division_id+'&category_id='+category_id,
		type: 'GET',
		cache: false,
		success: function(ret){
			$("#category_id").html(ret);
			$("#item_id").html('<option value="0">未設定</option>');
			$("#review_id").html('<option value="0">未設定</option>');
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
			$("#review_id").html('<option value="0">未設定</option>');
		}
	});
}
function load_review(item_id, review_id)
{
	$.ajax({
		url: 'ajax.php?type=review&item_id='+item_id+'&review_id='+review_id,
		type: 'GET',
		cache: false,
		success: function(ret){
			$("#review_id").html(ret);
		}
	});
}
</script>
</body>
</html>
