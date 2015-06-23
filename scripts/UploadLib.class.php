<?php
	//ファイルアップロードクラス
	//contentsディレクトリ以下に保存
	class UploadLib
	{
		private static $instance = null;
		/**
		 *
		 * @return UploadLib
		 */
		public static function getInstance(){
			if(is_null(self::$instance)){
				self::$instance = new self();
			}
			return self::$instance;
		}
		
		//ファイルアップロード
		//$target_dir = contentsディレクトリ以下のディレクトリ名
		public function upload($image_key, $target_dir, $prefix)
		{
			//保存ファイル名の自動生成
			$file_name = $prefix . "_" . time();
			
			return $this->_upload($image_key, $target_dir, $file_name);
		}
		
		//ファイルアップロード
		//$target_dir = contentsディレクトリ以下のディレクトリ名
		public function _upload($image_key, $target_dir, $file_name, $watermark=false)
		{
			if(!isset($_FILES[$image_key]))return false;
			
			//表示にトリミング＆コピーライト処理をするように変更
			$watermark=false;
			
			$img_name = $_FILES[$image_key]["name"];
			$img_size = $_FILES[$image_key]["size"];
			$img_type = $_FILES[$image_key]["type"];
			$img_tmp = $_FILES[$image_key]["tmp_name"];
				
			/* 無制限
			 if($img_size > 500000)
			 {
			//サイズが大きい
			return false;
			}
			*/
				
			$ext = $this->GetExt($img_name);
			if(strcasecmp($ext, 'png') != 0 && strcasecmp($ext, 'jpg') != 0 && strcasecmp($ext, 'gif') != 0)
			{
				//拡張子が違う
				return false;
			}
				
			$img_dir = $this->getRootPath() . '/' . $target_dir . '/';
			$img_path = $img_dir . $file_name . "." . $ext;
				
			if(@move_uploaded_file($img_tmp, $img_path) === false){
				return false;
			}
			if(is_file($img_path)){
				@chmod($img_path, 0666);
			}
			
			if($watermark){
				$this->watermarkCopyRight($target_dir, $file_name.'.'.$ext);
			}
			
			return $file_name . "." . $ext;
		}
		
		private function getRootPath()
		{
			$root_path = dirname(__FILE__) . '/../contents/';
			$root_path = realpath($root_path);
			return $root_path;
		}
		
		private function GetExt($FilePath){
			$f=strrev($FilePath);
			$ext=substr($f,0,strpos($f,"."));
			return strrev($ext);
		}
		
		//reviewディレクトリ限定
		public function watermarkCopyRight($target_dir, $file_name)
		{
			require_once dirname(__FILE__) . '/Zebra_Image.php';
			$WIDTH = 236;
			$HEIGHT = 178;
			$contents_dir = dirname(__FILE__) . '/../contents';
			
			
			$zebra = new Zebra_Image();
			$zebra->jpeg_quality = 100;
			$zebra->png_compression = 0;
			$zebra->source_path = $contents_dir.'/review/'.$file_name;
			$zebra->target_path = $contents_dir.'/review/'.$file_name;
			$zebra->resize($WIDTH, $HEIGHT);
			
			$base_img = null;
			if(stripos($file_name, '.png')!==false){
				$base_img = @imagecreatefrompng($contents_dir.'/review/'.$file_name);
			}else{
				$base_img = @imagecreatefromjpeg($contents_dir.'/review/'.$file_name);
			}
			
			$base_w = imagesx($base_img);
			$base_h = imagesy($base_img);
			
			$copyright_img = @imagecreatefrompng( dirname(__FILE__) . '/../common/img/copy-wrap.png' );
			$copyright_w = imagesx($copyright_img);
			$copyright_h = imagesy($copyright_img);
			$copyright_r = 3; //元画像が500x500なのでとりあえず３分の1にする
			$w = $copyright_w / $copyright_r; if($w<=2)$w=2;
			$h = $copyright_h / $copyright_r; if($h<=2)$h=2;
			$temp = imagecreatetruecolor($w, $h);
			imagealphablending($temp, false);
			imagesavealpha($temp, true);
			$backgroundColor = imagecolorallocate($temp, 255, 0, 255);//背景色セット
			imagefill($temp, 0, 0, $backgroundColor); // 背景を塗る。
			imagecolortransparent($temp,$backgroundColor);//透明化
			imagecopyresampled( $temp , $copyright_img , 0 , 0 , 0 , 0 , $w , $h , $copyright_w , $copyright_h );
			imagedestroy($copyright_img);
			$copyright_img = $temp;
			$copyright_w = $w;
			$copyright_h = $h;
			
			imagealphablending($copyright_img, false);
			imagesavealpha($copyright_img, true);
			
			//@imagecopyresampled( $base_img , $copyright_img , 0 , 0 , 0 , 0 , $base_w , $base_h , $copyright_w , $copyright_h );
			$dst_x = 0;
			$dst_y = 0;
			for($dst_y=0;$dst_y<$base_h;$dst_y+=$copyright_h)
			{
				for($dst_x=0;$dst_x<$base_w;$dst_x+=$copyright_w)
				{
					imagecopy($base_img, $copyright_img, $dst_x, $dst_y, 0, 0, $copyright_w, $copyright_h);
				}
			}
			
			if(stripos($file_name, '.png')!==false){
				@imagepng($base_img, $contents_dir.'/review/'.$file_name, 0);
			}else{
				@imagejpeg($base_img, $contents_dir.'/review/'.$file_name, 100);
			}
			
			@imagedestroy($base_img);
			@imagedestroy($copyright_img);
		}
	}