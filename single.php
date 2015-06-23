<?php
	require_once dirname(__FILE__) . '/scripts/model/ItemModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';
	require_once dirname(__FILE__) . '/scripts/model/CommentModel.class.php';

	$review_id = (int)@$_GET['review_id'];
	$review_model = new ReviewModel();
	$review_data = $review_model->getReview($review_id);
	// if(empty($review_data)){
	// 	header("location: index.php");
	// 	exit;
	// }
	//掲載不可ならindex.phpへリダイレクト
	// if($review_data['enable'] != 1){
	// 	header("location: index.php");
	// 	exit;
	// }

	//side.phpでitem_idを参照
	$item_id = $review_data['item_id'];


	$comment_model = new CommentModel();
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//POSTリクエストでコメントの書き込み
		$comment_data = array(
			'review_id' => $review_id,
			'name' => $_POST['name'],
			'comment' => $_POST['comment'],
		);


		//荒らし対策
		mb_regex_encoding('UTF-8');
		$error = false;
		if(mb_ereg_match(".*href",$comment_data['comment']))$error = true;
		if(!mb_ereg_match(".*[あ-ん]",$comment_data['comment']))$error = true;

		if(!$error){
			$comment_model->setComment($comment_data);
			$review_model->updateReview($review_id, array('update_time'=>BaseModel::NOW));
		}

		//元のクチコミ画面にリダイレクト
		header("location: rep.php?review_id={$review_id}");
		exit;
	}
	$comment_all = $comment_model->select(array('review_id'=>$review_id, 'enable'=>1), array('comment_id'=>BaseModel::ORDER_DESC));
	foreach($comment_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		$v['comment'] = htmlspecialchars($v['comment'], ENT_QUOTES, 'UTF-8');
		$v['date'] = date("Y/m/d", strtotime($v['update_time']));
		unset($v);
	}
	
	//会社データの取得
	$item_model = new ItemModel();
	$item = $item_model->getItem($review_data['item_id']);
	if(empty($item)){
		header("location: index.php");
		exit;
	}
	
	
	//順位の取得
	$item['rank'] = $item_model->getRank($review_data['item_id']);
		
	//レビュー数
	$review_count = $review_model->getReviewCount($review_data['item_id']);
	
	//オススメ度
	$review_data['star'] = ItemModel::getOsusumeStarTag($review_data['point']);
	
	//表情アイコン
	$review_data['face_image'] = ReviewModel::getFaceImage($review_data['image']);
	
	//その他のレビュー
	$other_review_all = $review_model->getReviewAllByItemId($item['item_id']);
	foreach($other_review_all as &$v)
	{
		$v['name'] = htmlspecialchars($v['name'], ENT_QUOTES, 'UTF-8');
		$v['title'] = htmlspecialchars($v['title'], ENT_QUOTES, 'UTF-8');
		$text = mb_strimwidth($v['text'], 0, 210, '･･･', 'UTF-8');
		$v['text'] = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		$v['date'] = date("Y/m/d", strtotime($v['update_time']));
		$v['face_image'] = ReviewModel::getFaceImage($v['image']);
		$v['star'] = ItemModel::getOsusumeStarTag($v['point']);
		unset($v);
	}
	

	
	//HTML表示用
	$review_data['name'] = htmlspecialchars($review_data['name'], ENT_QUOTES, 'UTF-8');
	$review_data['title'] = htmlspecialchars($review_data['title'], ENT_QUOTES, 'UTF-8');
	$review_data['good'] = htmlspecialchars($review_data['good'], ENT_QUOTES, 'UTF-8');
	$review_data['bad'] = htmlspecialchars($review_data['bad'], ENT_QUOTES, 'UTF-8');
	$review_data['text'] = htmlspecialchars($review_data['text'], ENT_QUOTES, 'UTF-8');
	$review_data['date'] = date("Y/m/d", strtotime($review_data['update_time']));
	
	//ソーシャルボタン用
	$server_name = $_SERVER['SERVER_NAME'];
	$social_url = urlencode("http://{$server_name}/single.php?item_id={$item['item_id']}");
	$url = "http://{$server_name}/single.php?item_id={$item['item_id']}";

	//総合満足度★タグの取得
	$osusume_star_tag = ItemModel::getOsusumeStarTag($item['point']);	
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta property="og:title" content="<?php echo $item['name']; ?>">
<meta property="og:site_name" content="株式会社いないいないばぁ">
<meta property="og:type" content="article">
<meta property="og:url" content="http://www.i-i-b.jp/company.php">
<meta property="og:image" content="http://www.i-i-b.jp/common/img/page/○○.png">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
<meta name="keywords" content="会社概要,サプライズマーケティング">
<meta name="description" content="株式会社いないいないばぁは日本一のサプライズマーケティング会社です。口コミやリピートを増やすためにサプライズを提案し、人々に喜んでもらうために動いてもらいます。">
<title><?php echo $item['name']; ?>|株式会社いないいないばぁ</title>
<link rel="apple-touch-icon-precomposed" href="common/img/home-icon.png" />
<link rel="shortcut icon" href="common/img/favicon.ico" />
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="all">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="common/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]><script src="common/js/minmax.js"></script><![endif]-->
</head>


<body id="blog">
<div id="wrapper">
<header id="header">
<div class="pcView"><!-- /PC MENU -->
<div class="logo"><a href="http://i-i-b.jp/"><img src="common/img/bnr/logo.png" alt="株式会社いないいないばぁ" width="150" height="40"></a></div>
<div class="h-sec">
<h1><?php echo $item['name']; ?> - 株式会社いないいないばぁ</h1>
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
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="http://www.i-i-b.jp/category/business" itemprop="url"><span itemprop="title">マーケティング</span></a></li>
<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" itemprop="child"><a href="http://www.i-i-b.jp/20150530-3739.html" itemprop="url"><span itemprop="title">マーケティングに必須となりうる「ビッグデータ」の価値</span></a></li>
</ul>
</div><!-- /パンくず -->

<div class="Block">
<div class="Cont">
<article>
<p class="date"><span class="icon-">&#xe905; </span>2015.06.20</p>
<h1 class="title">マーケティングに必須となりうる「ビッグデータ」の価値</h1>
<figure class="thumbnail">
<img src="common/img/mv/thumbnail01.jpg" alt="サムネイル" width="800" height="">
</figure>
<p>2011年ごろから「ビッグデータ」という言葉を耳にするようになりました。</p>
<p>弊社でもスモールマーケットからビッグマーケットまで、<br>
データ抽出のためのシステム制作をたびたび受けるようになり、<br>
こういったデータの扱いや価値というものに着目するようになりました。</p>
<p>「ビッグデータってよく聞くけど実はよくわからない」という人のために、<br>
ざっくりと理解できる基本的なポイント解説をしたいと思います。</p>

<h2 class="">そもそもビッグデータって何？</h2>
<p>パソコンやスマホでGoogleの検索ボックスにキーワードを入力したとき、<br>
または<a href="#">キーワード</a>を途中まで入力した時<br>
その候補となるキーワードがなぜ自動的に出てくるのか、<br>
不思議に思った経験はないでしょうか？</p>
<p class="blogImg"><img src="http://www.i-i-b.jp/wp-content/uploads/2015/06/20150601-01.png" alt="mtest"></p>
<p>他の例だと、みなさんもう日常的なものとなっているかと思いますが、<br>
Amazon、楽天ショップなどでおすすめ商品が表示されたり、<br>
なんか前に見たサイトの広告が他のサイト見ているときに追いかけてきたり<br>
どういう仕組みなんだと不思議に思いませんか?</p>

<h3>Internetにおけるみなさんの行動履歴はビッグデータの一部</h3>
<p>私達の行動履歴、つまり「検索」「WEBページへのアクセス」といった行動は<br>
ビッグデータの一部です。</p>
<p>最初にあげたGoogle検索についてわかりやすく説明すると、<br>
大勢の人々が入力したキーワードを集計し、傾向を見ることで、<br>
「この言葉の後にはこの言葉で検索されるだろう」という予測に基づいて表示されています。</p>

<h2>ビッグデータとラージデータの違い</h2>
<p class="blogImg"><img src="http://www.i-i-b.jp/wp-content/uploads/2015/06/20150601-03.png" alt="mtest"></p>
<p class="sub">※写真はイメージです。</p>
<p>どちらもそのまま日本語に訳すと「大きなデータ」となるので<br>
同じものと思われている方もいるかもしれませんが<br>
両者の大きな違いは「データの質」にあります。</p>

<h3>ラージデータの例</h3>
<p>例えば銀行などの金融機関の入出金履歴のデータ、<br>
小売店のPOS（販売管理）データといったものはラージデータに当てはまります。</p>
<p>それに対してビッグデータには、データ自体の粒度の細かさが存在します。</p>
<p>オンラインショッピングで購入をすれば、<br>
年齢、性別、年齢、住所、どうやって商品ページにたどり着いたのか、<br>
そのページにどれくらい滞在して注文したのか、<br>
どのページでサイトから離脱したのか、<br>
こういった行動履歴（データの粒度）までを取得できるのがビッグデータです。</p>
<p>なんとなくイメージがつきましたでしょうか?</p>

<h2>モバイルがビッグデータを加速させる</h2>
<p>今までインターネットは「家」、「職場」といった<br>
いわゆる「点」でとらえられていたものが<br>
スマホの普及により、流動的、「面」という形で<br>
行動履歴と残すようになりました。</p>
<p>さらにGPSやSNSに投稿された写真などの情報も加わって、<br>
この行動履歴がビッグデータを形成していきます。</p>
<p>現在ビッグデータに最も影響を与えているものは、<br>
モバイルデバイスといっても過言ではないでしょう。</p>

<h2>ITにおける全ての行動がビッグデータになる！</h2>
<p>モバイル、ソーシャルネットワーク、クラウドといった情報通信の技術の発展により、<br>
私たちの生活、仕事の仕方にも大きく影響が出てきたように感じます。</p>
<p>今後も様々なデバイスが生まれ、インターネットを介してつながって、<br>
膨大な量の個人の行動履歴が蓄積されることになります。</p>
<p>改めてビッグデータに注目が集まっているのは、<br>
人々の行動履歴から行動パターンなども読み取れるようになり、<br>
その情報の価値がマーケティングにおいて非常に重要なものであると認識され、<br>
そしてようやく現実的に活用できる段階に入ってきたからだと言えるでしょう。</p>
<p>弊社でもその一つのツールとして、動画解析のツールを一般向け、企業向けと<br>
サービスとして提供を始めました。</p>
<p>動画時代とも言われている昨今、閲覧者のペルソナ、行動パターンもまた<br>
広告活用する上でも非常に大きな役割を果たせるのではないかと感じています。</p>
<p>YouTube動画運用されている方はぜひお試しでご利用してみてください</p>

<div class="boxline">
<p>YouTube動画運用されている方はぜひお試しでご利用してみてください</p>
</div>


<!--h4>h4マーケティングに必須となりうる「ビッグデータ」の価値</h4>
<p class="blogImg"><img src="common/img/mv/thumbnail01.jpg" alt="mtest"></p>
<blockquote>3マーケティングに必須となりうる3マーケティングに必須となりうる</blockquote>
<p>3マーケティングに必須となりうる<a href="#">「ビッグデータ」</a>の価値</p-->
</article>
</div><!-- /Cont -->
</div><!-- Block -->


<div class="Block Blue">
<div class="Cont">
<h3>RECOMMEND</h3>
<p class="subTitle">よく読まれている記事です。</p>
<ul class="blog">
<li>
<div class="blogImg shake"><a href="#"><img src="common/img/blog/006.jpg" alt="サムネイル" width="300" height="300"></a></div>
<div class="blogTitle"><a href="#">台湾少女の1日を密着！？台湾少女の1日を密着！？</a></div>
<div class="blogEx"><p>テストテスト抜粋のテストテストテスト抜粋のテストテストテスト抜粋のテストテストテスト抜粋のテスト</p></div>
<div class="moreBtn"><a href="#"><img src="common/img/index/more-btn.jpg" alt="もっと読む" width="74" height="20"></a></div></li>

<li>
<div class="blogImg shake"><a href="#"><img src="common/img/blog/002.jpg" alt="サムネイル" width="" height=""></a></div>
<div class="blogTitle"><a href="#">台湾少女の1日を密着！？台湾少女の1日を密着！？</a></div>
<div class="blogEx"><p>テストテスト抜粋のテスト</p></div>
<div class="moreBtn"><a href="#"><img src="common/img/index/more-btn.jpg" alt="もっと読む" width="74" height="20"></a></div></li>

<li>
<div class="blogImg shake"><a href="#"><img src="common/img/blog/014.jpg" alt="サムネイル" width="" height=""></a></div>
<div class="blogTitle"><a href="#">台湾少女の1日を密着！？</a></div>
<div class="blogEx"><p>テストテスト抜粋のテスト</p></div>
<div class="moreBtn"><a href="#"><img src="common/img/index/more-btn.jpg" alt="もっと読む" width="74" height="20"></a></div></li>
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
