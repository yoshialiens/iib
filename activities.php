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
<meta property="og:title" content="いないいないばぁが提供しているもの">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/activities.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/page/activities.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="サービス,商品,サプライズマーケティング">
<meta name="description" content="日本一のサプライズマーケティング会社である株式会社いないいないばぁの私たちが提供しているものページです。口コミやリピートを増やすためにサプライズを提案し、人々に喜んでもらうために動いてもらいます。">
<title>私たちが提供しているもの|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/activities.php">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="activities">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="150" height="40"></a></div>
<div class="h-sec">
<h1>私たちが提供しているもの - 株式会社いないいないばぁ</h1>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/activities.php" itemprop="url"><span itemprop="title">私たちが提供しているもの</span></a></li>
</ul>
</div><!-- /パンくず -->


<div class="Block Top">
<div class="Cont">
<h2>私たちが提供しているもの</h2>
<p class="catch">ABOUT US</p>
</div><!-- /Cont -->
</div><!-- Block -->

<div class="Block">
<div class="Cont">
<div class="first">
<div class="firstBlock">
<div class="firstRight">
<p><img src="common/img/test.jpg" alt="サプライズで企業を変えます。" width="" height=""></p>
</div>
<div class="firstLeft">
<h3 class="firstTitle">サプライズで企業を変えます。</h3>
<p>弊社はサプライズで企業の課題を解決する、<br class="pcView">
サプライズマーケティング会社です。<br>
※売上をあげる目的のご依頼はお断りしています。</p>
<p>私たちが掲げる「サプライズマーケティング」は、<br class="pcView">売上を第一に考えません。</p>
<p>人の心に訴えかけるサプライズ（驚き、感動、喜び）をつくり、<br class="tabNoView">忘れられない体験を刻み込む。<br class="pcView">
感動を増幅させ、人々の行動に大きな変化を与えるのが<br class="pcView">「サプライズ」です。</p>
<p>会社にとって大切な「誰か」に驚きを与え、感動を届ける。<br class="tabNoView">
そのためのサプライズをデザインします。</p>
</div>
</div>
</div><!-- /first -->
</div><!-- /Cont -->
</div><!-- Block -->



<div class="Block Black">
<div class="Cont">
<div class="second">
<h3 class="secondTitle">私たちは以下の企業様を応援しています。</h3>
<ul class="secondBox">
<li>従業員100人以下の法人または個人</li>
<li>想いや情熱をもって事業に取り組んでいる</li>
<li>現状を変える勇気と遊び心がある</li>
</ul>
<p class="pickup">サプライズは販売を不要にする、いまだ<br class="spView">最も強力なマーケティングツールです。</p>
<p>どんな広告よりも効果があり、どんな体験よりも記憶に残り、どんなセールスよりも人を動かすのが「サプライズマーケティング」です。</p>
<p>人の行動に大きな変化を与え、「ファン」をつくり、「口コミ」を生み、販売を不要にする力がサプライズマーケティングにはあります。</p>
</div><!-- /second -->
</div><!-- /Cont -->
</div><!-- Block -->



<div class="Block White">
<div class="Cont">
<div class="third">
<h3 class="thirdTitle">サプライズマーケティング例</h3>
<div class="thirdBlock">
<div class="thirdImg f-r">
<p><img src="common/img/test.jpg" alt="ディズニーの事例" width="" height=""></p>
</div>
<div class="thirdTxt f-l">
<h4>ディズニーの事例：</h4>
<p>ディズニーは超人気キャラクター「ダッフィー」の新しいお友達「ジェラトーニ」を<br class="tabNoView">一般公開に先駆けて、ファンにお披露目する。<br>
序盤のミニショーで会場を盛り上げ、参加者たちは写真を撮り、見て、触って、<br class="tabNoView">それぞれの時間を満喫する。</p>
<p>そして自分の椅子に戻ると、そこには「ジェラトーニ」のぬいぐるみが。<br class="pcView">それはディズニーからのプレゼント。<br>
思いがけないサプライズにファンの感動と興奮が口コミとTwitterを駆け巡り、<br class="pcView">ジェラトーニグッズは、販売初日から数時間待ちの大行列となり、<br class="pcView">ぬいぐるみを残して数日で品切れてしまった。</p>
</div>
</div>

<div class="thirdBlock">
<div class="thirdImg f-l">
<p><img src="common/img/test.jpg" alt="某高級車ディーラーの事例" width="" height=""></p>
</div>
<div class="thirdTxt f-r">
<h4>某高級車ディーラーの事例：</h4>
<p>ターゲットになりそうな地域の住宅の前に商品となる車を駐車し、住宅と車が一緒に写るように撮影。<br class="pcView">
また別の住宅でも同じように駐車して撮影を行い、写真をPCで編集。</p>
<p>”この車はあなたが思っているよりずっと身近な存在ですよ”<br class="pcView">というメッセージを添えた「各家専用のDM」をその場でつくり、配布。<br class="pcView">
この演出に心を揺れ動かされ、DMを受け取った家庭の32%が試乗を予約した。</p>
</div>
</div>

<div class="thirdBlock">
<div class="thirdImg f-r">
<p><img src="common/img/test.jpg" alt="靴の通販小売会社の事例" width="" height=""></p>
</div>
<div class="thirdTxt f-l">
<h4>靴の通販小売会社の事例：</h4>
<p>その会社は通常4〜5日以内の配達を保証している。<br class="pcView">しかし、リピート顧客には翌日配達を実現しており、<br class="pcView">「もう届いた！」と驚いてもらう仕組みを構築している。</p>
<p>また、注文を受けた商品が在庫切れだった場合、「在庫はありませんでした」で終わらせない。<br class="pcView">
ライバルである他社のサイトで同じ商品がいくらで売られているかを検索。<br>
そして、それをお客様に教える、という感動サービスを提供し、ファンを作り、口コミが拡散した。</p>
</div>
</div>
</div><!-- /third -->
</div><!-- /Cont -->
</div><!-- Block -->




<div class="Block Blue">
<div class="Cont">
<div class="fourth">
<h3 class="fourthTitle">サプライズマーケティングの具体的な流れ</h3>
<div class="fourthTxt">
<p class="btm">「顧客へのサプライズ」をすることもあれば、「社内へのサプライズ」の場合もあります。<br class="pcView">
「誰に」驚きと感動を届けるのか。<br class="pcView">
クライアントさまに合ったサプライズ戦略をご提案いたします。</p>
<p>また、その戦略を実現するためのホームページ作成からマーケティング(集客)、<br class="pcView">イベント企画、プロモーション、アイデア出し、文章作成まで<br class="pcView">トータルでサポートさせていただきます。「サプライズの時間」を一緒に作り上げていきましょう。</p>
</div>

<h3 class="fourthTitle">ご依頼までの流れ</h3>
<ul class="step">
<li>
<p class="stepNumber">ステップ<span>1</span></p>
<p class="stepTitle">お問い合わせ</p>
<p class="stepTxt">お問い合わせフォームよりお問い合わせください。<br class="pcView">どのようなビジネスをしているのか、<br class="tabNoView">何のためにビジネスをやっているか<br class="tabNoView">などを教えてください。</p>
</li>
<li>
<p class="stepNumber">ステップ<span>2</span></p>
<p class="stepTitle">初回ヒヤリング</p>
<p class="stepTxt">私たちのオフィスにて1度お話させて<br class="tabNoView">いただき、イメージの共有やどのような<br class="tabNoView">課題を解消したいかをお伺いします。 <br class="pcView">また、どのようなサプライズをご提案<br class="tabNoView">するかの具体例もご紹介します。<br>
<span>※ここまで無料となります。</span></p>
</li>
<li>
<p class="stepNumber">ステップ<span>3</span></p>
<p class="stepTitle">お申し込み</p>
<p class="stepTxt">お申し込みしていただき、こちらで1度検討させていただきます。<br>
<span>※事業内容やどのようなお考えでビジネスに<br class="tabNoView">望まれているかによってお断りする場合が<br class="tabNoView">あります。</span></p>
</li>
<li>
<p class="stepNumber">ステップ<span>4</span></p>
<p class="stepTitle">ご契約</p>
<p class="stepTxt">検討させていただいたのち、契約書を<br class="tabNoView">交わします。期間は基本3ヶ月間です。<br>
<span>※ここで前金として、全体費用の50％お振込<br class="tabNoView">いただきます。</span></p>
</li>
<li>
<p class="stepNumber">ステップ<span>5</span></p>
<p class="stepTitle">サプライズマーケティング<br class="tabNoView">スタート</p>
<p class="stepTxt">ヒアリングを元に、サプライズ企画案を<br class="tabNoView">3つご提案します。<br class="tabNoView">その内容を元に、実践するサプライズを煮詰めていきます。</p>
</li>
<li>
<p class="stepNumber">ステップ<span>6</span></p>
<p class="stepTitle">サプライズの実践</p>
<p class="stepTxt">企画したサプライズを落とし込みます。<br class="pcView">進捗状況を確認するため、定期的に<br class="tabNoView">打ち合わせを進めます。この3ヶ月間は<br class="tabNoView">お互いに全力で臨んでいきましょう。<br class="pcView">
3ヶ月終了後、残りの全体費用の50%を<br class="tabNoView">お振込いただきます。<br>
<span>※契約の更新をご希望いただく場合は<br class="tabNoView">このタイミングでご検討ください。</span></p>
</li>
</ul>
<p class="btn01"><a href="contact/">お申し込み・お問い合わせ</a></p>
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
