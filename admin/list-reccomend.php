<?php
	require_once dirname(__FILE__) . '/../scripts/Session.class.php';
	//require_once dirname(__FILE__) . '/../scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/../scripts/model/ReccomendModel.class.php';
	
	$session = Session::getInstance();
	$admin = $session->get('admin');
	if($admin === false){
		header("location: login.php");
		exit;
	}
	
	$reccomend_id = (int)@$_GET['id'];

	//$category_model = new CategoryModel();
	$reccomend_model = new ReccomendModel();
	
	if(isset($_GET['reset'])){
		$attention_model->delete();
		header("location: list-reccomend.php");
		exit;
	}
	
	if($_SERVER['REQUEST_METHOD']=="POST")
	{
		$data = array(
			'reccomend_num_1' => @$_POST['reccomend_num_1'],
			'reccomend_num_2' => @$_POST['reccomend_num_2'],
			'reccomend_num_3' => @$_POST['reccomend_num_3'],
		);
		if($reccomend_id==0){
			//新規ならレコードを作っておく
			$reccomend_model->insertReccomend($data);
			$reccomend_id = $reccomend_model->lastInsertId();
		}
		//更新
		$reccomend_model->updateReccomend($reccomend_id, $data);
		header("location: list-reccomend.php?id=".$reccomend_id."#upd");
		exit;
	}
	$reccomend = array();


	$reccomend = $reccomend_model->getReccomend($reccomend_id);
	$reccomend['reccomend_num_1'] = htmlspecialchars(@$reccomend['reccomend_num_1'], ENT_QUOTES, 'UTF-8');
	$reccomend['reccomend_num_2'] = htmlspecialchars(@$reccomend['reccomend_num_2'], ENT_QUOTES, 'UTF-8');
	$reccomend['reccomend_num_3'] = htmlspecialchars(@$reccomend['reccomend_num_3'], ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<title>よく読まれる記事設定</title>

<link rel="stylesheet" href="common/css/basic.css" type="text/css" media="all">
</head>

<body>
<?php require dirname(__FILE__).'/alert.php'; ?>
<div id="wrapper">


<div class="Content">

<div class="PageBtn">
<ul>
<?php require_once 'menu.php'; ?>
</ul>
</div>

<div class="Cnt">

<h2>よく読まれる記事設定</h2>


<form method="POST" action="list-reccomend.php" id="frmReg">



<table class="List">
<!--<tr><th></th><th>注目の特集</th></tr>-->
<tr>
<th style="text-align:center;">IDの選択<br>(記事の「item_id」を選択)</th>
<td>
<p><input type="text" name="reccomend_num_1" value="<?php echo $reccomend[0]['reccomend_num_1']; ?>" /></p>
</td>
</tr>
<tr>
<th style="text-align:center;">IDの選択<br>(記事の「item_id」を選択)</th>
<td>
<p><input type="text" name="reccomend_num_2" value="<?php echo $reccomend[0]['reccomend_num_2']; ?>" /></p>
</td>
</tr>
<tr>
<th style="text-align:center;">IDの選択<br>(記事の「item_id」を選択)</th>
<td>
<p><input type="text" name="reccomend_num_3" value="<?php echo $reccomend[0]['reccomend_num_3']; ?>" /></p>
</td>
</tr>
</table>

<p class="RegBtn"><a href="javascript:document.getElementById('frmReg').submit();">登録</a>　<!--<a href="javascript:onReset();">リセット</a>--></p>
</form>

</div>




</div>

</div>
<script>
function onReset()
{
	if(!confirm('本当にリセットしますか？')){
		return;
	}
	location.href='list-reccomend.php?reset';
}
</script>
</body>
</html>
