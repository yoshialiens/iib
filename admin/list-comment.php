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
	
	//1ページに表示する数
	$PAGE_ITEM_COUNT = 200;
	
	
	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	$review_model = new ReviewModel();
	$comment_model = new CommentModel();
	
	
	$filter = $session->get('filter');
	$category_id = (int)@$filter['category_id'];
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isset($_POST['category_id']))
		{
			$filter['category_id'] = (int)@$_POST['category_id'];
			$session->set('filter', $filter);
				
			header("location: list-comment.php");
			exit;
		}
	}
	
	if(isset($_GET['del']))
	{
		$review_id = (int)@$_GET['id'];
		$comment_model->delete(array('comment_id'=>$comment_id));
		
		header("location: list-comment.php#upd");
		exit;
	}
	
	$category_model = new CategoryModel();
	$category_all = $category_model->getCategoryAll();
	
	//ページ番号
	$page_num = @$_GET['p'];
	
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$comment_id = (int)@$_GET['id'];
		$enable = (int)@$_POST['enable'];
		$comment_model->update(array('enable'=>$enable), array('comment_id'=>$comment_id));
		
		header("location: list-comment.php?p=".$page_num."#upd");
		exit;
	}
	
	
	$comment_count = $comment_model->getCommentAllCountByFilter($category_id);
	
	//最大ページ数
	$page_max = ceil($comment_count / $PAGE_ITEM_COUNT);
	
	$comment_all = $comment_model->getCommentAllByFilter($category_id, $page_num, $PAGE_ITEM_COUNT);
	foreach($comment_all as &$v)
	{
		$review = $review_model->getReview($v['review_id']);
		$item = $item_model->getItem($review['item_id']);
		$division = $division_model->getDivision($item['division_id']);
		$category = $category_model->getCategory($item['category_id']);
		
		
		$v['division'] = array('id'=>$division['division_id'], 'name'=>$division['name']);
		$v['category'] = array('id'=>$category['category_id'], 'name'=>$category['name']);
		$v['item'] = array('id'=>$item['item_id'], 'name'=>$item['name']);
		$v['review'] = array('id'=>$review['review_id'], 'title'=>$review['title']);
		
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		$v['comment'] = htmlspecialchars($v['comment'], ENT_QUOTES, 'UTF-8');
		unset($v);
	}
	
?><!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>コメント一覧</title>

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

<h2>コメント一覧</h2>

<p class="EntBtn"><a href="reg-comment.php">コメントの登録 &gt;</a></p>

<table class="List">
<tr>
<th class="Date">ジャンル</th>
<td>
<form method="POST" action="list-comment.php" id="frmFilter">
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
<li><a href="comment-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="comment-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>


<table class="List FC11">
<tr>
<th class="Date"><a href="#">登録/更新日</a></th>
<th class="MidName">大カテゴリー名</th>
<th class="BigName">ジャンル名</th>
<th class="MidName">アイテム名（商品/企業）名</th>
<th class="MidName">口コミタイトル</th>
<th>表示</th>
<th class="MidName">投稿者名</th>
<th class="btArea">編集</th>
<th class="btArea">削除</th>
</tr>

<?php foreach($comment_all as $v){ ?>
<tr>
<td><?php echo $v['update_time']; ?></td>
<td><a class="list-link" href="reg-division.php?id=<?php echo $v['division']['id']; ?>"><?php echo $v['division']['name']; ?></a></td>
<td><a class="list-link" href="reg-category.php?id=<?php echo $v['category']['id']; ?>"><?php echo $v['category']['name']; ?></a></td>
<td><a class="list-link" href="reg-item.php?id=<?php echo $v['item']['id']; ?>"><?php echo $v['item']['name']; ?></a></td>
<td><a class="list-link" href="reg-review.php?id=<?php echo $v['review']['id']; ?>"><?php echo $v['review']['title']; ?></a></td>
<td>
<form method="post" action="list-comment.php?id=<?php echo $v['comment_id']; ?>&p=<?php echo $page_num; ?>">
<select name="enable" class="Small">
<option value="0" <?php if($v['enable']==0)echo 'selected="selected"';?>>0:無効</option>
<option value="1" <?php if($v['enable']==1)echo 'selected="selected"';?>>1:有効</option>
</select>
<input type="submit" value="更新">
</form>
</td>
<td><?php echo $v['name']; ?></td>
<td><p class="btn"><a href="reg-comment.php?id=<?php echo $v['comment_id']; ?>">編集</a></p></td>
<td><p class="btn"><a href="javascript:onDelete(<?php echo $v['comment_id']; ?>);">削除</a></p></td>
</tr>
<?php } ?>

</table>

<ul class="paginate pag4 clearfix">
<?php for($p=0;$p<$page_max;++$p){ ?>
<?php if($p==$page_num){ ?>
<li><a href="comment-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
<?php }else{ ?>
<li><a href="comment-item.php?p=<?php echo $p; ?>"><?php echo $p+1; ?></a></li>
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
	location.href = 'list-comment.php?id='+id+"&del";
}
</script>
</body>
</html>
