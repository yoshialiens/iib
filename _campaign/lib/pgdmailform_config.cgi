##### pgdmailform_config.cgi
# 
# pgdmailform.cgi config
# 
# by am@pgd.jp


package PgdMailFormConfig;

##### テンプレート タイプの設定

# 動作するテンプレートタイプの設定
# 0 を設定すると利用停止
%PgdMailFormConfig::type_available = (
	'default' => 1,
);


##### 設置者向けメール設定

# 宛先メールアドレス
%PgdMailFormConfig::mail_to = (
	'default' => 'tm-script_test@pgd.jp',
);

# サブジェクト テンプレートごとに設定
%PgdMailFormConfig::mail_subject = (
	'default' => 'メールフォーム',
);

# 追加の宛先メールアドレス(CC)
%PgdMailFormConfig::mail_cc = (
	#'default' => 'tm-script_test_bcc@pgd.jp',
);

# 追加の宛先メールアドレス(BCC)
%PgdMailFormConfig::mail_bcc = (
	#'default' => 'tm-script_test_bcc@pgd.jp',
);


##### 自動応答メールの設定

# テンプレート mail_sender.txt が存在すれば自動応答を行う
# 自動応答を行う場合は以下を設定すること

# Fromアドレス 設定しない場合は「メールの宛先」が利用される
%PgdMailFormConfig::mail_sender_to = (
	#'default' => '',
);

# サブジェクト テンプレートごとに設定
%PgdMailFormConfig::mail_sender_subject = (
	'default' => 'メールフォーム 自動返信',
);

##### その他の設定

# テンプレートがUTF-8の場合にハイフン・チルダの文字化け対策を行う
$PgdMailFormConfig::utf8_char_fix = 1;
#   カッコ文字、ローマ数字等は文字化けする。
#   Unicode::Japanese がインストールされていれば設定しなくても回避可能

1;
