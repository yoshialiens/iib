<?php 
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	
	//1ページに表示するコラム数
	$PAGE_SIZE = 6;
	
	$experience_model = new ExperienceModel();
	
	$archive_id = (int)@$_GET['archive_id'];
	
	//ページ番号 0～
	$p = (int)@$_GET['p'];
	
	$review = $experience_model->getReview($archive_id);
	if(empty($review)){
		exit;
	}
	
	//コラム一覧の取得
	$temp = $experience_model->getReviewAllByCategoryId($review['category_id']);
	$temp = array_slice($temp, $p*$PAGE_SIZE, $PAGE_SIZE);
	$exp_list = array();
	foreach($temp as $v){
		if($archive_id == $v['archive_id'])continue; //表示中のコラムは除外する
		$exp_list[] = $v;
	}
	
	//全コラム数から表示中のコラムは除く
	$exp_count = $experience_model->getReviewAllCountByItemId($review['item_id']) - 1;
	
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
			$navi_button_list[$i] = 'javascript:get_explist('.$i.')';
		}
	}
?>

<h2 class="Title-Pink">その他の体験談</h2>
<div class="ExpList">
<ul>

<?php foreach($exp_list as $col){ ?>
<a href="exp.php?archive_id=<?php echo $col['archive_id']; ?>">
<li>
<p class="Thumb"><img src="<?php echo $col['photo1']; ?>" width="204" height="156" alt=""></p>
<p class="Title"><?php echo htmlspecialchars($col['title'],ENT_QUOTES,'UTF-8'); ?></p>
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
