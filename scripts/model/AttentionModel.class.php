<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class AttentionModel extends BaseModel
	{
		const ATTENTION_SIZE = 3;
		
		public function __construct()
		{
			parent::set_table_name('attention');
		}
		
		public function getRanking($type)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM attention");
			
			if($rs = $pdo->fetch_assoc()){
				
				$url = 'exp.php?archive_id=';
				$id = (int)$rs['experience_archive_id'];
				if($id==0){
					$url = 'special.php?archive_id=';
					$id = (int)$rs['special_archive_id'];
				}
				
				return array(
					'url' => $url.$id,
					'title' => $rs['title'],
					'text' => $rs['text'],
				);
			}
			return array();
		}
		
		public function getAttention()
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM attention");
			
			$result=array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] =  $rs;
			}
			return $result;
		}
	}