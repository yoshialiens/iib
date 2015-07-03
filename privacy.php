<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/privacy.php");
	$url = "http://{$server_name}/privacy.php";
?>
<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="プライバシーポリシー|株式会社いないいないばぁ">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/privacy.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/common/fb.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="プライバシーポリシー,サプライズマーケティング">
<meta name="description" content="日本一のサプライズマーケティング会社、株式会社いないいないばぁのプライバシーポリシーです。口コミやリピートを増やすためにサプライズを提案し、人々に喜んでもらうために動いてもらいます。">
<title>プライバシーポリシー|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/privacy.php">
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
<h1>プライバシーポリシー - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=プライバシーポリシー|株式会社いないいいないばぁ &amp;url=http://www.i-i-b.jp/privacy.php" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?http://www.i-i-b.jp/privacy.php" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/privacy.php" itemprop="url"><span itemprop="title">プライバシーポリシー</span></a></li>
</ul>
</div><!-- /パンくず -->


<div class="Block Top">
<div class="Cont">
<h2>プライバシーポリシー</h2>
<p class="catch">PRIVACY POLICY</p>
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block">
<div class="Cont">
<section>
<h1 class="title">プライバシーポリシー</h1>
<!-- <h2>プライバシーポリシー</h2> -->
<p>株式会社いないいないばぁ（以下、「当社」という）は、<br class="pcView">総合コンサルティング、Webサイトの企画・制作業務などを通じて、個人情報を取り扱っています。</p>
<p>当社は、当社を信頼して個人情報を提供してくださった貴方との<br class="pcView">信頼関係を何よりも大切にします。</p>
<p>その信頼を失わないために、どのような場合にも、<br class="pcView">このプライバシーポリシーに則り、皆様の個人情報の適切な取り扱いを実現いたします。</p>
<p>当サイトをご利用される場合は、弊社のプライバシーポリシーにご同意くださったものと<br class="pcView">考えさせていただきますので本プライバシーポリシーの内容を熟読してご理解下さい。</p>

<h2>個人情報の保護について</h2>
<p>当社では、事業活動の過程で収集したお客様個人を特定できる情報<br class="pcView">（以下、「個人情報」とします）を適切に取り扱うために、<br class="pcView">下記の個人情報保護方針を定め、実施、維持、改叢活動を行っております。</p>

<ul>
<li>1：個人情報の取り扱いに関する法令、公的な規範を遵守します。</li>
<li>2：個人情報を収集する際は目的をできるだけ明確にし、<br class="pcView">その目的の範囲内での適法かつ公正な利用を行います。</li>
<li>3：収集した個人情報は厳重に管理し、<br class="pcView">不正アクセス、紛失、破壊、改ざん、漏えいを防ぐための合理的な安全対策を講じます。</li>
<li>4：収集した個人情報を管理する体制や仕組みについては定期的に見直し、改叢・向上に取り組みます。</li>
<li>5：お客様からご提供いただいた個人情報は、原則として下記の場合を除き、第三者へ開示しません。</li>
</ul>
<ul>
<li>● お客様の同意がある場合。</li>
<li>● あらかじめ弊社と機密保持契約を結んでいる企業に、業務上必要な範囲内で開示する場合。</li>
<li>● その他の理由で、適法かつ公正であると弊社が判断できる場合。</li>
</ul>

<h2>個人情報の収集</h2>
<p>貴方は当社ウェブサイトを閲覧するにあたり、<br class="pcView">ご自身に関するいかなる情報も開示する必要はありません。</p>
<p>貴方は匿名のままで、当社ウェブサイトを自由に閲覧する事ができます。</p>
<p>しかし、場合により貴方の氏名やメールアドレスなどの個人情報の開示を<br class="pcView">お願いする事があります。<br>（例：当社へのお問い合わせの際など）</p>
<p>しかし、貴方から当社へ個人情報を送信された場合、当社はあなたの個人情報を<br class="pcView">貴方の許可なく、第三者へ開示・共有する事はありません。</p>

<h2>免責事項</h2>
<p>当社ウェブサイトからリンクされた、当社ウェブサイト以外のウェブサイトの内容や
サービスに関して、<br class="pcView">当社ウェブサイトの個人情報保護についての諸条件は適用されません。</p>
<p>当社ウェブサイト以外のウェブサイトの内容及び、個人情報の保護に関して、<br class="pcView">当社は責任を負いません。</p>

<h2>個人情報の取扱いに関する相談窓口</h2>
<p>株式会社いないいないばぁ<br>
東京都渋谷区渋谷1-17-1 TOC第2ビル3F</p>
<p>mail：<a href="/contact/">お問い合わせフォーム</a>よりお問い合わせください。</p>

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
