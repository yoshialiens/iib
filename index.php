<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ExperienceModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/SpecialModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/RankingModel.class.php';

	$division_model = new DivisionModel();
	$category_model = new CategoryModel();
	$item_model = new ItemModel();
	$review_model = new ReviewModel();
	$experience_model = new ExperienceModel();
	$special_model = new SpecialModel();
	$ranking_model = new RankingModel();

	//division_id一覧
	$division_list = array(
		1, //IT・ネット・通信
		2, //金融
		3, //美容・サプリ
		4, //暮らし・生活
		5, //グルメ
		6, //ビジネス・資格
	);

	$division_all = array();
	foreach($division_list as $id)
	{
		$division_all[$id] = $division_model->getDivision($id);
	}

	foreach($division_all as $k => $v)
	{
		$category_all = $category_model->getCategoryAllByDivisionId($v['division_id']);
		$item_all = $item_model->getItemAllByDivisionId($v['division_id']);
		$division_all[$k]['category_all'] = array_slice($category_all, 0, 4);
	}


    //1ページに表示する数
	$PAGE_ITEM_COUNT = 6;
	//$filter = $session->get('filter');
	$category_id2 = (int)@$filter['category_id'];
	//ページ番号
	$page_num = @$_GET['p'];

	$item_all2 = $item_model->getItemAllByFilter($category_id2, $page_num, $PAGE_ITEM_COUNT);
	foreach($item_all2 as &$v2)
	{
		$v2['division_name'] = htmlspecialchars($v2['division_name'], ENT_QUOTES, 'UTF-8');
		$v2['category_name'] = htmlspecialchars($v2['category_name'], ENT_QUOTES, 'UTF-8');
		$v2['name'] = htmlspecialchars($v2['name'], ENT_QUOTES, 'UTF-8');
		unset($v2);
	}

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/");
	$url = "http://{$server_name}/";
?>


<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="株式会社いないいないばぁ|日本一のサプライズマーケティング">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="website">
<meta property="og:url" content="http://www.i-i-b.jp/">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/page/index.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="いないいないばぁ,サプライズマーケティング,サプライズコンサルティング">
<meta name="description" content="株式会社いないいないばぁは日本一のサプライズマーケティング会社です。口コミやリピートを増やすためにサプライズを提案し、人々に喜んでもらうために動いてもらいます。">
<title>株式会社いないいないばぁ|日本一のサプライズマーケティング</title>
<link rel="canonical" href="http://www.i-i-b.jp/">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="index">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="150" height="40"></a></div>
<div class="h-sec">
<h1>日本一のサプライズマーケティング会社 - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=日本一のサプライズマーケティング会社-株式会社いないいいないばぁ &amp;url=http://www.i-i-b.jp/" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?http://www.i-i-b.jp/" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
</ul><!-- /h-sns -->
</div><!-- /PC MENU -->
<div class="spView"><!-- SP MENU -->
<header id="header-sp" class="spView">
<p class="Logo"><a href="/"><img src="common/img/bnr/logo.png" width="155" height="38" alt="株式会社いないいないばぁ"/></a></p>
<?php @include 'header-nav-sp.php'; ?>
</header>
</div><!-- /SP MENU -->
</header>



<div class="contents">
<div id="main">
<div class="Block MV">
<div class="mvTxt">
</div>
</div><!-- /MV -->
<div class="Block Blue">
<div class="Cont">
<h3>About"IIB"</h3>
<p class="subTitle">株式会社いないいないばぁはこんな会社です。</p>
<div class="aboutList">
<div class="aboutImg f-l"><div class="radius150"><img src="common/img/index/about01.jpg" alt="日本一のサプライズマーケティング会社" width="300" height="300"></div></div>
<div class="aboutTxt f-r"><h4>株式会社いないいないばぁは<br class="pcView">「日本一のサプライズマーケティング会社」です。</h4>
<p>私達はサプライズで企業の課題を解決します。人の行動に大きな変化を与え、ファンをつくり、口コミを生み、販売を不要にするのがサプライズマーケティングです。<br class="tabNoView">
会社にとって大切な「誰か」に驚きを与え、感動を届けるためのサプライズをデザインします。</p></div><!-- /aboutTxt -->
</div><!-- /aboutList -->

<div class="aboutList">
<div class="aboutImg f-r"><div class="radius150"><img src="common/img/index/about02.jpg" alt="売上よりも驚きを" width="300" height="300"></div></div>
<div class="aboutTxt f-l"><h4>売上よりも驚きを</h4>
<p>私たちは売上を第一に考えない会社です。人の気持ちのど真ん中に響く「驚き」を何よりも大切にします。<br class="tabNoView">
腕をつかんで無理やり引っ張ることはせず、心に訴えかけるサプライズを届けると、人は自然と集まります。<br class="tabNoView">
そんなやさしい「驚き」を第一に考えます。</p></div><!-- /aboutTxt -->
</div><!-- /aboutList -->

<div class="aboutList">
<div class="aboutImg f-l"><div class="radius150"><img src="common/img/index/about03.jpg" alt="サプライズを企業文化に" width="300" height="300"></div></div>
<div class="aboutTxt f-r"><h4>サプライズを企業文化に</h4>
<p>顧客だけでなく、社内の従業員、関わる取引先の企業も含め、誰もが毎日をワクワクしながら過ごせる。そんな企業を増やしていける力がサプライズにはあると信じています。<br class="tabNoView">
日本の企業文化にサプライズを浸透させ、従来の常識を変えていくことが私たちの理念です。</p></div><!-- /aboutTxt -->
</div><!-- /aboutList -->

</div><!-- Cont -->
</div><!-- Block -->

<div class="Block White">
<div class="Cont">
<h3>ACTIVITY REPORT</h3>
<p class="subTitle">株式会社いないいないばぁの活動報告</p>
<ul class="blog">


<?php foreach($item_all2 as $v2): ?>
<li>
<div class="blogImg shake"><div class="blogTag pcView bg<?php echo $v2['category_id']; ?>"><p><?php echo $v2['category_name']; ?></p></div><a href="article.php?item_id=<?php echo $v2['item_id']; ?>"><?php if(!empty($v2['photo1'])){ ?><img src="<?php echo $v2['photo1']; ?>" width="300" height="300" alt="<?php echo $v2['name']; ?>"><?php } ?></a></div>
<div class="blogTitle"><a href="article.php?item_id=<?php echo $v2['item_id']; ?>"><?php echo $v2['name']; ?></a></div>
<div class="blogEx"><p><?php echo $v2['rank_info']; ?></p></div>
<div class="moreBtn pcView"><a href="article.php?item_id=<?php echo $v2['item_id']; ?>">MORE ＞</a></div>
</li>
<?php endforeach; ?>

</ul>
</div><!-- /Cont -->
</div><!-- Block -->

<?php @include 'more.php'; ?>
<?php @include 'campaign.php'; ?>

</div><!-- /main -->
</div><!-- /contents -->
<?php @include 'footer.php'; ?>
</div><!-- /#wrapper -->

<!-- JS Section -->
<?php @include 'js.php'; ?>
<!-- /JS Section -->
</body>
</html>