<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class AuthorModel extends BaseModel
	{
		const SIDE_AUTHOR_SIZE = 5;
		
		public function __construct()
		{
			parent::set_table_name('author');
		}
		
		public function getAuthorAll()
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("
					SELECT author.*, division.name AS division_name 
					FROM author LEFT JOIN division ON author.division_id = division.division_id
					ORDER BY author.author_id DESC");
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		public function getAuthorAllByDivisionId($division_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM author WHERE division_id=? ORDER BY sort_no ASC", array($division_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			return $result;
		}
		
		public function getAuthorCount()
		{
			$pdo = PdoInterface::getInstance();
			
			$pdo->query("
					SELECT COUNT(*)
					FROM author LEFT JOIN division ON author.division_id = division.division_id
					ORDER BY author.author_id DESC");
			return (int)$pdo->fetchColumn();
		}
		
		
		public function getAuthor($author_id)
		{
			$ret = parent::select(array('author_id'=>$author_id));
			if(empty($ret)){
				return false;
			}
			
			return $ret[0];
		}
		
		public function insertAuthor($data)
		{
			$data['author_id'] = BaseModel::IS_NULL;
			$data['update_time'] = BaseModel::NOW;
			
			parent::insert($data);
		}
		
		public function updateAuthor($author_id, $data)
		{
			$data['update_time'] = BaseModel::NOW;
			parent::update($data, array('Author_id'=>$author_id));
		}
	}
;?>