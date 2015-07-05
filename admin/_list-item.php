<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ItemModel.class.php';
	
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
	
	//更新時
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$filter['category_id'] = (int)@$_POST['category_id'];
		$session->set('filter', $filter);
	
		header("location: list-item.php");
		exit;
	}
	
	$item_model = new ItemModel();
	//削除時
	if(isset($_GET['del']))
	{
		$item_id = (int)@$_GET['id'];
		$item_model->delete(array('item_id'=>$item_id));
		
		header("location: list-item.php#upd");
		exit;
	}
	
	
	
	$category_model = new CategoryModel();
	$category_all = $category_model->getCategoryAll();
	
	//ページ番号
	$page_num = @$_GET['p'];
	
	$item_count = $item_model->getItemCountByFilter($category_id);
	
	//最大ページ数
	$page_max = ceil($item_count / $PAGE_ITEM_COUNT);
	
	$item_all = $item_model->getItemAllByFilter($category_id, $page_num, $PAGE_ITEM_COUNT);
	foreach($item_all as &$v)
	{
		$v['division_name'] = htmlspecialchars($v['division_name'], ENT_QUOTES, 'UTF-8');
		$v['category_name'] = htmlspecialchars($v['category_name'], ENT_QUOTES, 'UTF-8');
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>口コミ一覧</title>

<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">
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

<h2>記事登録</h2>

<p class="EntBtn"><a href="reg-item.php">記事の登録 &gt;</a></p>

<table class="List">
<tr>
<th class="Date">ジャンル</th>
<td>
<form method="POST" action="list-item.php" id="frmFilter">
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
<li><a href="list-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="list-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>


<table class="List">
<tr>
<th class="Date"><a href="#">登録日</a></th>
<th class="MidName">カテゴリ</th>
<th class="MidName">記事タイトル</th>
<th class="AuthorName">ライター</th>
<th class="item_id">item_id</th>
<th class="btArea">編集</th>
<th class="btArea">削除</th>
</tr>

<?php foreach($item_all as $v){ ?>
<tr>
<td><?php echo $v['date']; ?></td>
<td><?php echo $v['category_name']; ?></td>
<td><?php echo $v['name']; ?></td>
<td><?php echo $v['author_name']; ?></td>
<td><?php echo $v['item_id']; ?></td>
<td><p class="btn"><a href="reg-item.php?id=<?php echo $v['item_id']; ?>">編集</a></p></td>
<td><p class="btn"><a href="javascript:onDelete(<?php echo $v['item_id']; ?>);">削除</a></p></td>
</tr>
<?php } ?>

</table>

<ul class="paginate pag4 clearfix">
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="list-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="list-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
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
	location.href = 'list-item.php?id='+id+"&del";
}
</script>
</body>
</html>
