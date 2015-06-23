<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class ReviewModel extends BaseModel
	{
		public function __construct()
		{
			parent::set_table_name('review');
		}
		
		public function getReviewAllCount()
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM review");
			return $pdo->fetchColumn();
		}
		public function getReviewAllCountByFilter($category_id)
		{
			if($category_id==0){
				return $this->getReviewAllCount();
			}
			
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM review LEFT JOIN item ON review.item_id=item.item_id WHERE item.category_id=?", array($category_id));
			return $pdo->fetchColumn();
		}
		
		public function getReviewCount($item_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT COUNT(*) FROM review WHERE item_id=? AND enable=1", array($item_id));
			return $pdo->fetchColumn();
		}
		
		public function getReviewAll($p, $page_count)
		{
			$pdo = PdoInterface::getInstance();
				
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
				
			$pdo->query("SELECT review.*, 
					division.division_id AS division_id, division.name AS division_name, 
					category.category_id AS category_id, category.name AS category_name, 
					item.item_id AS item_id, item.name AS item_name
					FROM review
					LEFT JOIN item     ON review.item_id = item.item_id
					LEFT JOIN category ON item.category_id = category.category_id
					LEFT JOIN division ON item.division_id = division.division_id
					ORDER BY review.review_id DESC {$limit}");
			
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
		
			$pdo->query("SELECT review.*,
				division.division_id AS division_id, division.name AS division_name,
				category.category_id AS category_id, category.name AS category_name,
				item.item_id AS item_id, item.name AS item_name
				FROM review
				LEFT JOIN item     ON review.item_id = item.item_id
				LEFT JOIN category ON item.category_id = category.category_id
				LEFT JOIN division ON item.division_id = division.division_id
				WHERE category.category_id=?
				ORDER BY review.review_id DESC {$limit}", array($category_id));
				
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			return $result;
		}
		
		public function getReview($review_id)
		{
			$ret = parent::select(array('review_id'=>$review_id));
			if(empty($ret)){
				return false;
			}
				
			return $ret[0];
		}
		
		public function insertReview($data)
		{
			$data['review_id'] = BaseModel::IS_NULL;
		
			parent::insert($data);
		}
		
		public function updateReview($review_id, $data)
		{
			parent::update($data, array('review_id'=>$review_id));
		}
		
		//bush.phpからクチコミの登録
		public function setReview($review_data)
		{
			//review_idはauto_incrementなのでNULLを指定
			$review_data['review_id'] = BaseModel::IS_NULL;
			$review_data['update_time'] = BaseModel::NOW;
			$review_data['create_time'] = BaseModel::NOW;
			
			parent::insert($review_data);
		}
		
		public function getReviewAllByItemId($item_id)
		{
			return parent::select(array('item_id'=>$item_id, 'enable'=>1), array('pickup'=>BaseModel::ORDER_DESC, 'update_time'=>BaseModel::ORDER_DESC));
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
		
		//表情イメージファイル名の取得
		public static function getFaceImage($image)
		{
			switch($image)
			{
				case '5': return 'icon-happy-small.png';
				case '4': return 'icon-normal-small.png';
				case '3': return 'icon-comlaint-small.png';
				case '2': return 'icon-cry-small.png';
				case '1': return 'icon-angry-small.png';
			}
			
			return '';
		}
	}