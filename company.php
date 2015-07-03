<?php
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/company.php");
	$url = "http://{$server_name}/company.php";
?>
<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="いないいないばぁの会社概要">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/company.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/common/fb.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="会社概要,サプライズマーケティング,いないいないばぁ">
<meta name="description" content="日本一のサプライズマーケティング会社、株式会社いないいないばぁの会社概要ページです。企業情報やメンバー紹介など、株式会社いないいないばぁに関してはこちらのページをごらんください。">
<title>会社概要|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/company.php">
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="company">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://www.i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="147" height="50"></a></div>
<div class="h-sec">
<h1>会社概要 - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=会社概要|株式会社いないいいないばぁ &amp;url=http://www.i-i-b.jp/company.php" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?http://www.i-i-b.jp/company.php" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/company.php" itemprop="url"><span itemprop="title">会社概要</span></a></li>
</ul>
</div><!-- /パンくず -->

<div class="Block Top">
<div class="Cont">
<h2>会社概要</h2>
<p class="catch">COMPANY</p>
</div><!-- /Cont -->
</div><!-- Block -->

<div class="Block">
<div class="Cont">
<h3 class="comTitle">会社概要</h3>
<dl id="table">
<dt>会社名</dt>
<dd>株式会社いないいないばぁ</dd>
<dt>資本金</dt>
<dd>3,000,000円</dd>
<dt>設立</dt>
<dd>2013年8月</dd>
<dt>所在地</dt>
<dd>〒150-0002<br>東京都渋谷区渋谷1丁目17−1TOC第二ビル3F</dd>
<dt>設立</dt>
<dd>2013年8月</dd>
<dt>社員数</dt>
<dd>10名</dd>
<dt>事業内容</dt>
<dd>・サプライズマーケティング事業<br>
　（企画・立案/サイト制作/デザイン/プロモーション/ライティング）<br>
・自社メディア運用事業<br>
・プロモーション・コンテンツ企画制作事業<br>
・システム開発事業<br>
<a href="activities.php">サービス内容を詳しく見る>></a>
</dd>
<dt>主要取引先</dt>
<dd>英会話のAEON<br>
株式会社ジオコード<br>
Allianz Global Assistance Japan<br>
東京海上グループ<br>
株式会社ユーザー・センタード・デザイン<br>
サイブリッジグループ<br>
株式会社カドベヤ<br>
IEインスティテュート<br>
株式会社イニシャルサイト<br>
株式会社クミン<br>
株式会社カザキリ<br>
Wonderland.inc<br>
株式会社アップスジェイピー<br>
株式会社ルートワン・パワー<br>
株式会社モバイルカレッジ<br>
Aquent<br>
A.K.I設計<br>
株式会社Lo Umber<br>
Operation Blessing Japan<br>
アデコ株式会社<br>
株式会社ベストクリエイト<br>
株式会社ファンメディア<br>
レイン・バード株式会社<br>
バリューマーケティング<br>
アッソラート</dd>
</dl>
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block Blue">
<div class="Cont">
<h3>IIBのメンバー</h3>
<p class="subTitle">MEMBER</p>
<ul class="member">
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/takenari.png" alt="柴田剛成" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/takenari2.png" alt="柴田剛成" width="300" height="300"></div>
</div></div>
<div class="memberTitle">柴田 剛成</div>
<div class="memberSub">Takenari Shibata</div>
<div class="memberEx"><p>代表取締役 / クリエイティブチームリーダー<br>WEB解析、WEB制作、鬼軍曹を担当。</p></div>
<!--<div class="memberEx"><p>株式会社いないいないばぁの黒幕。<br>WEB解析士、翻訳者、WEB制作、コンサルティング、鬼軍曹を担当。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/kuni.png" alt="中村州宏" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/kuni2.png" alt="中村州宏" width="300" height="300"></div>
</div></div>
<div class="memberTitle">中村 州宏</div>
<div class="memberSub">Kunihiro Nakamura</div>
<div class="memberEx"><p>取締役 / ライティングチームリーダー<br>コピーライティングとパシリ役を担当。</p></div>
<!--<div class="memberEx"><p>株式会社いないいないばぁの白幕。<br>コピーライティングとパシリ役を担当。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/koiken.png" alt="小泉憲一" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/koiken2.png" alt="小泉憲一" width="300" height="300"></div>
</div></div>
<div class="memberTitle">小泉 憲一</div>
<div class="memberSub">Kenichi Koizumi</div>
<div class="memberEx"><p>取締役 / マーケティングチームリーダー<br>ききビールと鬼ビールを担当。</p></div>
<!--<div class="memberEx"><p>いないいないばぁさんのおとうさん。<br>主にビールを飲んで寝る役。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/yoshitaku.png" alt="吉見拓朗" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/yoshitaku2.png" alt="吉見拓朗" width="300" height="300"></div>
</div></div>
<div class="memberTitle">吉見 拓朗</div>
<div class="memberSub">Takuro Yoshimi</div>
<div class="memberEx"><p>クリエイティブチーム / プログラマー<br>WEB周りと水回りを担当。</p></div>
<!--<div class="memberEx"><p>株式会社いないいないばぁの武士。<br>WEB周りやコーチング担当のマイペースなParty Boy。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/denden.png" alt="田中優輝" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/denden2.png" alt="田中優輝" width="300" height="300"></div>
</div></div>
<div class="memberTitle">田中 優輝</div>
<div class="memberSub">Yuuki Tanaka</div>
<div class="memberEx"><p>クリエイティブチーム / プログラマー<br>WEB制作と残飯処理を担当。</p></div>
<!--<div class="memberEx"><p>株式会社いないいないばぁの自由人。<br>web制作、カウンセリング、ご縁をつなぐワークショップ、残飯処理を担当。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/tsuchi.png" alt="土持和" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/tsuchi2.png" alt="土持和" width="300" height="300"></div>
</div></div>
<div class="memberTitle">土持 和</div>
<div class="memberSub">Wataru Tsuchimochi</div>
<div class="memberEx"><p>クリエイティブチーム / デザイナー<br>WEBデザインとボインを担当。</p></div>
<!--<div class="memberEx"><p>いないいないばぁのマスコットキャラクター。<br>主にWEBデザインと子守りを担当。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/shinnosuke.png" alt="齊川真輔" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/shinnosuke2.png" alt="齊川真輔" width="300" height="300"></div>
</div></div>
<div class="memberTitle">齊川 真輔</div>
<div class="memberSub">Shinsuke Saikawa</div>
<div class="memberEx"><p>マーケティングチーム / クマ<br>くま。</p></div>
<!--<div class="memberEx"><p>株式会社いないいないばぁの忍び。<br>WEB解析士、FP、マーケティングといじられ役を担当。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/yasuto.png" alt="平井靖人" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/yasuto2.png" alt="平井靖人" width="300" height="300"></div>
</div></div>
<div class="memberTitle">平井 靖人</div>
<div class="memberSub">Yasuto Hirai</div>
<div class="memberEx"><p>ライティングチーム / ライター<br>ライティングとスパンキングを担当。</p></div>
<!--<div class="memberEx"><p>株式会社いないいないばぁのボランチ。<br>コピーライティングと関西弁を担当。</p></div>-->
</li>
<li>
<div class="memberImg" ontouchstart="this.classList.toggle('hover');"><div class="flipper">
<div class="front"><img src="common/img/member/naoki.png" alt="松永直樹" width="300" height="300"></a></div>
<div class="back"><img src="common/img/member/naoki2.png" alt="松永直樹" width="300" height="300"></div>
</div></div>
<div class="memberTitle">松永 直樹</div>
<div class="memberSub">Naoki Matsunaga</div>
<div class="memberEx"><p>ライティングチーム / ライター<br>線路に石を並べているところを拾ってきたため、まずは足し算から教えることになった。</p></div>
<!--<div class="memberEx"><p>いないいないばぁのエンターテイナー。<br>コピーライティング、レクリエーション、ツッコミ（雑）を担当。</p></div>-->
</li>
</ul>
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
