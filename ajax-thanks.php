<?php
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	
	$review_id = (int)@$_GET['review_id'];
	
	
	//10分間は何度も押せない
	$THANKS_INTERVAL = (60 * 10);
	
	
	$review_model = new ReviewModel();
	$review = $review_model->getReview($review_id);
	if(empty($review)){
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	
	$pdo = PdoInterface::getInstance();
	$pdo->query("SELECT * FROM thanks_history WHERE review_id=? AND ip_addr=?", array($review_id, $_SERVER['REMOTE_ADDR']));
	if($rs = $pdo->fetch_assoc()){
		if((time() - strtotime($rs['update_time'])) <= $THANKS_INTERVAL){
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
	
	$time = date("Y-m-d H:i:s");
	$pdo->query("INSERT INTO thanks_history (review_id, ip_addr, update_time) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE update_time=?", array($review_id, $_SERVER['REMOTE_ADDR'], $time, $time));
	
	
	$review['btn_thanks'] += 1;
	$review_model->update(array('btn_thanks'=>$review['btn_thanks']), array('review_id'=>$review_id));
	
	echo $review['btn_thanks'];
