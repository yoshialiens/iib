<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/detail01.php");
	$url = "http://{$server_name}/detail01.php";
?>
<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="キャンペーン詳細|株式会社いないいないばぁ">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/detail01.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/common/fb.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="キャンペーン詳細,日本全国サプライズの旅,サプライズマーケティング">
<meta name="description" content="株式会社いないいないばぁ企画、日本全国サプライズ旅キャンペーンの概要ページです。各都道府県１社に対してサプライズマーケティングを無料で実施していただきます！">
<title>キャンペーン詳細|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/detail01.php">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="privacy">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://www.i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="147" height="50"></a></div>
<div class="h-sec">
<h1>キャンペーン詳細 - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=日本全国サプライズの旅-株式会社いないいいないばぁ &amp;url=http://www.i-i-b.jp/campaign/" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?http://www.i-i-b.jp/campaign/" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
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
<!-- パンくず -->
<div class="breadcrumb">
<ul>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">HOME</span></a></li>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/detail01.php" itemprop="url"><span itemprop="title">キャンペーン詳細</span></a></li>
</ul>
</div><!-- /パンくず -->


<div class="Block Top">
<div class="Cont">
<h2>キャンペーン詳細</h2>
<p class="catch">CAMPAIGN INFO</p>
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block">
<div class="Cont">
<section>
<h2>★キャンペーン期間</h2>
<p>2015年7月1日（水）0時〜2015年7月20日（月）24時</p>

<h2>★応募方法</h2>
<p>本キャンペーンにご参加される際は本サイト内の<br class="pcView">
「お申し込みはこつら！」ボタンをクリックしてください。<br class="pcView">
クリックをすると、アンケート画面が表示されますので<br class="pcView">
必要事項をご記入ください。</p>

<h2>★応募方法</h2>
<p>2015年7月20日（月）24時まで</p>

<h2>★賞品・当選者数</h2>
<p>賞品：無料でサプライズマーケティング（コンサルタントが訪問する際の交通費のみ負担）<br class="pcView">
当選者数：各都道府県につき１社</p>

<h2>★応募方法</h2>
<ul>
<li>● 当社の提案に対して１つ以上の施策を行っていただける方のみ対象となります。</li>
<li>● サプライズマーケティング実行後、アンケートのご協力、HP 掲載等肖像権の商業利用にご了承いただける方のみ対象となります。</li>
</ul>

<h2>★当選発表について</h2>
<p>合否の結果にかかわらず、弊社からメールを差し上げます。<br class="pcView">ご連絡はアンケート提出後から１週間程度を予定しております。</p>

<h2>★注意事項</h2>
<ul>
<li>● 登録前に必ず｢利用規約｣をお読みください。</li>
<li>● 本キャンペーンのご応募はパソコン、携帯電話、スマートフォンからのみです。はがき、封書でのご応募は受付けておりません。</li>
<li>● 当選権利の譲渡および転売はできません。</li>
<li>● 当選後における当選者都合のキャンセルは受付いたしませんのでご了承ください。</li>
<li>● メール受信拒否設定（ドメイン指定受信）を設定している場合は、『@i-i-b.jp』からのメールを受信できるよう、設定してください。設定されていない場合、キャンペーンのお知らせに関するメールが受信できない場合がございます。</li>
<li>次の場合は応募が無効となりますので、ご注意ください。</li>
<li class="ml-20">● 応募内容に虚偽の記載があった場合。</li>
<li class="ml-20">● 暴力団・暴力団員その他これに準ずる者等反社会的勢力に該当した場合。</li>
<li class="ml-20">● その他、ご応募に関して不正な行為があった場合。</li>
<li class="ml-20">● 当社が定める注意事項および禁止事項等にご同意いただけない場合。（当選者は、依頼時に、当社が定める注意事項および禁止事項等にご同意いただく必要があります。）
</li>
<li class="ml-20">● 応募時に想定外の動作をし、当選された場合。</li>
<li class="ml-20">● その他利用規約に違反した場合。</li>
</ul>

<h2>★個人情報の取り扱い</h2>
<p>ご入力いただいた個人情報は、当社が応募資格の確認、当選のご連絡、<br class="pcView">
本キャンペーンのメール配信、メールマガジン配信（ご希望いただいた方のみ）の他、<br class="pcView">
個人を特定しない統計的情報として利用させていただきます。<br>
また当社のプライバシーポリシーについては、<a href="privacy.php" target="_blank">こちら</a>をご確認ください。</p>

<h2>★お問い合わせ先</h2>
<p>株式会社いないいないばぁ<br><a href="mailto:info@i-i-b.jp">info@i-i-b.jp</a></p>
<p>＊土・日・祝日は、業務をお休みさせていただいております。</p>


</section>
</div><!-- /Cont -->
</div><!-- Block -->

<?php @include 'more.php'; ?>
<?php @include 'campaignSec.php'; ?>
</div><!-- /main -->
</div><!-- /contents -->
<?php @include 'footer.php'; ?>
</div><!-- /#wrapper -->



</div><!-- /#wrapper -->

<!-- JS Section -->
<?php @include 'js.php'; ?>
<!-- /JS Section -->
</body>
</html>
