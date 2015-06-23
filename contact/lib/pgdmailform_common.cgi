##### pgdmailform_common.cgi
# 
# pgdmailform.cgi common
# 
# by am@pgd.jp


package PgdMailFormCommon;

# テンプレート $template_dir . "TYPE/" . $template_xxxx
$PgdMailFormCommon::template_dir = './template/';
$PgdMailFormCommon::template_form = 'form.html';
$PgdMailFormCommon::template_check = 'check.html';
$PgdMailFormCommon::template_send = 'send.html';
$PgdMailFormCommon::template_mail = 'mail.txt';
$PgdMailFormCommon::template_mail_sender = 'mail_sender.txt';

# 入力サイズの最大値
$PgdMailFormCommon::max_size = 10000;

1;
