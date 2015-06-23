<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class CategoryModel extends BaseModel
	{
		const SIDE_CATEGORY_SIZE = 5;
		
		public function __construct()
		{
			parent::set_table_name('category');
		}
		
		public function getCategoryAll()
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("
					SELECT category.*, division.name AS division_name 
					FROM category LEFT JOIN division ON category.division_id = division.division_id
					ORDER BY category.category_id DESC");
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		public function getCategoryAllByDivisionId($division_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM category WHERE division_id=? ORDER BY sort_no ASC", array($division_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			return $result;
		}
		
		public function getCategoryCount()
		{
			$pdo = PdoInterface::getInstance();
			
			$pdo->query("
					SELECT COUNT(*)
					FROM category LEFT JOIN division ON category.division_id = division.division_id
					ORDER BY category.category_id DESC");
			return (int)$pdo->fetchColumn();
		}
		
		
		public function getCategory($category_id)
		{
			$ret = parent::select(array('category_id'=>$category_id));
			if(empty($ret)){
				return false;
			}
			
			return $ret[0];
		}
		
		public function insertCategory($data)
		{
			$data['category_id'] = BaseModel::IS_NULL;
			$data['update_time'] = BaseModel::NOW;
			
			parent::insert($data);
		}
		
		public function updateCategory($category_id, $data)
		{
			$data['update_time'] = BaseModel::NOW;
				
			parent::update($data, array('category_id'=>$category_id));
		}
	}