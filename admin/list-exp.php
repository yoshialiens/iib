<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	
	$session = Session::getInstance();
	$admin = $session->get('admin');
	if($admin === false){
		header("location: login.php");
		exit;
	}
	
	//1ページに表示する数
	$PAGE_ITEM_COUNT = 200;
	
	
	$filter = $session->get('filter');
	$category_id = (int)@$filter['category_id'];
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isset($_POST['category_id']))
		{
			$filter['category_id'] = (int)@$_POST['category_id'];
			$session->set('filter', $filter);
	
			header("location: list-exp.php");
			exit;
		}
	}
	
	$experience_model = new ExperienceModel();
	
	if(isset($_GET['del']))
	{
		$archive_id = (int)@$_GET['id'];
		$experience_model->delete(array('archive_id'=>$archive_id));
		
		header("location: list-exp.php#upd");
		exit;
	}
	
	//ページ番号
	$page_num = @$_GET['p'];
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$archive_id = (int)@$_GET['id'];
		$disp = (int)@$_POST['disp'];
		$experience_model->update(array('disp'=>$disp), array('archive_id'=>$archive_id));
		
		header("location: list-exp.php?p=".$page_num."#upd");
		exit;
	}
	
	
	$category_model = new CategoryModel();
	$category_all = $category_model->getCategoryAll();
	
	$review_count = $experience_model->getReviewAllCountByFilter($category_id);
	
	//最大ページ数
	$page_max = ceil($review_count / $PAGE_ITEM_COUNT);
	
	$review_all = $experience_model->getReviewAllByFilter($category_id, $page_num, $PAGE_ITEM_COUNT);
	foreach($review_all as &$v)
	{
		if(empty($v['item_name']))$v['item_name']='全て';
		
		$v['division_name'] = htmlspecialchars($v['division_name'], ENT_QUOTES, 'UTF-8');
		$v['category_name'] = htmlspecialchars($v['category_name'], ENT_QUOTES, 'UTF-8');
		$v['item_name'] = htmlspecialchars($v['item_name'], ENT_QUOTES, 'UTF-8');
		$v['title'] = htmlspecialchars($v['title'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
?><!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>体験談一覧</title>

<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">
<style>
.list-link { color:blue; }
</style>
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

<h2>体験談一覧</h2>

<p class="EntBtn"><a href="reg-exp.php">体験談の登録 &gt;</a></p>

<table class="List">
<tr>
<th class="Date">ジャンル</th>
<td>
<form method="POST" action="list-exp.php" id="frmFilter">
<select name="category_id">
<option value="">全て</option>
<?php foreach($category_all as $v): ?>
<option value="<?php echo $v['category_id'];?>" <?php if($category_id==$v['category_id'])echo 'selected="selected"'; ?>><?php echo $v['name'];?></option>
<?php endforeach;?>
</select>
<p class="btn" style="display:inline-block;width:100px;"><a href="#" onclick="document.getElementById('frmFilter').submit();return false;">絞り込み</a></p>
</form>
</td>
</tr>
</table>

<ul class="paginate pag4 clearfix">
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="list-exp.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="list-exp.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>


<table class="List FC12">
<tr>
<th class="Date"><a href="#">登録/更新日</a></th>
<th class="BigName">大カテゴリー名</th>
<th class="MidName">ジャンル名</th>
<th class="MidName">アイテム名（商品/企業）名</th>
<th class="MidName">タイトル</th>
<th class="MidName">exp_id</th>
<th class="MidName">表示</th>
<th class="btArea">編集</th>
<th class="btArea">削除</th>
</tr>

<?php foreach($review_all as $v){ ?>
<tr>
<td><?php echo $v['update_time']; ?></td>
<td><a class="list-link" href="reg-division.php?id=<?php echo $v['division_id']; ?>"><?php echo $v['division_name']; ?></a></td>
<td><a class="list-link" href="reg-category.php?id=<?php echo $v['category_id']; ?>"><?php echo $v['category_name']; ?></a></td>
<td>
<?php if(empty($v['item_id'])): ?>
<?php echo $v['item_name']; ?>
<?php else: ?>
<a class="list-link" href="reg-item.php?id=<?php echo $v['item_id']; ?>"><?php echo $v['item_name']; ?></a>
<?php endif; ?>
</td>
<td><?php echo $v['title']; ?></td>
<td><?php echo $v['archive_id']; ?></td>
<td>
<form method="post" action="list-exp.php?id=<?php echo $v['archive_id']; ?>&p=<?php echo $page_num; ?>">
<select name="disp" class="Small">
<option value="0" <?php if($v['disp']==0)echo 'selected="selected"';?>>0:無効</option>
<option value="1" <?php if($v['disp']==1)echo 'selected="selected"';?>>1:有効</option>
</select>
<input type="submit" value="更新">
</form>
</td>
<td><p class="btn"><a href="reg-exp.php?id=<?php echo $v['archive_id']; ?>">編集</a></p></td>
<td><p class="btn"><a href="javascript:onDelete(<?php echo $v['archive_id']; ?>);">削除</a></p></td>
</tr>
<?php } ?>

</table>

<ul class="paginate pag4 clearfix">
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="list-exp.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="list-exp.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>


</div>



</div>

</div>
<script>
function onDelete(id)
{
	if(!confirm('本当に削除しますか？')){
		return;
	}
	location.href = 'list-exp.php?id='+id+"&del";
}
</script>
</body>
</html>
