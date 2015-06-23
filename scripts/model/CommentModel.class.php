<?php
	require_once dirname(__FILE__) . '/../BaseModel.class.php';

	class CommentModel extends BaseModel
	{
		public function __construct()
		{
			parent::set_table_name('comment');
		}
		
		public function getCommentAll($p, $page_count)
		{
			$pdo = PdoInterface::getInstance();
			
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
			
			
			$pdo->query("SELECT * FROM comment {$limit}");
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
		
		public function getComment($comment_id)
		{
			$ret = parent::select(array('comment_id'=>$comment_id));
			if(empty($ret)){
				return false;
			}
		
			return $ret[0];
		}
		
		//コメントの登録
		public function setComment($comment_data)
		{
			//comment_idはauto_incrementなのでNULLを指定
			$comment_data['comment_id'] = BaseModel::IS_NULL;
			$comment_data['update_time'] = BaseModel::NOW;
				
			parent::insert($comment_data);
		}
		
		public function getCommentAllCountByFilter($category_id)
		{
			if($category_id==0){
				return parent::count();
			}
			
			$pdo = PdoInterface::getInstance();
			
			$pdo->query("SELECT COUNT(*) FROM comment INNER JOIN (SELECT review.review_id FROM review LEFT JOIN item ON review.item_id=item.item_id WHERE item.category_id=?) a ON comment.review_id=a.review_id", array($category_id));
			
			return $pdo->fetchColumn();
		}
		public function getCommentAllByFilter($category_id, $p, $page_count)
		{
			if($category_id==0){
				return $this->getCommentAll($p, $page_count);
			}
			
			$pdo = PdoInterface::getInstance();
			
			$p = $p*$page_count;
			$limit = "LIMIT {$p}, {$page_count}";
			
			$pdo->query("SELECT * FROM comment INNER JOIN (SELECT review.review_id FROM review LEFT JOIN item ON review.item_id=item.item_id WHERE item.category_id=?) a ON comment.review_id=a.review_id {$limit}", array($category_id));
			
			$result = array();
			while($rs = $pdo->fetch_assoc())
			{
				$result[] = $rs;
			}
			
			return $result;
		}
	}