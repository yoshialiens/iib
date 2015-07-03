<?php 
	require_once dirname(__FILE__) . '/scripts/model/DivisionModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CategoryModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/UploadLib.class.php';

	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/credo.php");
	$url = "http://{$server_name}/credo.php";
?>
<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta property="og:title" content="いないいないばぁのクレド">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/credo.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/common/fb.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="クレド,行動指針,サプライズマーケティング,いないいないばぁ">
<meta name="description" content="日本一のサプライズマーケティング会社、株式会社いないいないばぁのクレドページです。弊社が掲げる行動指針について、面白おかしく紹介しています。">
<title>クレド|株式会社いないいないばぁ</title>
<link rel="canonical" href="http://www.i-i-b.jp/credo.php">
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
<h1>クレド - 株式会社いないいないばぁ</h1>
<?php @include 'header-nav.php'; ?>
</div><!-- /h-sec -->
<ul class="h-sns">
<li class="snsBlock"><a href="https://www.facebook.com/inai2bar" target="_blank"><span class="icon-">&#xea8d;</span></a></li>
<li class="snsBlock"><a href="http://twitter.com/share?text=クレド|株式会社いないいいないばぁ &amp;url=http://www.i-i-b.jp/credo.php" target="_blank"><span class="icon-">&#xea92;</span></a></li>
<li class="snsBlock"><a href="http://b.hatena.ne.jp/append?http://www.i-i-b.jp/credo.php" target="_blank"><span class="icon-">&#xeaba;</span></a></li>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="/credo.php" itemprop="url"><span itemprop="title">クレド</span></a></li>
</ul>
</div><!-- /パンくず -->

<div class="Block Top btm-lll">
<div class="Cont">
<h2>いないいないばぁのクレド</h2>
<p class="catch">CREDO</p>
</div><!-- /Cont -->
</div><!-- Block -->

<div class="Block">
<div class="credoList">
<div class="credoBlock f-r">
<img src="common/img/credo/01.png" alt="迷ったらワクワクを選ぶ" width="750" height="401">
</div>
<div class="credoBlock f-l">
<p class="credoNumber">CREDO<span>01</span></p>
<p class="credoTitle">迷ったらワクワクを選ぶ</p>
<p>迷うのは、軸が決まっていないから。</p>
<p>迷ってしまうのは、立ち止まって前に進めないこと。</p>
<p>人生は選択の連続だから、迷い始めたらキリがありません。</p>
<p>私たちはリサーチに全力を尽くして迷ったら、こう自分に問いかけます</p>
<p>「自分はどっちにワクワクするだろうか！？」</p>
<p>私たちは直感で心に響いた方を選んで前に進みます。</p>
</div>
</div><!-- /credoList1 -->

<div class="credoList">
<div class="credoBlock f-l">
<img src="common/img/credo/02.png" alt="全員がリーダー" width="750" height="401">
</div>
<div class="credoBlock f-r">
<p class="credoNumber">CREDO<span>02</span></p>
<p class="credoTitle">全員がリーダー</p>
<p>リーダーって何？</p>
<p>「ビジョンを示す人？」「やる気を引き出す人？」「仲間を成長させる人？」</p>
<p>どれも正解。</p>
<p>でも大事なのは、自分で考えて決断するってこと。</p>
<p>リーダーは人に依存しません。</p>
<p>リーダーは雇われてるという意識もありません。</p>
<p>私たちは周りの意見も取り入れつつ、最後は自分で決断します。</p>
</div>
</div><!-- /credoList2 -->

<div class="credoList">
<div class="credoBlock f-r">
<img src="common/img/credo/03.png" alt="社員（なかま）はもうひとつの家族" width="750" height="401">
</div>
<div class="credoBlock f-l">
<p class="credoNumber">CREDO<span>03</span></p>
<p class="credoTitle">社員（なかま）はもうひとつの家族</p>
<p>家族ほど、無条件に自分を愛してくれる人はいません。</p>
<p>自分を隠す必要もない。ありのままでいい。</p>
<p>本気でぶつかるし、厳しくもする。だから成長します。</p>
<p>社員（なかま）とだって、そんな関係でもいい。</p>
<p>何をやるかより、誰とやるか？</p>
<p>理由とかメリットも大事だけど、私たちはまず社員（なかま）を信頼します。</p>
</div>
</div><!-- /credoList3 -->

<div class="credoList">
<div class="credoBlock f-l">
<img src="common/img/credo/04.png" alt="年に1回は実家に帰り、親孝行をする" width="750" height="401">
</div>
<div class="credoBlock f-r">
<p class="credoNumber">CREDO<span>04</span></p>
<p class="credoTitle">年に1回は実家に帰り、親孝行をする</p>
<p>自分がこの世に生まれる確率。</p>
<p>それは天文学的数字とも言えます。</p>
<p>つまり、人生は奇跡の連続。</p>
<p>そんな奇跡のきっかけを生み出してくれた家族を大切にせず、</p>
<p>誰を大切にできる？　誰を幸せにできる？</p>
<p>私たちはまず、家族に感謝します。「ありがとう」をかたちにします。</p>
<p>そして、家族の目の前で「ありがとう」をプレゼントします。</p>
<p>家族が生きているうちじゃなく、家族が元気でいるうちにします。</p>
</div>
</div><!-- /credoList4 -->

<div class="credoList">
<div class="credoBlock f-r">
<img src="common/img/credo/05.png" alt="目指すは日本一幸せになる会社" width="750" height="401">
</div>
<div class="credoBlock f-l">
<p class="credoNumber">CREDO<span>05</span></p>
<p class="credoTitle">目指すは日本一幸せになる会社</p>
<p>目指すものは何？</p>
<p>大金持ちになること？自由になること？</p>
<p>それは成功であって、幸せじゃないかもよ？</p>
<p>自分のことばかり考えすぎると周りが見えなくなります。</p>
<p>家族があってこその自分。顧客・仲間があってこその会社。</p>
<p>私たちは今までの全ての人への感謝を忘れません。</p>
</div>
</div><!-- /credoList5 -->

<div class="credoList">
<div class="credoBlock f-l">
<img src="common/img/credo/06.png" alt="世の中の常識を変え、自分の常識を超える" width="750" height="401">
</div>
<div class="credoBlock f-r">
<p class="credoNumber">CREDO<span>06</span></p>
<p class="credoTitle">世の中の常識を変え、自分の常識を超える</p>
<p>常識に頼ることは己の考えを放棄すること。</p>
<p>そんなもんで、世の中を変えられる？</p>
<p>常識なんて、人間の思い込みにすぎません。</p>
<p>世の中の常識を疑おう。ついでに壊しちゃえ。</p>
<p>私たちは周りに惑わされず、己で己を超えていきます。</p>
</div>
</div><!-- /credoList6 -->

<div class="credoList">
<div class="credoBlock f-r">
<img src="common/img/credo/07.png" alt="365日成長する" width="750" height="401">
</div>
<div class="credoBlock f-l">
<p class="credoNumber">CREDO<span>07</span></p>
<p class="credoTitle">365日成長する</p>
<p>今や、世の中の変化はとても激しいです。</p>
<p>立ち止まったら、何も変わらないどころじゃない。</p>
<p>あっという間に置いてけぼりにされてしまうこともあります。</p>
<p>成長するための最大の敵は自分自身。</p>
<p>完璧だと思った瞬間に、成長は止まり、腐敗が始まる。</p>
<p>私たちは毎日成長する姿勢を貫きます。昨日の自分よりレベルアップします。</p>
</div>
</div><!-- /credoList7 -->

<div class="credoList">
<div class="credoBlock f-l">
<img src="common/img/credo/08.png" alt="本気の変人を本気で応援する" width="750" height="401">
</div>
<div class="credoBlock f-r">
<p class="credoNumber">CREDO<span>08</span></p>
<p class="credoTitle">本気の変人を本気で応援する</p>
<p>世の中を変えるのはいつも変人でした。</p>
<p>その変人は世の中に否定され続けてきました。</p>
<p>周りに仲間がいない。否定されるのが怖い。</p>
<p>そう思うのはわかる。でも大切な価値観をなくさない。</p>
<p>その価値観を持ち続けたのが、本気の変人だから。</p>
<p>そんな覚悟を決めた変人を、本気で応援するのが私たち。</p>
<p>本気の変人同士、やるならやるで本気で世の中を変えていきます。</p>
</div>
</div><!-- /credoList8 -->

<div class="credoList">
<div class="credoBlock f-r">
<img src="common/img/credo/09.png" alt="明日、死んでもいい行動をする" width="750" height="401">
</div>
<div class="credoBlock f-l">
<p class="credoNumber">CREDO<span>09</span></p>
<p class="credoTitle">明日、死んでもいい行動をする</p>
<p>失敗を恐れて行動しなかった人は死ぬときにどう感じるか？</p>
<p>今日死んでしまった人は最後の１日をどのように感じたか？</p>
<p>人間はずっと生きることはできない。 いつか人生に終わりが来る。</p>
<p>だから、私たちは今日１日１日を全力で生きます。</p>
<p>残りの時間を意識しなければしないほど、何もしなくなるから。</p>
<p>悔いは残っても、後悔だけは残さない人生を送ります。</p>
</div>
</div><!-- /credoList9 -->

<div class="credoList">
<div class="credoBlock f-l">
<img src="common/img/credo/10.png" alt="まずは自分が楽しめ。満たせ。" width="750" height="401">
</div>
<div class="credoBlock f-r">
<p class="credoNumber">CREDO<span>10</span></p>
<p class="credoTitle">まずは自分が楽しめ。満たせ。</p>
<p>人生、思いっきり楽しんでる？人生、幸せで満たされてる？</p>
<p>いくら世のため人のためといっても、自分が楽しくなければ続かない。</p>
<p>いくら成功しても稼げても、自分が満たされなければ幸せになれない。</p>
<p>でも、楽しんでいると感じていれば、周りも勝手に楽しくなります。</p>
<p>幸せに満たされていれば、幸せで満たされている仲間が集まってきます。</p>
<p>私たちは今ある状況を楽しみます。人間に生まれてきた幸せを体全体で感じます。</p>
</div>
</div><!-- /credoList10 -->

<div class="credoList">
<div class="credoBlock f-r">
<img src="common/img/credo/11.png" alt="予想を裏切るスピードで" width="750" height="401">
</div>
<div class="credoBlock f-l">
<p class="credoNumber">CREDO<span>11</span></p>
<p class="credoTitle">予想を裏切るスピードで</p>
<p>この世で唯一、平等なもの。それは時間。</p>
<p>どんな人間にも１日は24時間しかありません。</p>
<p>この時間の使い方で人生の質が決まります。</p>
<p>どんなアイデアも既にどっかの誰かが思いついてる。</p>
<p>だったら、後は実現までの勝負。そう感じてからが本番だ。</p>
<p>ライバルだけじゃない、私たちは仲間も驚くスピードで突っ走ります。</p>
</div>
</div><!-- /credoList11 -->

<div class="credoList">
<div class="credoBlock f-l">
<img src="common/img/credo/12.png" alt="誇りをもって、一流の仕事をする" width="750" height="401">
</div>
<div class="credoBlock f-r">
<p class="credoNumber">CREDO<span>12</span></p>
<p class="credoTitle">誇りをもって、一流の仕事をする</p>
<p>「なんだこのふざけた名前の会社は！」そう感じてくれたならごもっとも。</p>
<p>ふざけるのも誇りと責任を持って全力でやってるから。ビジネスも同じ。</p>
<p>「なんだこの面白い会社は！」そうツッコんでくれたら万々歳。</p>
<p>常に本気。何に対しても。それがいないいないばぁ。</p>
<p>一流であれ。どんな時も。それがいないいないばぁ。</p>
<p>誇りと責任を持って、私たちは最高のアウトプットを提供します。</p>
</div>
</div><!-- /credoList12 -->
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
