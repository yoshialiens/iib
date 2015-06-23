<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/SpecialModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/RankingModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/AttentionModel.class.php';
	
	$session = Session::getInstance();
	$admin = $session->get('admin');
	if($admin === false){
		header("location: login.php");
		exit;
	}
	
	
	$category_model = new CategoryModel();
	$ranking_model = new RankingModel();
	$experience_model = new ExperienceModel();
	$special_model = new SpecialModel();
	$attention_model = new AttentionModel();
	
	if(isset($_GET['reset'])){
		$attention_model->delete();
		header("location: top-list.php");
		exit;
	}
	
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$ranking_model->delete();
		for($i=1;$i<=RankingModel::RANKING_SIZE;++$i)
		{
			$category_id = (int)@$_POST['category_id_'.$i];
			if($category_id!=0){
				$ranking_model->insert(array(
					'rank_no'=>$i,
					'category_id'=>$category_id,
					'experience_archive_id'=>0,
					'special_archive_id'=>0,
					'info'=>'',
				));
			}
			
			$archive_id = (int)@$_POST['archive_id_'.$i];
			if($archive_id!=0){
				$exp_sp = (int)@$_POST['exp_sp_'.$i];
				$info = @$_POST['info_'.$i];
				if($exp_sp==0){
					$ranking_model->insert(array(
						'rank_no'=>$i,
						'category_id'=>0,
						'experience_archive_id'=>$archive_id,
						'special_archive_id'=>0,
						'info'=>$info,
					));
				}else{
					$ranking_model->insert(array(
						'rank_no'=>$i,
						'category_id'=>0,
						'experience_archive_id'=>0,
						'special_archive_id'=>$archive_id,
						'info'=>$info,
					));
				}
			}
		}
		
		$attention_model->delete();
		for($i=1;$i<=AttentionModel::ATTENTION_SIZE;++$i)
		{
			$attention_type = (int)@$_POST['attention_type_'.$i];
			$attention_archive_id = (int)@$_POST['attention_archive_id_'.$i];
			$attention_title = @$_POST['attention_title_'.$i];
			$attention_text = @$_POST['attention_text_'.$i];
			
			$category_id = $experience_archive_id = $special_archive_id = 0;
			if($attention_type==0){
				$experience_archive_id = $attention_archive_id;
			}elseif($attention_type==1){
				$special_archive_id = $attention_archive_id;
			}else{
				$category_id = $attention_archive_id;
			}
			if(empty($experience_archive_id) && empty($special_archive_id) && empty($category_id)){
				continue;
			}
			
			$attention_model->insert(array(
				'experience_archive_id'=>$experience_archive_id,
				'special_archive_id'=>$special_archive_id,
				'category_id'=>$category_id,
				'title'=>$attention_title,
				'text'=>$attention_text,
			));
		}
		
		header("location: top-list.php");
		exit;
	}
	
	
	$category_all = $category_model->getCategoryAll();
	$category_all = array_reverse($category_all);
	$category_ranking = $ranking_model->getRanking(RankingModel::TYPE_CATEGORY);
	$exp_sp_ranking = $ranking_model->getRanking(RankingModel::TYPE_EXP_SP);
	
	for($i=1;$i<=RankingModel::RANKING_SIZE;++$i)
	{
		if(isset($category_ranking[$i])){
			$category = $category_ranking[$i];
			$category_ranking[$i] = $category_model->getCategory($category['id']);
		}else{
			$category_ranking[$i] = array('category_id'=>0);
		}
		
		
		if(isset($exp_sp_ranking[$i])){
			$exp_sp = $exp_sp_ranking[$i];
			
			if($exp_sp['experience_archive_id']!=0){
				$archive = $experience_model->getReview($exp_sp['experience_archive_id']);
				if(!empty($archive)){
					$exp_sp_ranking[$i]['experience_archive_id'] = $archive['archive_id'];
				} 
			}else{
				$archive = $special_model->getArchive($exp_sp['special_archive_id']);
				if(!empty($archive)){
					$exp_sp_ranking[$i]['special_archive_id'] = $archive['archive_id'];
				}
			}
		}else{
			$exp_sp_ranking[$i] = array('experience_archive_id'=>0, 'special_archive_id'=>0, 'info'=>'');
		}
		if($exp_sp_ranking[$i]['special_archive_id']!=0){
			$exp_sp_ranking[$i]['archive_id']=$exp_sp_ranking[$i]['special_archive_id'];
			$exp_sp_ranking[$i]['info']=$exp_sp_ranking[$i]['info'];
			$exp_sp_ranking[$i]['exp']='';
			$exp_sp_ranking[$i]['sp']='checked="checked"';
		}else{
			$exp_sp_ranking[$i]['archive_id']=$exp_sp_ranking[$i]['experience_archive_id'];
			$exp_sp_ranking[$i]['info']=$exp_sp_ranking[$i]['info'];
			$exp_sp_ranking[$i]['exp']='checked="checked"';
			$exp_sp_ranking[$i]['sp']='';
		}
	}
	
	$attention_all = $attention_model->getAttention();
	$attention_all = array_reverse($attention_all);
	$attention=array();
	for($i=1;$i<=AttentionModel::ATTENTION_SIZE;++$i)
	{
		$attention['title_'.$i] = @$attention_all[$i-1]['title'];
		$attention['text_'.$i] = @$attention_all[$i-1]['text'];
		
		if((int)@$attention_all[$i-1]['category_id']!==0){
			$attention['type_0_'.$i] = '';
			$attention['type_1_'.$i] = '';
			$attention['type_2_'.$i] = 'checked="checked"';
			$attention['archive_id_'.$i] = (int)@$attention_all[$i-1]['category_id'];
		}elseif((int)@$attention_all[$i-1]['special_archive_id']!==0){
			$attention['type_0_'.$i] = '';
			$attention['type_1_'.$i] = 'checked="checked"';
			$attention['type_2_'.$i] = '';
			$attention['archive_id_'.$i] = (int)@$attention_all[$i-1]['special_archive_id'];
		}else{
			$attention['type_0_'.$i] = 'checked="checked"';
			$attention['type_1_'.$i] = '';
			$attention['type_2_'.$i] = '';
			$attention['archive_id_'.$i] = (int)@$attention_all[$i-1]['experience_archive_id'];
		}
	}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>アクセスランキング</title>

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

<h2>アクセスランキング</h2>


<form method="POST" action="top-list.php" id="frmReg">

<table class="List">
<tr>
<th>No</th><th>注目ジャンル</th>
</tr>
<?php for($i=1;$i<=RankingModel::RANKING_SIZE;++$i): ?>
<tr>
<td style="text-align:center;"><?php echo $i; ?></td>
<td>
<p>
<select name="category_id_<?php echo $i; ?>">
<option value="0">未選択</option>
<?php foreach($category_all as $v): ?>
<option value="<?php echo $v['category_id']; ?>" <?php if($category_ranking[$i]['category_id']==$v['category_id'])echo 'selected="selected"'; ?> ><?php echo $v['name']; ?></option>
<?php endforeach; ?>
</select>
</p>
</td></tr>
<?php endfor; ?>
</table>


<table class="List">
<tr>
<th>No</th><th>特集＆体験談</th>
</tr>
<?php for($i=1;$i<=RankingModel::RANKING_SIZE;++$i): ?>
<tr>
<td style="text-align:center;"><?php echo $i; ?></td>
<td>
<p><input type="radio" name="exp_sp_<?php echo $i; ?>" id="exp_<?php echo $i; ?>" value="0" <?php echo $exp_sp_ranking[$i]['exp'] ?> style="display:inline;" /><label for="exp_<?php echo $i; ?>">体験談</label></p>
<p><input type="radio" name="exp_sp_<?php echo $i; ?>" id="sp_<?php echo $i; ?>" value="1" <?php echo $exp_sp_ranking[$i]['sp'] ?> style="display:inline;" /><label for="sp_<?php echo $i; ?>">特集</label></p>
<p>ID：<input type="text" name="archive_id_<?php echo $i; ?>" value="<?php echo $exp_sp_ranking[$i]['archive_id']; ?>" /></p>
<p><textarea class="S02" name="info_<?php echo $i; ?>"><?php echo htmlspecialchars(@$exp_sp_ranking[$i]['info'], ENT_QUOTES, 'UTF-8'); ?></textarea></p>
</td></tr>
<?php endfor; ?>
</table>


<table class="List">
<tr><th></th><th>注目の特集</th></tr>
<?php for($i=1;$i<=AttentionModel::ATTENTION_SIZE;++$i): ?>
<tr>
<th style="text-align:center;">IDの選択</th>
<td>
<p><input type="radio" name="attention_type_<?php echo $i; ?>" id="attention_0_<?php echo $i; ?>" value="0" <?php echo @$attention['type_0_'.$i] ?> style="display:inline;" /><label for="attention_0_<?php echo $i; ?>">体験談</label></p>
<p><input type="radio" name="attention_type_<?php echo $i; ?>" id="attention_1_<?php echo $i; ?>" value="1" <?php echo @$attention['type_1_'.$i] ?> style="display:inline;" /><label for="attention_1_<?php echo $i; ?>">特集</label></p>
<p><input type="radio" name="attention_type_<?php echo $i; ?>" id="attention_2_<?php echo $i; ?>" value="2" <?php echo @$attention['type_2_'.$i] ?> style="display:inline;" /><label for="attention_2_<?php echo $i; ?>">ジャンル</label></p>
<p><input type="text" name="attention_archive_id_<?php echo $i; ?>" value="<?php echo @$attention['archive_id_'.$i]; ?>" /></p>
</td>
</tr>
<tr>
<th style="text-align:center;">タイトル入力</th>
<td><input type="text" name="attention_title_<?php echo $i; ?>" value="<?php echo @$attention['title_'.$i]; ?>" /></td>
</tr>
<tr>
<th style="text-align:center;">テキスト入力</th>
<td><textarea class="S02" name="attention_text_<?php echo $i; ?>"><?php echo htmlspecialchars(@$attention['text_'.$i], ENT_QUOTES, 'UTF-8'); ?></textarea></td>
</tr>
<?php endfor; ?>
</table>

<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a>　<a href="javascript:onReset();">リセット</a></p>
</form>

</div>




</div>

</div>
<script>
function onReset()
{
	if(!confirm('本当にリセットしますか？')){
		return;
	}
	location.href='top-list.php?reset';
}
</script>
</body>
</html>
