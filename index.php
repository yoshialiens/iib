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
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="株式会社いないいないばぁ|日本一のサプライズマーケティング">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="website">
<meta property="og:url" content="http://www.i-i-b.jp/">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/common/fb.jpg">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="いないいないばぁ,サプライズマーケティング,サプライズコンサルティング,IIB">
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
<div class="logo"><a href="http://www.i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="147" height="50"></a></div>
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
<p class="Logo"><a href="/"><img src="common/img/bnr/logo.png" width="147" height="50" alt="株式会社いないいないばぁ"/></a></p>
<?php @include 'header-nav-sp.php'; ?>
</header>
</div><!-- /SP MENU -->
</header>



<div class="contents">
<div id="main">
<div class="Block MV">
<a href="/campaign/" target="_blank"></a>
</div><!-- /MV -->

<div class="Block Black">
<div cllass="Cont">
<p class="Label">７月１日〜「日本全国サプライズの旅」キャンペーン中！詳細は画像をクリック！</p>
</div>
</div>

<div class="Block White">
<div class="Cont">
<div class="mission">
<p class="missionTitle">「サプライズを企業文化に」</p>
<div class="Txt">
<p class="TxtImg">
<img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="147" height="50">
</p>
<p>早期に見返りを求めること。<br class="pcView">
売上を重要視すること。<br class="pcView">
それは大事なものを見えなくさせます。</p>

<p>私たちは売上を第一に考えません。<br class="pcView">
「売上」ではなく、大切にしたいものは<br class="pcView">
喜びや感動を生む「サプライズ」を作ること。</p>

<p>サプライズには「感動させる力」「絆を深める力」「人を動かす力」があります。<br class="pcView">
人の心に訴えかけ、感動を増幅させ、企業に大きな渦を巻き起こします。<br class="pcView">
顧客だけでなく、社内の従業員、そこに関わる取引先の企業も誰もが毎日を<br class="pcView">
ワクワクと過ごせるよう、日本の企業文化にサプライズを浸透させていきたい。</p>

<p>サプライズこそが日本の起爆剤になると信じて、<br class="pcView">
これまでの常識を変えていくことが私たちの理念です。</p>

<p>想いや情熱を持った素晴らしい企業様を１社でも多く増やしていき、<br class="pcView">
そして世の中を驚かせるビジネスを１つでも多く増やしていくことを目指し、<br class="pcView">
今日も誰かをやさしく驚かせます。</p>

<p class="missionLast">「いつもビジネスに驚きを」<br>
株式会社いないいないばぁ</p>
</div>
</div><!-- /mission -->
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block Blue">
<div class="Cont">
<h3>NEWS</h3>
<p class="subTitle">株式会社いないいないばぁのニュース</p>
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
<div class="blogBtn"><p class="btn02"><a href="blog.php">もっと読む</a></p></div>
</div><!-- /Cont -->
</div><!-- Block -->

<?php @include 'more.php'; ?>
<?php @include 'campaignSec.php'; ?>

</div><!-- /main -->
</div><!-- /contents -->
<?php @include 'footer.php'; ?>
</div><!-- /#wrapper -->

<!-- JS Section -->
<?php @include 'js.php'; ?>
<!-- /JS Section -->
</body>
</html>