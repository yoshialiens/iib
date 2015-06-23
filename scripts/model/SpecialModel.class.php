<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class SpecialModel extends BaseModel
	{
		public function __construct()
		{
			parent::set_table_name('special');
		}
		
		public function getReviewAllCount()
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM special");
			return $pdo->fetchColumn();
		}
		public function getReviewAllCountByFilter($category_id)
		{
			if($category_id==0){
				return $this->getReviewAllCount();
			}
			
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM special LEFT JOIN item ON special.item_id=item.item_id WHERE item.category_id=?", array($category_id));
			return $pdo->fetchColumn();
		}
		public function getReviewAllCountByItemId($item_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM special WHERE item_id=?", array($item_id));
			return $pdo->fetchColumn();
		}
		
		public function getReviewCount($item_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM special WHERE item_id=? AND enable=1", array($item_id));
			return $pdo->fetchColumn();
		}
		
		public function getReviewAll($p, $page_count)
		{
			$pdo = PdoInterface::getInstance();
				
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
				
			$pdo->query("SELECT special.*, 
					division.division_id AS division_id, division.name AS division_name, 
					category.category_id AS category_id, category.name AS category_name, 
					item.item_id AS item_id, item.name AS item_name
					FROM special
					LEFT JOIN item     ON special.item_id = item.item_id
					LEFT JOIN category ON special.category_id = category.category_id
					LEFT JOIN division ON special.division_id = division.division_id
					ORDER BY special.archive_id DESC {$limit}");
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
				
			return $result;
		}
		public function getReviewAllByFilter($category_id, $p, $page_count)
		{
			if($category_id==0){
				return $this->getReviewAll($p, $page_count);
			}
			$pdo = PdoInterface::getInstance();
		
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
		
			$pdo->query("SELECT special.*,
					division.division_id AS division_id, division.name AS division_name,
					category.category_id AS category_id, category.name AS category_name,
					item.item_id AS item_id, item.name AS item_name
					FROM special
					LEFT JOIN item     ON special.item_id = item.item_id
					LEFT JOIN category ON special.category_id = category.category_id
					LEFT JOIN division ON special.division_id = division.division_id
					WHERE category.category_id=?
					ORDER BY special.archive_id DESC {$limit}", array($category_id));
				
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			return $result;
		}
		
		public function getArchive($archive_id)
		{
			$ret = parent::select(array('archive_id'=>$archive_id));
			if(empty($ret)){
				return false;
			}
				
			return $ret[0];
		}
		public function getAttentionData($archive_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT special.*,
					division.division_id AS division_id, division.name AS division_name,
					category.category_id AS category_id, category.name AS category_name,
					item.item_id AS item_id, item.name AS item_name
					FROM special
					LEFT JOIN item     ON special.item_id = item.item_id
					LEFT JOIN category ON special.category_id = category.category_id
					LEFT JOIN division ON special.division_id = division.division_id
					WHERE special.archive_id=? ", array($archive_id));
				
			return $pdo->fetch_assoc();
		}
		
		public function insertReview($data)
		{
			$data['archive_id'] = BaseModel::IS_NULL;
		
			parent::insert($data);
		}
		
		public function updateReview($archive_id, $data)
		{
			parent::update($data, array('archive_id'=>$archive_id));
		}
		
		
		public function getReviewAllByItemId($item_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM special WHERE (item_id=? OR item_id=0) AND disp=1 ORDER BY update_time DESC", array($item_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		public function getReviewAllByCategoryId_ItemId($category_id, $item_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM special WHERE category_id=? AND (item_id=? OR item_id=0) AND disp=1 ORDER BY update_time DESC", array($category_id, $item_id));
				
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
				
			return $result;
		}
		
		public function getReviewAllByCategoryId($category_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM experience WHERE category_id=? AND disp=1 ORDER BY update_time DESC", array($category_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		//指定したアイテムの全クチコミを取得
		public function getItemReview($item_data)
		{
			return array();
			
			$item_id = $item_data['item_id'];
			$item_name = $item_data['name'];
				
			$pdo = PdoInterface::getInstance();
				
			//situation一覧を取得
			$pdo->query("SELECT situation_id,name FROM situation");
			$situation_all = array();
			while($rs = $pdo->fetch_assoc()){
				$situation_all[ $rs['situation_id'] ] = $rs['name'];
			}
				
			//会社名を含めたクチコミ一覧を取得
			$pdo->query("SELECT * FROM special WHERE enable=1 ORDER BY archive_id DESC");
				
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				//指定した会社のクチコミ
				if($rs['item_id'] == $item_id){
					$result[] = $rs;
					continue;
				}
		
				//situationの会社名と一致したクチコミ
				for($i = 1; $i <= 3; ++$i)
				{
					$situation_id = $rs['type'.$i];
					if($situation_id==0)continue;
					if($item_name == $situation_all[$situation_id]){
						$result[] = $rs;
					}
				}
			}
				
			return $result;
		}
	}