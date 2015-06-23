<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class ItemModel extends BaseModel
	{
		public function __construct()
		{
			parent::set_table_name('item');
		}
		
		public function getItemCount()
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM item");
			return $pdo->fetchColumn();
		}
		public function getItemCountByFilter($category_id)
		{
			if($category_id==0){
				return $this->getItemCount();
			}
			
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM item WHERE category_id=?", array($category_id));
			return $pdo->fetchColumn();
		}
		
		public function getItemAll($p, $page_count)
		{
			$pdo = PdoInterface::getInstance();
			
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
			
			$pdo->query("SELECT item.*, division.name AS division_name, category.name AS category_name 
					FROM item
					LEFT JOIN division ON item.division_id = division.division_id
					LEFT JOIN category ON item.category_id = category.category_id
					ORDER BY item.item_id DESC {$limit}");
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		public function getItemAllByFilter($category_id, $p, $page_count)
		{
			if($category_id==0){
				return $this->getItemAll($p, $page_count);
			}
			
			$pdo = PdoInterface::getInstance();
				
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
				
			$pdo->query("SELECT item.*, division.name AS division_name, category.name AS category_name
					FROM item
					LEFT JOIN division ON item.division_id = division.division_id
					LEFT JOIN category ON item.category_id = category.category_id
					WHERE item.category_id=?
					ORDER BY item.item_id DESC {$limit}", array($category_id));
					
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		public function getItem($item_id)
		{
			$pdo = PdoInterface::getInstance();
			
			$pdo->query("SELECT item.*, division.name AS division_name, category.name AS category_name
					FROM item
					LEFT JOIN division ON item.division_id = division.division_id
					LEFT JOIN category ON item.category_id = category.category_id
					WHERE item.item_id=?", array($item_id));
			
			if($rs = $pdo->fetch_assoc())
			{
				return $rs;
			}
			
			return false;
		}
		
		public function insertItem($data)
		{
			$data['item_id'] = BaseModel::IS_NULL;
			$data['update_time'] = BaseModel::NOW;
				
			parent::insert($data);
		}
		
		public function updateItem($item_id, $data)
		{
			$data['update_time'] = BaseModel::NOW;
		
			parent::update($data, array('item_id'=>$item_id));
		}
		
		//順位を取得
		public function getRank($item_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT (SELECT COUNT(*) FROM item b WHERE a.point < b.point AND a.category_id=b.category_id) + 1 AS rank FROM item a WHERE a.item_id=?",array($item_id));
				
			if(($rs = $pdo->fetch_assoc()) === false){
				return false;
			}
			return $rs['rank'];
		}
		
		//指定したカテゴリのアイテムをランク付きで取得
		public function getItemAllByCategoryId($category_id)
		{
			
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT a.*, (SELECT COUNT(*) FROM item b WHERE a.point < b.point AND a.category_id=b.category_id) + 1 AS rank FROM item a WHERE a.category_id=? ORDER BY rank ASC",array($category_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc()){
				$result[] = $rs;
			}
			return $result;
		}

		//指定したディビジョンのアイテムをランク付きで取得
		public function getItemAllByDivisionId($division_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM item WHERE division_id=? ORDER BY item_id ASC", array($division_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			return $result;
		}
		
		//オススメ度の★タグ生成
		public static function getOsusumeStarTag($osusume)
		{
			//計算しやすいように100倍する ※小数2桁まで整数化
			$osusume = (int)($osusume * 100);
				
			$tag = "";
			for($i=0;$i<5;++$i)
			{
				$mod = $osusume - ($i*100);
				if($mod >= 100){
					$tag .= '<li class="star"><img src="common/img/star/star-full.png" width="100" height="100" alt=""></li>'."\n";
				}elseif($mod >= 75){
					$tag .= '<li class="star"><img src="common/img/star/star-75.png" width="100" height="100" alt=""></li>'."\n";
				}elseif($mod >= 50){
					$tag .= '<li class="star"><img src="common/img/star/star-half.png" width="100" height="100" alt=""></li>'."\n";
				}elseif($mod >= 25){
					$tag .= '<li class="star"><img src="common/img/star/star-25.png" width="100" height="100" alt=""></li>'."\n";
				}else{
					$tag .= '<li class="star"><img src="common/img/star/star-none.png" width="100" height="100" alt=""></li>'."\n";
				}
			}
				
			return $tag;
		}
	}