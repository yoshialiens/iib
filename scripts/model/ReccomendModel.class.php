<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class ReccomendModel extends BaseModel
	{
		const RECCOMEND_SIZE = 3;
		
		public function __construct()
		{
			parent::set_table_name('reccomend');
		}
		
		public function getReccomend($reccomend_id)
		{
			$pdo = PdoInterface::getInstance();
			$pdo->query("SELECT * FROM reccomend");
			
			$result=array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] =  $rs;
			}
			return $result;
		}

		public function insertReccomend($data)
		{
			$data['reccomend_id'] = BaseModel::IS_NULL;
			$data['update_time'] = BaseModel::NOW;
			
			parent::insert($data);
		}
		
		public function updateReccomend($reccomend_id, $data)
		{
			$data['update_time'] = BaseModel::NOW;				
			parent::update($data, array('reccomend_id'=>$reccomend_id));
		}
	}
;?>