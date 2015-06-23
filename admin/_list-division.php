<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
	
	$session = Session::getInstance();
	$admin = $session->get('admin');
	if($admin === false){
		header("location: login.php");
		exit;
	}
	
	//1ページに表示する数
	$PAGE_ITEM_COUNT = 20;
	
	$division_model = new DivisionModel();
	
	if(isset($_GET['del']))
	{
		$division_id = (int)@$_GET['id'];
		$division_model->delete(array('division_id'=>$division_id));
		
		header("location: list-division.php#upd");
		exit;
	}
	
	$division_all = $division_model->getDivisionAll();
	
	//ページ番号
	$page_num = @$_GET['p'];
	//最大ページ数
	$page_max = ceil(count($division_all) / $PAGE_ITEM_COUNT);
	
	$division_all = array_slice($division_all, $page_num*$PAGE_ITEM_COUNT, $PAGE_ITEM_COUNT);
	foreach($division_all as &$v)
	{
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
<title>大カテゴリー一覧</title>

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
<h2>大カテゴリー一覧</h2>

<p class="EntBtn"><a href="reg-division.php">大カテゴリーの登録 &gt;</a></p>

<ul class="paginate pag4 clearfix">
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="list-division.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="list-division.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>



<table class="List">
<tr>
<th class="Date"><a href="#">登録/更新日</a></th>
<th class="Name">大カテゴリー名</th>
<th>division_id</th>
<th class="btArea">編集</th>
<th class="btArea">削除</th>
</tr>

<?php foreach($division_all as $v){ ?>
<tr>
<td><?php echo $v['update_time']; ?></td>
<td><?php echo $v['name']; ?></td>
<td><?php echo $v['division_id']; ?></td>
<td><p class="btn"><a href="reg-division.php?id=<?php echo $v['division_id']; ?>">編集</a></p></td>
<td><p class="btn"><a href="javascript:onDelete(<?php echo $v['division_id']; ?>);">削除</a></p></td>
</tr>
<?php } ?>


</table>

<ul class="paginate pag4 clearfix">
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="list-division.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="list-division.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
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
	location.href = 'list-division.php?id='+id+"&del";
}
</script>
</body>
</html>
