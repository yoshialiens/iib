<?php
/*
Ver 2.0.05112009 utf8ver
*/
class kichiform{
	function kichiform($limit = 0){//コンストラクタ
		global $ARYconf;
		mb_language('ja');												//日本語指定
		mb_internal_encoding('UTF-8');
		mb_http_output($ARYconf['charsetPHP']);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		//added on 200911
		mb_convert_variables("UTF-8", $ARYconf['charsetPHP'], $ARYconf);
		
		//deleted on 20091105
		//ob_start('mb_output_handler');
		$this->hiddenForm = "";
		$this->error = 0;
		$ARYconf['testMode']	= '0';
		
		if(! isset($ARYconf['testHostName'])){
			$ARYconf['testHostName'] = "test";
		}else{
			$ARYconf['testHostName'] = $ARYconf['testHostName'];
		}
		if(! isset($ARYconf['webRootPath'])){
			$ARYconf['webRootPath'] = "..";
		}
		
		if(isset($_SERVER['HTTP_HOST'])){
			if(preg_match('/'.$ARYconf['testHostName'].'/i',$_SERVER['HTTP_HOST'])){
				$ARYconf['testMode']	= '1';							//1=テストモード 0=本番モード
			}else{
				$ARYconf['testMode']	= '0';							//1=テストモード 0=本番モード
			}
		}
		if(isset($_SERVER['REQUEST_URI'])){
			if(preg_match('/'.$ARYconf['testHostName'].'/i',$_SERVER['REQUEST_URI'])){
				$ARYconf['testMode']	= '1';							//1=テストモード 0=本番モード
			}
		}
		
		if(($ARYconf['testMode'] == 1)&&($ARYconf['BULogFileTest'] == 0)){//テストモード時のログファイル書き出しが禁止されている
			$ARYconf['BULogFileDays'] = 0;//ログの書き出しをOFFにする
		}
		
		if($ARYconf['testMode']){
			$ARYconf['hensinMail']	= $ARYconf['hensinMailTest'];
			$ARYconf['clientMail']	= $ARYconf['clientMailTest'];
			$ARYconf['jumpUrlFin']	= $ARYconf['jumpUrlFinTest'];
		}else{
			$ARYconf['hensinMail']	= $ARYconf['hensinMailPP'];
			$ARYconf['clientMail']	= $ARYconf['clientMailPP'];
			$ARYconf['jumpUrlFin']	= $ARYconf['jumpUrlFinPP'];
		}

		if(isset($_POST['mode'])){//モードが存在＝2ページ目以降
			$this->mode = $_POST['mode'];
			switch($this->mode){
				case "check":
				case "complete":
					$this->completeData = file_get_contents($ARYconf['templateFolder'].$ARYconf['FormCheck']);
					$this->completeData = mb_convert_encoding($this->completeData, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
					break;
				case "send":
					$this->hensinMailTe = file_get_contents($ARYconf['templateFolder'].$ARYconf['hensinMailTemplate']);
					$this->hensinMailTe = mb_convert_encoding($this->hensinMailTe, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
					$this->clientMailTe = file_get_contents($ARYconf['templateFolder'].$ARYconf['clientMailTemplate']);
					$this->clientMailTe = mb_convert_encoding($this->clientMailTe, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
					break;
			}
			$this->formData = file_get_contents($ARYconf['templateFolder'].$ARYconf['FormInput']);
			$this->formData = mb_convert_encoding($this->formData, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
			$this->formData = preg_replace('/ selected\="selected"/','',$this->formData,999);
			$this->formData = preg_replace('/ checked\="checked"/','',$this->formData,999);
			foreach($ARYconf['hissu'] as $key => $value){//必須文言がセットされている場合、POSTでその値がセットされているか確認
				if(! isset($_POST[$key])){
					if((isset($ARYconf['hissuCheck'][$key]))&&($ARYconf['hissuCheck'][$key] > 0)){//必須がチェックボックスでセットされていて、なおかつ、その数値が０以上の時
						$count = 0;
						for($i=0;$i<20;$i++){
							if(isset($_POST[$key."_".$i])){//セットされていない場合、空白でセットする
								$count ++; 
							}
						}
						$this->hissuCheck[$key] = $count;
					}else{//セットされていない場合、空白でセットする
						$_POST[$key] = ""; 
					}
				}
			}
			//■mb_strlen()
			$this->formData = $this->resData($this->formData);
			$this->formData = $this->deleteExTag($this->formData);

			if(isset($_GET['test'])){//テストモード
				echo "\n".$this->completeData;
				echo "\n".$this->hensinMailTe;
				echo "\n".$this->clientMailTe;
				var_dump($_POST);
				exit();
			}
			if($this->error == 1){
				$this->mode = "check";
			}elseif($this->mode == "check"){
				$this->mode = "complete";
			}
		}

		//追加処理ファイルがあれば、処理を実行
		if(file_exists('./ex_exe.php')){
			include './ex_exe.php';
		}
		
		//携帯最新機種への対応 Mozillaの時だけtext/htmlにする
		$agent = $_SERVER["HTTP_USER_AGENT"];
		if(preg_match('/DoCoMo/',$agent)){
			$conType = "application/xhtml+xml";
		}else{
			$conType = "text/html";
		}
		
		switch($this->mode){
			default:
				//ヘッダー出力		
				header("Content-Type: ".$conType."; charset=".$ARYconf['charsetHTML']);
				$bodyData = $this->htmlInclude($ARYconf['templateFolder'].$ARYconf['templateInput'],"check");
				$bodyData = $this->deleteExTag($bodyData);
				$this->echo_page($bodyData);
				break;
			case "check":
				$this->nowMode = "check";
				//ヘッダー出力		
				header("Content-Type: ".$conType."; charset=".$ARYconf['charsetHTML']);
				$bodyData = $this->htmlInclude($ARYconf['templateFolder'].$ARYconf['templateCheck'],"complete");
				$this->echo_page($bodyData);
				break;
			case "complete":
				//ヘッダー出力		
				header("Content-Type: ".$conType."; charset=".$ARYconf['charsetHTML']);
				$bodyData = $this->htmlInclude($ARYconf['templateFolder'].$ARYconf['templateComplete'],"send");
				$this->echo_page($bodyData);
				break;
			case "send":
				if(isset($_GET['test'])){//テストモード
					echo "\n".$this->completeData;
					echo "\n".$this->hensinMailTe;
					echo "\n".$this->clientMailTe;
					var_dump($_POST);
					exit();
				}
				
				//連続投稿禁止モード
				$r = 0;
				if($limit > 0){
					if(($ARYconf['BULogFolderPath'] != '')&&(file_exists($ARYconf['BULogFolderPath']))){
						$filename= $ARYconf['BULogFolderPath']."timer.dat";
						$mae = file($filename);
						$FileH = fopen($filename,"w");		//tmpにファイル名(日付時間+「_」置換メールアドレス)を指定してwモードでファイル展開。
						fwrite($FileH,time().",".$_SERVER['REMOTE_ADDR']);						//クライアント、ユーザのメールアドレスと本分をdatに書き込み
						fclose($FileH);
						
						$ARY_i = explode(",",$mae[0]);
						if(($ARY_i[1] == $_SERVER['REMOTE_ADDR'])&&((time() - $ARY_i[0]) < $limit)){ $r = 1; }//連続投稿発生
					}else{
						echo '<strong>連続投稿の禁止が設定されていますが、ログの書き込みフォルダの設定が不正なため機能しません。</strong>';
					}
				
				}
				if($r == 0){
					$userEmail = $_POST[$ARYconf['userMainEmail']];
					$this->sendMail($userEmail,1);
				}
				if((isset($ARYconf['templateFin']))&&(file_exists($ARYconf['templateFolder'].$ARYconf['templateFin']))&&($ARYconf['templateFin'] != "")){//読み込み表示の設定があり、そのテンプレートファイルが存在している
					//ヘッダー出力
					header("Content-Type: ".$conType."; charset=".$ARYconf['charsetHTML']);
					$bodyData = $this->htmlInclude($ARYconf['templateFolder'].$ARYconf['templateFin'],'fin');
					
					$userEmail = preg_replace('/\./','_',$userEmail);
					$userEmail = urlencode($userEmail);
					$bodyData = preg_replace('/<!--UserEmail-->/',$userEmail,$bodyData);
					$this->echo_page($bodyData);
				}else{//読み込み表示の設定がない
					if((! isset($ARYconf['jumpUrlFin']))||($ARYconf['jumpUrlFin'] == "")){
						exit("No set jump location.");
					}
					header('Location: '.$ARYconf['jumpUrlFin']);
					exit();
				}
				break;
		}

		if(isset($_GET['test'])){//テストモード
			echo "\n ->error:".$this->error."\n mode:".$this->mode."\n";
			var_dump($_POST);
		}
	}
	
	function echo_page($bodyData){
		global $ARYconf;
		//$bodyData = mb_convert_encoding($bodyData, $ARYconf['charsetPHP'], "UTF-8");		//文字コードを変換
		//echo $bodyData;
		
		//added on 20091105
		$outputData = mb_convert_encoding($bodyData, $ARYconf['charsetPHP'], mb_internal_encoding());
		echo $outputData;
	}

	function htmlInclude($fileName,$nextMode){
		global $ARYconf;
		if(!file_exists($fileName)){ return; }
		$bodyData = file_get_contents($fileName);
		$bodyData = mb_convert_encoding($bodyData, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
		while(preg_match('/<!--#include virtual\=\"([\w\.\/]+)\"(\s*)-->/',$bodyData,$ARY_str)){
			if(preg_match('/^\//',$ARY_str[1])){
				//old $filePath = "..".$ARY_str[1];
				$filePath = $ARYconf['webRootPath'].$ARY_str[1];
			}else{
				$filePath = $ARY_str[1];
			}
		
			if(($filePath == $ARYconf['templateFolder'].$ARYconf['FormInput'])&&($this->nowMode == 'check')){//チェックでフォーム部分タグを読む時
				$subData = $this->formData;
			}elseif($filePath == $ARYconf['templateFolder'].$ARYconf['FormCheck']){//チェックでフォーム部分タグを読む時
				$subData = $this->deleteExTag($this->completeData);
			}elseif(file_exists($filePath)){
				$subData = file_get_contents($filePath);
				$subData = mb_convert_encoding($subData, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
			}else{
				$subData = "";
			}
			$bodyData = preg_replace('/<!--#include virtual\=\"([\w\.\/]+)\"(\s*)-->/',$subData,$bodyData,1);
		}
		
		$bodyData = preg_replace('/<!--InputMode-->/','<input type="hidden" value="'.$nextMode.'" name="mode" />',$bodyData);
		$bodyData = preg_replace('/<!--hiddenForm-->/',$this->hiddenForm,$bodyData);
		if($ARYconf['testMode']){
			$bodyData = preg_replace('/<title>/','<title>',$bodyData);
		}
		return $bodyData;
	}
	
	function exTag($bodyData,$formName,$mess){//該当フォームのエラー発動
		global $ARYconf;
		$mess = preg_quote($mess,'/');
		$formName = preg_quote($formName,'/');
		while(preg_match('/ class\(([\w]+),'.$formName.'\)/',$bodyData,$ARY_str)){
			$str1_q = preg_quote($ARY_str[1],'/');
			$bodyData = preg_replace('/ class\('.$str1_q.','.$formName.'\)/',' class="'.$ARY_str[1].'"',$bodyData,1);
			$bodyData = preg_replace('/ class=\"'.$str1_q.'\" class=\"'.$str1_q.'\"/',' class="'.$ARY_str[1].'"',$bodyData,1);
		}

		while(preg_match('/<!--mess\('.$mess.','.$formName.'\)-->/',$bodyData)){
			$bodyData = preg_replace('/<!--mess\('.$mess.','.$formName.'\)-->/',$ARYconf[$mess][$formName],$bodyData,1);
		}
		$this->error = 1;
		$ARYconf['errors'][$formName] = 1;
		return $bodyData;
	}
	
	function deleteExTag($bodyData){//置き換えタグの残骸消去
		$bodyData = preg_replace('/ class\(([\w]+),([\w]+)\)/','',$bodyData,999);
		$bodyData = preg_replace('/<!--mess\(([\w]+),([\w]+)\)-->/','',$bodyData,999);
		$bodyData = preg_replace('/<!--value\(([\w]+)\)-->/','',$bodyData,999);
		$bodyData = preg_replace('/<!--value\(([\w]+),([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$bodyData,999);
		return $bodyData;
	}
	
	function resData($bodyData){
		global $ARYconf;
		if(isset($ARYconf['uploadFileForm'])){
			foreach($ARYconf['uploadFileForm'] as $key2 => $value2){
				if($_FILES[$key2]['name'] != ""){//アップロードファイルがあれば保存
					$type = strstr($_FILES[$key2]['name'],".");
					$file_name = date(ymdHis).$type;
					if(! file_exists($value2.$file_name)){
						move_uploaded_file($_FILES[$key2]['tmp_name'],$value2.$file_name);
						$value = $file_name;
						$value = mb_convert_encoding($value, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
						$value = trim($value);
						$bodyData = $this->checkForm($bodyData,$key2,$value);
						$bodyData = $this->setValues($bodyData,$key2,$value);
					}
				}
			}
		}
		foreach($_POST as $key => $value){
			$value = mb_convert_encoding($value, "UTF-8", $ARYconf['charsetPHP']);		//文字コードを変換
			$value = trim($value);
			$_POST[$key] = $value;//元の$_POSTデータも整形データで上書きする
			$bodyData = $this->checkForm($bodyData,$key,$value);
			$bodyData = $this->setValues($bodyData,$key,$value);
		}
		return $bodyData;
	}
	
	function checkForm($bodyData,$key,$value){//フォーム内の値チェック
		global $ARYconf;
		$flg = 0;
		$tr_mode = 0;
		if((isset($ARYconf['hissu'][$key]))&&($ARYconf['hissu'][$key])){//必須
			if(! isset($ARYconf['hissuCheck'][$key])){//チェックボックス必須じゃない場合、必須フラグＯＮ
				$flg = 1; 
			}
		}else{//必須がない＝チェックボックスフォームかもしれない
			if(preg_match('/(.+)_(\d+)/',$key,$ARY_checkbox)){//アンダーバー＋数字を含んでいる
				if(isset($ARYconf['hissuCheck'][$ARY_checkbox[1]])){//チェックボックスグループ必須がある
					$flg = 1; 
				}
			}
		}
		if((isset($ARYconf['num'][$key]))&&($ARYconf['num'][$key])){ $tr_mode = 1; } //数値
		if((isset($ARYconf['email'][$key]))&&($ARYconf['email'][$key])){ $tr_mode = 2; }//メールアドレス
		if((isset($ARYconf['CBError'][$key]))&&($ARYconf['CBError'][$key])){ $tr_mode = 3; }//チェックボックス排他処理

		if(isset($_GET['test'])){//テストモード
			echo "\n key:".$key."\n tr_mode:".$tr_mode."\n";
		}
		
		switch($tr_mode){
			case 1:
				$bodyData = $this->postCheckDigit($value, $flg ,$bodyData,$key);
				break;
			case 2:
				$bodyData = $this->postMailAddress($value, $flg ,$bodyData,$key);
				break;
				
			case 3:
				$bodyData = $this->postCheckBoxH($value, $flg ,$bodyData,$key);
				break;
			default:
				$bodyData = $this->postCheckTextMulti($value, $flg ,$bodyData,$key);
				break;
		}
		
		if(is_array($ARYconf['hissuCheck'])){
			foreach($ARYconf['hissuCheck'] as $key => $value){//必須チェックボックスを確認
				if($this->hissuCheck[$key] < $value){//チェック数が設定数に満たない
					$bodyData = $this->exTag($bodyData,$key,'hissu');
				}
			}
		}
		
		if((isset($ARYconf['hmstr'][$key]))&&($ARYconf['hmstr'][$key])){//文字数制限
			$bodyData = $this->postCheckHMStr($value, $ARYconf['hmstrcnf'][$key] ,$bodyData,$key);
		}
		
		if((isset($ARYconf['2bstr'][$key]))&&($ARYconf['2bstr'][$key])){//２バイト文字が必須
			if((strlen($value) > 0)&&(strlen($value) == mb_strlen($value))){//エラー
				$bodyData = $this->exTag($bodyData,$key,'2bstr');
			}
		}
		
	 	return $bodyData; 
	}
	
	function setValues($bodyData,$key,$value){
		$key_q = preg_quote($key,'/');
		$value_q = preg_quote($value,'/');
		while(preg_match('/name\="'.$key_q.'" value\=""/',$bodyData)){//input
			$bodyData = preg_replace('/name\="'.$key_q.'" value\=""/','name="'.$key.'"  value="'.$value.'" ',$bodyData,1);
		}
		while(preg_match('/name\="'.$key_q.'"><\/textarea>/',$bodyData)){//textarea
			$bodyData = preg_replace('/name\="'.$key_q.'"><\/textarea>/','name="'.$key.'" >'.$value.'</textarea>',$bodyData,1);
		}
/*変更20091009
		while(preg_match('/name\="'.$key_q.'">/',$bodyData)){//select
			$bodyData = preg_replace('/name\="'.$key_q.'">/','name="'.$key.'" >',$bodyData,1);
			$bodyData = preg_replace('/<option value\="'.$value_q.'">/','<option value="'.$value.'" selected="selected">',$bodyData,1);
		}
*/
		while(preg_match('/name\="'.$key_q.'">(.|\n|\r|\r\n)*?select>/',$bodyData,$ARY_select)){//select
			$i_q = preg_quote($ARY_select[0],'/');
			$i = $ARY_select[0];
			$i = preg_replace('/name\="'.$key_q.'">/','name="'.$key.'" >',$i,1);
			$i = preg_replace('/<option value\="'.$value_q.'">/','<option value="'.$value.'" selected="selected">',$i,1);
			$bodyData = preg_replace('/'.$i_q.'/',$i,$bodyData,1);
		}
		while(preg_match('/name\="'.$key_q.'" value\="'.$value_q.'" \/>/',$bodyData)){//radio
			$bodyData = preg_replace('/name\="'.$key_q.'" value\="'.$value_q.'" \/>/','name="'.$key.'" value="'.$value.'" checked="checked" />',$bodyData,1);
//不要？20091009			$bodyData = preg_replace('/<option value\="'.$value_q.'">/','<option value="'.$value.'" selected="selected">',$bodyData,1);
		}
		while(preg_match('/type="checkbox" name\="'.$key_q.'" value\="([\w]+)" \/>/',$bodyData,$ARY_match)){//check
			$bodyData = preg_replace('/type="checkbox" name\="'.$key_q.'" value\="([\w]+)" \/>/','/name\="'.$key.'"  type="checkbox" value\="'.$ARY_match[1].'" checked="checked" \/>/',$bodyData);
		}


	 	return $bodyData; 
	}

	
	
//メール送信
	function sendMail($userEmail,$toUser = 1){//$toUser = 1ユーザーへのメールを送信する = 0しない
		global $ARYconf;
		$this->hensinMailTe = $this->deleteExTag($this->hensinMailTe);
		$this->clientMailTe = $this->deleteExTag($this->clientMailTe);
		$this->hensinMailTe = preg_replace('/<!--UserAgent-->/',$_SERVER['HTTP_USER_AGENT'],$this->hensinMailTe);
		$this->hensinMailTe = preg_replace('/<!--RemoteAddr-->/',$_SERVER['REMORT_ADDR'],$this->hensinMailTe);
		$this->clientMailTe = preg_replace('/<!--UserAgent-->/',$_SERVER['HTTP_USER_AGENT'],$this->clientMailTe);
		$this->clientMailTe = preg_replace('/<!--RemoteAddr-->/',$_SERVER['REMORT_ADDR'],$this->clientMailTe);
		
		//datフォルダの存在＆書き込み権限確認
		if($ARYconf['BULogFileDays']){
			$BU_error = "\n\n";
			if(! file_exists($ARYconf['BULogFolderPath'])){
				$BU_error .= "●バックアップファイルの書き出しがONになっていますが、バックアップ書き出し用のフォルダが存在していません。\n";
				$ARYconf['BULogFileDays'] = 0;
			}
			if(! is_writeable($ARYconf['BULogFolderPath'])){
				$BU_error .= "●バックアップファイルの書き出しがONになっていますが、バックアップ書き出し用のフォルダへの書き込み権限がありません。\n";
				$ARYconf['BULogFileDays'] = 0;
			}
			if(! is_executable($ARYconf['BULogFolderPath'])){
				$BU_error .= "●バックアップファイルの書き出しがONになっていますが、バックアップ書き出し用のフォルダへの書き込み権限がありません。\n";
				$ARYconf['BULogFileDays'] = 0;
			}
			$BU_error .= "\n\n";
			$this->clientMailTe = $BU_error.$this->clientMailTe;
		}
		
		//送信設定と送信作業
		if((isset($ARYconf['fpMail']))&&($ARYconf['fpMail'] != '')){
			mb_send_mail($ARYconf['clientMail'],$ARYconf['clientMailSubject'],$this->clientMailTe,"From: ".$userEmail);	//fromユーザでクライアントへ送信
			if($toUser){
				mb_send_mail($userEmail,$ARYconf['hensinMailSubject'],$this->hensinMailTe,"From: ".$ARYconf['hensinMail']);	//fromクライアントでユーザへ送信
			}
		}else{
			mb_send_mail($ARYconf['clientMail'],$ARYconf['clientMailSubject'],$this->clientMailTe,"From: ".$userEmail);	//fromユーザでクライアントへ送信
			if($toUser){
				mb_send_mail($userEmail,$ARYconf['hensinMailSubject'],$this->hensinMailTe,"From: ".$ARYconf['hensinMail']);	//fromクライアントでユーザへ送信
			}
		}

		//datファイルの生成
		if($ARYconf['BULogFileDays']){//$ARYconf['BULogFileDays']=0の時は実行しません。
			$FileH = fopen($ARYconf['BULogFolderPath'].date('ymdHms').preg_replace('/(@|\.)/','_',$userEmail).".dat","w");		//tmpにファイル名(日付時間+「_」置換メールアドレス)を指定してwモードでファイル展開。
			fwrite($FileH,$ARYconf['clientMail']."\n".$userEmail."\n".$this->hensinMailTe);						//クライアント、ユーザのメールアドレスと本分をdatに書き込み
			fclose($FileH);
	
			//古いDATファイルを削除
			$hl = $ARYconf['BULogFileDays'] * 86400;//86400s = 24H
			$dir = opendir($ARYconf['BULogFolderPath']);
			while(($ent = readdir()) !== FALSE){
				if(($ent == ".")||($ent == "..")||($ent == "")){ continue; }
				$kikan = time() - filectime($ARYconf['BULogFolderPath'].$ent);
				if($kikan > $hl){
					unlink($ARYconf['BULogFolderPath'].$ent);
				}
			}
		}
	}



//入力内容の正規表現チェックとエラーメッセージ、メール送信関数群
	
	
	function postCheckHMStr($value, $config ,$bodyData,$key){//文字数制限
		global $ARYconf;
		$error = 0;
		if(preg_match('/-/',$config)){//「-」を含む場合範囲指定
			$config = preg_replace('/,/','',$config);//ハイフンとカンマは共存できない
			$ARY_data = explode('-',$config,2);
			$ARY_data[0] = preg_replace('/[^\d]/','',trim($ARY_data[0]));//数字以外削除
			$ARY_data[1] = preg_replace('/[^\d]/','',trim($ARY_data[1]));//数字以外削除
			if(($ARY_data[0] > mb_strlen($value))||($ARY_data[1] < mb_strlen($value))){//指定範囲外の文字数の時
				$error = 1;//エラーフラグを立てる
			}
		}else{//含まない場合はカンマ区切り
			$config = preg_replace('/-/','',$config);//ハイフンとカンマは共存できない
			if(preg_match('/,/',$config)){
				$ARY_data = explode(',',$config);
			}else{
				$ARY_data[0] = $config;
			}
			$error = 1;//エラーフラグを強制的に立てて
			foreach($ARY_data as $val){
				$val = preg_replace('/[^\d]/','',trim($val));//数字以外削除
				if($val == mb_strlen($value)){//指定文字数の時
					$error = 0;//エラーフラグを消す
					break;
				}
			}
		}

		if(isset($_GET['test'])){//テストモード
			echo "\n HMStr:".$error."\n count:".mb_strlen($value)."\n";
		}
		
		
        if(($error == 1)&&($ARYconf['errors'][$key] != 1)){ $bodyData = $this->exTag($bodyData,$key,'hmstr'); }
		return $bodyData;
	}
	
	function postCheckBoxH($value, $flg ,$bodyData,$key){//排他機能付きチェックボックス
		global $ARYconf;
		$count = 0;
		$haita = 0;
		//排他処理のみ行う
		switch($value){
			case "checkbox00";
				if(isset($_POST[$key.'0'])){ $count ++; }
				$j = 1;
				break;
			case "checkbox01";
				if(isset($_POST[$key.'0'])){ $count ++; }
				if(isset($_POST[$key.'1'])){ $count ++; }
				if($count >= 2){ $haita = 1; }
				$j = 2;
				break;
		}
		
		if((! $haita)&&($count)){//高速化
			for($i=$j;$i<20;$i++){//チェックボックスは0～19の20個まで
				if(isset($_POST[$key.$i])){//排他エラー発生
					$count ++;
					$haita = 1;
					break;
				}
			}
		}

		if(isset($_GET['test'])){//テストモード
			echo "\n haita:".$haita."\n count:".$count."\n";
		}
		
        if($haita){ $bodyData = $this->exTag($bodyData,$key,'CBError'); }
        if((!$count)&&($flg)){ $bodyData = $this->exTag($bodyData,$key,'hissu'); }
		return $bodyData;
	}
	
	function postMailAddress($posted, $flg ,$bodyData,$key){//メールアドレス
		global $ARYconf;
	
		$checked = mb_convert_kana($posted, "a", $ARYconf['charsetPHP']);						//半角に変換
		$checked = htmlspecialchars($checked, ENT_QUOTES);						//",'をhtmlエンティティ(エスケープ)に変換
		
		
		if($checked){//空ではない場合内容をチェック
			if(!preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.[a-zA-Z]{2,4}$/", $checked)){  //行頭で[a-zA-Z0-9]、他に[a-zA-Z0-9\._-]、@、[a-zA-Z0-9\._-]、.、行末で[a-zA-Z]{2,4}$にマッチしない場合
				$bodyData = $this->exTag($bodyData,$key,'email');
			}
		}elseif($flg){//空の場合必須チェック
			$bodyData = $this->exTag($bodyData,$key,'hissu');
		}
	
		if((isset($ARYconf['isEqaul'][$key]))&&($ARYconf['isEqaul'][$key])){
			if($checked != $_POST[$ARYconf['isEqaul']['for']]){ $bodyData = $this->exTag($bodyData,$key,'isEqaul'); }
		}
		$key_q = preg_quote($key,'/');
		$this->completeData = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->completeData);
		$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->hensinMailTe);
		$this->clientMailTe = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->clientMailTe);
		if($checked != ""){
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->completeData,$ARY_data)){
				$this->completeData = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->completeData);
			}
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->hensinMailTe,$ARY_data)){
				$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->hensinMailTe);
			}
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->clientMailTe,$ARY_data)){
				$this->clientMailTe = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->clientMailTe);
			}
		}else{
			$this->completeData = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->completeData);
			$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->hensinMailTe);
			$this->clientMailTe = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->clientMailTe);
		}
		$this->hiddenForm .= '<input type="hidden" name="'.$key.'" value="'.$checked.'" />'."\n";
		return $bodyData;
	}
	
	function postCheckDigit($posted, $flg ,$bodyData,$key){//数字
		global $ARYconf;
		$key_q = preg_quote($key,'/');
        if($posted){//空ではない場合内容をチェック
			//対象文字の置換作業(正規表現)
			$checked = mb_convert_kana($posted, "a", $ARYconf['charsetPHP']);		//半角に変換
			if(preg_match("/[^0-9\-]/", $checked)){ $bodyData = $this->exTag($bodyData,$key,'num'); }
		}elseif($flg){//空の場合必須チェック
			$bodyData = $this->exTag($bodyData,$key,'hissu'); 
		}
		$this->completeData = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->completeData);
		$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->hensinMailTe);
		$this->clientMailTe = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->clientMailTe);
		if($checked != ""){
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->completeData,$ARY_data)){
				$this->completeData = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->completeData);
			}
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->hensinMailTe,$ARY_data)){
				$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->hensinMailTe);
			}
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->clientMailTe,$ARY_data)){
				$this->clientMailTe = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->clientMailTe);
			}
		}else{
			$this->completeData = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->completeData);
			$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->hensinMailTe);
			$this->clientMailTe = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->clientMailTe);
		}
		$this->hiddenForm .= '<input type="hidden" name="'.$key.'" value="'.$checked.'" />'."\n";
		return $bodyData;
	}
	
	function postCheckTextMulti($posted, $flg ,$bodyData,$key){//テキスト
		global $ARYconf;
		$key_q = preg_quote($key,'/');
		$checked = mb_convert_kana($posted, "KVasnr");	//KVasnrで置換(半角カナ→全角カナ、濁点文字を1文字に、全角英数→半角英数、全角空白→半角空白)
		$checked = preg_replace("/<br ?\/?>/", "\n", $checked);		//<br>または<br />を「\n」に変換(perl互換正規表現)
		$checked = preg_replace("/<|>|'|\"|&|%|\\\/", "", $checked);	//<、>、'、\、"、&、%、\\\」のどれかにマッチしたら消去(perl互換正規表現)
		$checked = stripslashes($checked);				//\,',",ヌル文字をアンエスケープ
		$checked = trim($checked);					//両端端の余分な文字の削除(改行コードなど)
		$checked = preg_replace("/\r\n/", "\n", $checked);		//CR+LFをLFに変換
		$checked = preg_replace("/\r/", "\n", $checked);		//CRをLFに変換
		
		
        if((!$checked)&&($flg)){ $bodyData = $this->exTag($bodyData,$key,'hissu'); }
		if(($posted)&&(!$checked)){ $bodyData = $this->exTag($bodyData,$key,'text'); }
		$this->completeData = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->completeData);
		$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->hensinMailTe);
		$this->clientMailTe = preg_replace('/<!--value\('.$key_q.'\)-->/',$checked,$this->clientMailTe);
		if($checked != ""){
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->completeData,$ARY_data)){
				$this->completeData = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->completeData);
			}
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->hensinMailTe,$ARY_data)){
				$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->hensinMailTe);
			}
			if(preg_match('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/',$this->clientMailTe,$ARY_data)){
				$this->clientMailTe = preg_replace('/<!--value\('.$key_q.','.preg_quote($ARY_data[1], "/") .','.preg_quote($ARY_data[2], "/") .'\)-->/',$ARY_data[1].$checked.$ARY_data[2],$this->clientMailTe);
			}
		}else{
			$this->completeData = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->completeData);
			$this->hensinMailTe = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->hensinMailTe);
			$this->clientMailTe = preg_replace('/<!--value\('.$key_q.',([\s\&\w<>=";]*),([\s\&\w\/<>=";]*)\)-->/','',$this->clientMailTe);
		}
		if($key != "mode"){
			$this->hiddenForm .= '<input type="hidden" name="'.$key.'" value="'.$checked.'" />'."\n";
		}
		return $bodyData;
	}
}

?>
