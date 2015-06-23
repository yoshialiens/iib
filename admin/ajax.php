<?php 
require_once dirname(__FILE__) . '/../scripts/Session.class.php';
require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
require_once dirname(__FILE__) . '/../scripts/model/DivisionModel.class.php';
require_once dirname(__FILE__) . '/../scripts/model/ItemModel.class.php';
require_once dirname(__FILE__) . '/../scripts/model/ReviewModel.class.php';

$session = Session::getInstance();
$admin = $session->get('admin');
if($admin === false){
	header("location: login.php");
	exit;
}

$division_model = new DivisionModel();
$category_model = new CategoryModel();
$item_model = new ItemModel();
$review_model = new ReviewModel();

if(isset($_GET['type']) && $_GET['type']=='category'){
	$division_id = (int)$_GET['division_id'];
	$category_id = (int)$_GET['category_id'];
	$category_all = $category_model->select(array('division_id'=>$division_id));
	echo '<option value="0">未設定</option>'."\n";
	foreach($category_all as $v)
	{
		$selected = '';
		if($category_id == $v['category_id'])$selected = ' selected="selected"';
		$name = htmlspecialchars(@$v['name'], ENT_QUOTES, 'UTF-8');
		echo '<option value="'.$v['category_id'].'"'.$selected.'>'.$name.'</option>'."\n";
	}
}elseif(isset($_GET['type']) && $_GET['type']=='item'){
	$category_id = (int)$_GET['category_id'];
	$item_id = (int)$_GET['item_id'];
	$item_all = $item_model->select(array('category_id'=>$category_id));
	
	if(isset($_GET['all'])){
		echo '<option value="0">全て</option>'."\n";
	}else{
		echo '<option value="0">未設定</option>'."\n";
	}
	
	foreach($item_all as $v)
	{
		$selected = '';
		if($item_id == $v['item_id'])$selected = ' selected="selected"';
		$name = htmlspecialchars(@$v['name'], ENT_QUOTES, 'UTF-8');
		echo '<option value="'.$v['item_id'].'"'.$selected.'>'.$name.'</option>'."\n";
	}
}elseif(isset($_GET['type']) && $_GET['type']=='review'){
	$item_id = (int)$_GET['item_id'];
	$review_id = (int)$_GET['review_id'];
	$review_all = $review_model->select(array('item_id'=>$item_id));
	echo '<option value="0">未設定</option>'."\n";
	foreach($review_all as $v)
	{
		$selected = '';
		if($review_id == $v['review_id'])$selected = ' selected="selected"';
		$title = htmlspecialchars(@$v['title'], ENT_QUOTES, 'UTF-8');
		echo '<option value="'.$v['review_id'].'"'.$selected.'>'.$title.'</option>'."\n";
	}
}