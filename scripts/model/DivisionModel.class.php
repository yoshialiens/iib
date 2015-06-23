<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class DivisionModel extends BaseModel
	{
		public function __construct()
		{
			parent::set_table_name('division');
		}
		
		public function getDivisionAll()
		{
			return parent::select(null, array('division_id'=>BaseModel::ORDER_DESC));
		}
		
		public function getDivision($division_id)
		{
			$ret = parent::select(array('division_id'=>$division_id));
			if(empty($ret)){
				return false;
			}
			
			return $ret[0];
		}
		
		public function insertDivision($data)
		{
			$data['division_id'] = BaseModel::IS_NULL;
			$data['update_time'] = BaseModel::NOW;
			
			parent::insert($data);
		}
		
		public function updateDivision($division_id, $data)
		{
			$data['update_time'] = BaseModel::NOW;
			
			parent::update($data, array('division_id'=>$division_id));
		}
	}