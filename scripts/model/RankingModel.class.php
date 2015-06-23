<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class RankingModel extends BaseModel
	{
		const RANKING_SIZE = 5;
		
		const TYPE_CATEGORY = 0;
		const TYPE_EXP_SP = 1;
		
		public function __construct()
		{
			parent::set_table_name('ranking');
		}
		
		public function getRanking($type)
		{
			$pdo = PdoInterface::getInstance();
			
			switch($type)
			{
				case RankingModel::TYPE_CATEGORY:
					$pdo->query("SELECT rank_no, info, category_id AS id FROM ranking WHERE experience_archive_id=0 AND special_archive_id=0 ORDER BY rank_no ASC");
					break;
				case RankingModel::TYPE_EXP_SP:
					$pdo->query("SELECT rank_no, info, experience_archive_id, special_archive_id FROM ranking WHERE category_id=0 ORDER BY rank_no ASC");
					break;
				default:
					return array();
			}
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[ $rs['rank_no'] ] = $rs;
			}
			return $result;
		}
	}