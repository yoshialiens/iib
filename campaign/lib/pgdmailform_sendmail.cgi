##### pgdmailform_sendmail.cgi
# 
# pgdmailform.cgi sendmail
# 
# by am@pgd.jp


package PgdMailFormSendmail;

$PgdMailFormSendmail::sendmail_path = '/usr/sbin/sendmail';

sub sendmail {
	
	use Jcode;
	
	# Unicode::Japanese が利用できれば読み込む
	my $use_unicode_japanese = 0;
	if (eval 'use Unicode::Japanese; 1') { $use_unicode_japanese = 1;}
	
	# 引数の読み込み
	my ($maildata) = @_;
	
	# メールサブジェクト・From・To・メール本文をコード変換
	# Jcode の場合
	if (!$use_unicode_japanese) {
		$maildata->{'Subject'} = Jcode->new($maildata->{'Subject'})->jis; 
		$maildata->{'From'} = Jcode->new($maildata->{'From'})->jis; 
		$maildata->{'To'} = Jcode->new($maildata->{'To'})->jis; 
		$maildata->{'Data'} = Jcode->new($maildata->{'Data'})->jis; 
	# Unicode::Japanese が利用できればJcodeの変わりに利用する。波ダッシュなど一部文字の文字化け防止
	} elsif ($use_unicode_japanese) {
		$maildata->{'Subject'} = Unicode::Japanese->new($maildata->{'Subject'},'auto')->jis; 
		$maildata->{'From'} = Unicode::Japanese->new($maildata->{'From'},'auto')->jis; 
		$maildata->{'To'} = Unicode::Japanese->new($maildata->{'To'},'auto')->jis; 
		$maildata->{'Data'} = Unicode::Japanese->new($maildata->{'Data'},'auto')->jis; 
	}
	
	# メールサブジェクト・From・ToをMIMEエンコード
	$maildata->{'Subject'} = Jcode->new($maildata->{'Subject'})->mime_encode;
	$maildata->{'From'} = Jcode->new($maildata->{'From'})->mime_encode;
	$maildata->{'To'} = Jcode->new($maildata->{'To'})->mime_encode;
	
	my $body;
	$body = "To: $maildata->{'To'}\n";
	$body .= "From: $maildata->{'From'}\n";
	if ($maildata->{'Cc'}) { $body .= "Cc: $maildata->{'Cc'}\n";}
	if ($maildata->{'Bcc'}) { $body .= "Bcc: $maildata->{'Bcc'}\n";}
	$body .= "Subject: $maildata->{'Subject'}\n";
	$body .= "MIME-Version: 1.0\n";
	$body .= "Content-type: text/plain; charset=\"ISO-2022-JP\"\n";
	$body .= "Content-Transfer-Encoding: 7bit\n";
	$body .= "\n";
	$body .= "$maildata->{'Data'}\n";
	
	open(MAIL,"| $PgdMailFormSendmail::sendmail_path -t -i") or return;
		print MAIL "$body\n";
	close(MAIL);
	
}

1;

