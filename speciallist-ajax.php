<?php 
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	
	//1ページに表示するコラム数
	$PAGE_SIZE = 6;
	
	$special_model = new SpecialModel();
	
	$archive_id = (int)@$_GET['archive_id'];
	
	//ページ番号 0～
	$p = (int)@$_GET['p'];
	
	$review = $special_model->getArchive($archive_id);
	if(empty($review)){
		exit;
	}
	
	//コラム一覧の取得
	$temp = $special_model->getReviewAllByCategoryId($review['category_id']);
	$temp = array_slice($temp, $p*$PAGE_SIZE, $PAGE_SIZE);
	$special_list = array();
	foreach($temp as $v){
		if($archive_id == $v['archive_id'])continue; //表示中のコラムは除外する
		$special_list[] = $v;
	}
	
	
	//全コラム数から表示中のコラムは除く
	$exp_count = $special_model->getReviewAllCountByItemId($review['item_id']) - 1;
	
	//全ページ数
	$page_count = ceil($exp_count / $PAGE_SIZE);
	
	$navi_button_list = array();
	for($i=$p-5;$i<=$p+5;$i++)
	{
	if($i<0)continue;
	if($i>=$page_count)continue;
	
	if($p==$i){
	$navi_button_list[$i] = '';
	}else{
	$navi_button_list[$i] = 'javascript:get_speciallist('.$i.')';
		}
	}
?>


<section>
<div class="H-Line Gray">
<h2>その他の特集</h2>
</div><!-- Title Part -->


<div class="SpecialList">
<ul>

<?php foreach($special_list as $special){ ?>
<a href="special.php?archive_id=<?php echo $special['archive_id']; ?>">
<li>
<p class="Thumb"><img src="<?php echo $special['image']; ?>" width="204" height="156" alt=""></p>
<p class="Title"><?php echo htmlspecialchars($special['title'],ENT_QUOTES,'UTF-8'); ?></p>
</li>
</a>
<?php } ?>

</ul>

</div>

<div class="Pager">
<ul class="paginate pag clearfix">
<?php foreach($navi_button_list as $no => $link){ ?>
<?php if(empty($link)){ ?>
<li class="current"><?php echo $no+1; ?></li>
<?php }else{ ?>
<li><a href="<?php echo $link; ?>"><?php echo $no+1; ?></a></li>
<?php } ?>
<?php } ?>
</ul>
</div>
</section>
