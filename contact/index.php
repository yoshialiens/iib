<?php

//文字コード
$ARYconf['charsetHTML']			= 'UTF-8';
$ARYconf['charsetPHP']			= 'utf8';

mb_language('ja');										//日本語指定
mb_internal_encoding($ARYconf['charsetPHP']);

require_once './php_lib/D_func.php';				//D_func.phpの読み取り

//テンプレートファイル名
$ARYconf['templateFolder']		= './template/';

$ARYconf['templateInput']		= 'entry.html';
$ARYconf['templateCheck']		= 'reentry.html';
$ARYconf['templateComplete']	= 'check.html';
$ARYconf['templateFin']			= 'entry.html';

$ARYconf['FormInput']			= 'form_input.html';
$ARYconf['FormCheck']			= 'form_check.html';

$ARYconf['BULogFileDays']			= 0;				//0＝書き出さない 1 >＝ 書き出す＆数字の日数だけファイルを保存
$ARYconf['BULogFolderPath']			= './tmp/';				//ログフォルダを書き出すフォルダ名（注意！：末尾に｢/｣必須。）
$ARYconf['BULogFileTest']			= 0;				//0＝テスト時は設定に関わらず書き出さない 1>＝ テスト時も書き出す

$ARYconf['isEqaul']['for']		= 'f_mail';			//特殊拡張isEqaulと比較するフォーム名
$ARYconf['userMainEmail']		= 'f_mail';			//自動返信メールを送信する先のＥ－ｍａｉｌを入力させるフォーム名

//本番設定 （送信先メールアドレス、完了画面)
$ARYconf['hensinMailPP']		= 'takuro@i-i-b.jp';		//本番用通知メールＦｒｏｍアドレス'
$ARYconf['clientMailPP']		= 'takuro@i-i-b.jp';		//本番用通知メール送信先テスト用
$ARYconf['jumpUrlFinPP']		= 'entry.html';			//送信完了ページ $ARYconf['templateFin']が空の場合有効

//メール設定
$ARYconf['hensinMailSubject']	= '【株式会社いないいないばぁ】オフィシャルサイトへお問い合わせありがとうございます。';
$ARYconf['hensinMailTemplate']	= 'hensinMail.dat';
$ARYconf['clientMailSubject']	= '【株式会社いないいないばぁ】オフィシャルサイトからお問合せを承りました。';
$ARYconf['clientMailTemplate']	= 'clientMail.dat';

//表示文言
$ARYconf['hissu']['f_name']			= '<p class="fc-red">お名前を入力してください。</p>';
$ARYconf['hissu']['f_title']			= '<p class="fc-red">お問い合わせ種別を選択してください。</p>';
$ARYconf['hissu']['f_text']			= '<p class="fc-red">お問合わせ詳細を入力してください。</p>';


$ARYconf['hissu']['f_mail']			= '<p class="fc-red">メールアドレスを入力して下さい。</p>';
$ARYconf['email']['f_mail']			= '<p class="fc-red">メールアドレスに使えない文字が入力されていませんか？</p>';
$ARYconf['hissu']['f_mail2']			= '<p class="fc-red">メールアドレスを入力して下さい。</p>';
$ARYconf['email']['f_mail2']			= '<p class="fc-red">メールアドレスに使えない文字が入力されていませんか？</p>';
$ARYconf['isEqaul']['f_mail2']	= '<p class="fc-red">メールアドレスの入力が一致していません。同じアドレスを2回ご入力ください。</p>';


$FORM = new kichiform(60);
?>