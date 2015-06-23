<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/activities.php");
	$url = "http://{$server_name}/activities.php";
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="サプライズマーケティング全国制覇|株式会社いないいないばぁ">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/campaign201501.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/page/campaign.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="サプライズマーケティング全国制覇,サプライズマーケティング">
<meta name="description" content="日本一のサプライズマーケティング会社、株式会社いないいないばぁではサプライズマーケティング全国制覇キャンペーンを実施中です。47都道府県の企業様のご応募をお待ちしております。">
<title>サプライズマーケティング全国制覇|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/campaign201501.php">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="campaign">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="150" height="40"></a></div>
<div class="h-sec">
<h1>サプライズマーケティング全国制覇 - 株式会社いないいないばぁ</h1>
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
<!-- パンくず -->
<div class="breadcrumb">
<ul>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">HOME</span></a></li>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/campaign201501.php" itemprop="url"><span itemprop="title">サプライズマーケティング全国制覇</span></a></li>
</ul>
</div><!-- /パンくず -->

<div class="Block CampTop">
<img src="common/img/campaign/top.jpg" alt="サプライズマーケティング全国制覇" width="2000" height="" class="tabNoView">
<img src="common/img/campaign/top-tab.jpg" alt="サプライズマーケティング全国制覇" width="1300" height="" class="tabView">
</div><!-- /MV -->

<div class="Block">
<div class="Cont">
<div class="first">
<h2>〜「日本の企業はカッコイイ」という未来を〜</h2>
<p class="subTitle">全国４７都道府県の企業をサプライズで応援！</p>
<div class="firstBlock">
<h3>「あれ？今の日本っておかしくない？」</h3>
<p>これがすべての始まりでした。事実として、日本は売上や成果を第一に考えた結果、衰退してしまいました。<br class="pcView">
軍隊のような組織や成果主義により、”お客様のハッピーよりもノルマを優先する”<br class="tabNoView">
”家族よりも上司の目を気にする”といったことが当たり前になっていたのです。</p>
<p>では、<strong>仕事を通じて人間が幸せになるためにはどうすればいいのか？</strong>という難題に対して<br class="tabNoView">
われわれが出した答えは</p>
<p class="fs-l u-l">サービスを提供する人もされる人もワクワクする</p>
<p>でした。</p>
<p><b>今度はどんな手を使って驚かせてやろうかな？</b>って大人がニヤニヤしているような未来って<br class="tabNoView">
なんだかワクワクするなって感じたのです。<br>
そんな想いから<b>”売上を伸ばす”</b>ではなく、<strong>”心を震わせる体験を刻み込む”ことを追求しました。</strong></p>
<p class="u-l">もっとも大切な「人の心」を考え続けた結果がサプライズマーケティングだったのです。</p>
</div>
</div><!-- /first -->
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block Black">
<div class="Cont">
<div class="second">
<ul class="secondBox">
<li>サプライズには感動させる力がある</li>
<li>サプライズには絆を深める力がある</li>
<li>サプライズには人を動かす力がある</li>
</ul>
<p class="pickup">これを信念に従来の常識を変えるべく立ち上がりました。</p>
</div><!-- /second -->
</div><!-- /Cont -->
</div><!-- Block -->



<div class="Block">
<div class="Cont">
<div class="third">
<h3>〜日本全国の企業を無料でサポートする〜</h3>
<div class="thirdBlock">
<p>「日本を変える」と言うことは簡単です。<br>
しかし、本当に実現するのならば信念、行動が伴っていなければなりません。</p>
<p>それを実現するために、<strong>全国４７都道府県をまわり無料でサポート</strong>をすることにしました。<br>
”売上を重視しない”と言っているため、<span class="u-l">アドバイス料や成果報酬などは一切いただきません。</span><p>
<p>ただし、<b>会社、商品、サービス、働く人、お客様への熱い想いと変わる勇気を見せてください。</b></p>
<p class="u-l bold">わたしたちは本気でサポートします。</p>
<p>・布団をはねのけてワクワクしながら目覚める<br>
・周りの大切な人に涙を流すくらい喜んでもらう<br>
・「あなたじゃないとダメなんだ」と言われるくらい愛される</p>
<p>そんな瞬間を一緒に味わいましょう。</p>
</div>
</div><!-- /third -->
</div><!-- /Cont -->
</div><!-- Block -->



<div class="Block White">
<div class="Cont">
<div class="fourth">
<h3>お申し込み</h3>

<div class="fourthBlock">
<h4>募集期間</h4>
<div class="fourthBottom">
<p class="fourthTitle">7/1(水)〜7/20(月)</p>
</div>
</div>

<div class="fourthBlock">
<h4>応募条件</h4>
<div class="fourthBottom">
<p class="fourthTitle">交通費を負担いただく</p>
<p>コンサルタントが直接お伺いする際の交通費を（場所によっては宿泊費）のみ、２名分ご負担ください。<br>
コンサル費用はいただきません。</p>
<p class="fourthTitle">提案の中から実践していただく</p>
<p>サプライズコンサルティングを成功させるにはお客様の協力が欠かせません。<br>
弊社からの提案に対しては１つ以上、実行していただくことをお約束ください。</p>
<p class="fourthTitle">コンサル内容をWeb上で公開させていただく</p>
<p>実際におこなった提案や取り組んでいる様子を弊社Webサイトで公開します。<br>
社名は伏せていただいても結構ですが、多くの方に知ってもらう機会になります。</p>
</div>
</div>

<div class="fourthBlock">
<h4>お申し込みの流れ</h4>
<div class="fourthBottom">
<p class="fourthTitle">STEP1：申し込み(アンケート)</p>
<p>まずは下記のアンケートをご記入ください。<br>
特に「なんのためにビジネスをやっているのか」、「なぜ今の仕事をはじめたのか」などは詳しくご記入ください。</p>
<p class="fourthTitle">STEP2：審査</p>
<p>いただいたアンケートは１通１通、拝見させていただきます。<br>
心苦しいですが、１つの都道府県につき１社のみご協力させていただきます。<br>
ご連絡は１週間以内にさせていただきます。</p>
<p class="fourthTitle">STEP3：ヒアリング</p>
<p>会社、商品、お客様などへの理解を深めるためにヒアリングシートを用いてヒアリングさせていただきます。<br>
サプライズマーケティングを成功させるためにもじっくりとお話を聞かせてください。</p>
<p class="fourthTitle">STEP4：訪問＆ご提案</p>
<p>サプライズコンサルタントがお客様の元を訪問します。<br>
ヒアリング内容をもとにいくつかご提案させていただきます。</p>
<p class="fourthTitle">サプライズの実践</p>
<p>サプライズコンサルタントが提案した中から１つ以上の企画を実践していただきます。</p>
</div>
</div>

<p class="btn01"><a href="#">7/1(水) 募集スタート！</a></p>
</div><!-- /fourth -->
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
