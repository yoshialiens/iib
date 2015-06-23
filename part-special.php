<?php 
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/RankingModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/AttentionModel.class.php';
	
	
	$attention_model = new AttentionModel();
	$attention_all = $attention_model->getAttention();
	$attention=array();
	if(!empty($attention_all))
	{
		shuffle($attention_all);
		$attention=$attention_all[0];
		if((int)$attention['special_archive_id']!==0){
			$model = new SpecialModel();
			$data = $model->getAttentionData($attention['special_archive_id']);
			if(!empty($data)){
				$attention['url'] = 'special.php?archive_id='.$data['archive_id'];
				$attention['image'] = $data['image'];
				$attention['category_name'] = $data['category_name'];
			}
		}elseif((int)$attention['experience_archive_id']!==0){
			$model = new ExperienceModel();
			$data = $model->getAttentionData($attention['experience_archive_id']);
			if(!empty($data)){
				$attention['url'] = 'exp.php?archive_id='.$data['archive_id'];
				$attention['image'] = $data['image'];
				$attention['category_name'] = $data['category_name'];
			}
		}else{
			$model = new CategoryModel();
			$data = $model->getCategory($attention['category_id']);
			
			if(!empty($data)){
				$attention['url'] = 'ranking.php?category_id='.$data['category_id'];
				$attention['image'] = $data['image1'];
				$attention['category_name'] = $data['name'];
			}
		}
	}
?>
<?php if(!empty($attention)): ?>
<div class="Block">
<div class="Img"><img src="<?php echo $attention['image']; ?>" width="236" height="178" alt=""></div>
<div class="Txt">
<h2><span>注目の特集</span><?php echo $attention['title']; ?></h2>
<div class="tx">
<p><?php echo $attention['text']; ?>
<a href="<?php echo $attention['url']; ?>"><span class="Btn">もっと見る &gt;</span></a></p>
</div>
</div>
</div>
<?php endif; ?>