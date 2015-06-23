<?php 
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	
	$item = $item_model->getItem(@$item_id);
	
	$result = $category_model->select(null, array('update_time'=>BaseModel::ORDER_DESC));
	
	//表示アイテムのカテゴリを一番上にする
	$top = array();
	$other = array();
	foreach($result as $v)
	{
		//カテゴリ内のアイテムを列挙する
		$v['item_all'] = $item_model->getItemAllByCategoryId($v['category_id']);
		
		//一番上のカテゴリとそれ以外を分離
		if($v['category_id']==@$item['category_id']){
			$top[] = $v;
		}else{
			$other[ $v['category_id'] ] = $v;
		}
	}
	
	$_other=array();
	if(!empty($top))
	{
		for($i=1;$i<=CategoryModel::SIDE_CATEGORY_SIZE;++$i)
		{
			$cat = $top[0]['category_id_'.$i];
			if(!empty($other[$cat])){
				$_other[] = $other[$cat];
			}
		}
	}
	
	//分離したカテゴリたちをマージ
	$category_all = array_merge($top, $_other);
	
	function getRankClass($rank)
	{
		switch($rank){
			case '1': return 'One';
			case '2': return 'Two';
			case '3': return 'Three';
		}
		return 'Other';
	}
?><aside>
<div id="Side">



<?php foreach($category_all as $v){ ?>
<article>
<div class="Block Green">
<h2><a href="#"><?php echo $v['name']; ?>ランキング</a></h2>
<ul class="Ranking">
<?php foreach($v['item_all'] as $i){ ?>
<a href="reputation.php?item_id=<?php echo $i['item_id']; ?>"><li><dl><dt class="<?php echo getRankClass($i['rank']); ?>"><?php echo $i['rank']; ?>位</dt><dd><?php echo $i['name']; ?></dd></dl></li></a>
<?php } ?>
</ul>
</div>
</article>
<?php } ?>


<article>
<div class="Block Gray">
<h2><a href="#">最近の特集</a></h2>
<ul class="Special">
<a href="#"><li><dl><dt><img src="common/img/sample.png" width="236" height="178" alt=""></dt><dd>最高のヘッドスパマッサージ</dd></dl></li></a>
<a href="#"><li><dl><dt><img src="common/img/sample.png" width="236" height="178" alt=""></dt><dd>最高のヘッドスパマッサージ</dd></dl></li></a>
<a href="#"><li><dl><dt><img src="common/img/sample.png" width="236" height="178" alt=""></dt><dd>最高のヘッドスパマッサージ</dd></dl></li></a>
</ul>
</div>
</article>




<article>
<div class="Block Pink">
<h2><a href="#">最近の特集</a></h2>
<ul class="Special">
<a href="#"><li><dl><dt><img src="common/img/sample.png" width="236" height="178" alt=""></dt><dd>最高のヘッドスパマッサージ</dd></dl></li></a>
<a href="#"><li><dl><dt><img src="common/img/sample.png" width="236" height="178" alt=""></dt><dd>最高のヘッドスパマッサージ</dd></dl></li></a>
<a href="#"><li><dl><dt><img src="common/img/sample.png" width="236" height="178" alt=""></dt><dd>最高のヘッドスパマッサージ</dd></dl></li></a>
</ul>
</div>
</article>



<!-- #Side --></div>
</aside>