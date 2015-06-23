#!/usr/bin/perl

################################################################################
# 
# 汎用メールフォーム pgdmailform.cgi 用 テンプレートURLエンコード・デコード
# PGD WORKS by Akira Motojima
# http://pgd.jp/
# 
#-------------------------------------------------------------------------------
# 
# ○ 実行
# 
# コマンドラインから
# ./pgdmailform_tmplenc.pl encode|decode < form.html > form_NEW.html
# 
#-------------------------------------------------------------------------------
# 
# ○ 履歴
# 
# 2008/04/17 デコードに対応
# 2008/04/08 作成
# 
################################################################################


##### require


use strict;


##### run

my $data;
while (<STDIN>) {
	
	$data .= $_;
	
}

# 処理対象一覧を取得
my @match_str = $data =~ /TMPL_IF\s+name=\"[\w\_]+\-[^\"]+/g;

# 処理後の文字列を生成
my %str2code;
foreach my $str (@match_str) {
	
	# 処理する/しない箇所を検出
	$str =~ /(TMPL_IF\s+name=\"[\w\_]+\-)([^\"]+)/g;
	my $str_not = $1;
	my $str_do = $2;
	
	# デコードの場合
	if ($ARGV[0] eq 'decode') {
		
		$str_do =~ tr/+/ /;
		$str_do =~ s/%([0-9A-Fa-f][0-9A-Fa-f])/pack('H2', $1)/eg;
		
	# エンコードの場合
	} else {
		
		$str_do =~ s/([^a-zA-Z0-9_.!~*'()-])/'%'.unpack('H2',$1)/eg;
		
		# エンコード不要だった場合スキップ
		if ($str_do !~ /\%\w\w/) { next;}
		
	}
	
	$str2code{$str} = $str_not . $str_do;
	
}

# 1項目ずつ置換を行う
foreach my $str (keys %str2code) {
	
	$data =~ s/\Q$str\E/$str2code{$str}/g;
	
}

print $data;

exit;