<?php 
require_once dirname(__FILE__) . '/scripts/Zebra_Image.php';
require_once dirname(__FILE__) . '/scripts/model/ReviewModel.class.php';

$id = (int)@$_GET['id'];
$w = (int)@$_GET['w'];
$h = (int)@$_GET['h'];
$cw = (int)@$_GET['cw'];
$ch = (int)@$_GET['ch'];
$p = (int)@$_GET['p'];
if($p==0)$p=1;

$zebra = new Zebra_Image();
$review_model = new ReviewModel();

$review = $review_model->getReview($id);
if(empty($review)){
	exit;
}

$zebra->source_path = dirname(__FILE__) . '/' . $review['photo'.$p];
if(!is_file($zebra->source_path)){
	exit;
}

$target_type = strtolower(substr($zebra->source_path, strrpos($zebra->source_path, '.') + 1));
switch($target_type)
{
	case 'jpg':
	case 'jpeg':
	case 'png':
		break;
	default:
		exit;
}

$zebra->copyright = new CopyrightWriterInterface($cw, $ch);
$zebra->resize($w, $h);
//$zebra->resize($w, $h, 11);


switch($target_type)
{
	case 'jpg':
	case 'jpeg':
		header('Content-type: image/jpeg');
		@imagejpeg($zebra->target_identifier, null, 100);
		break;
	case 'png':
		header('Content-type: image/png');
		@imagepng($zebra->target_identifier, null, 0);
		break;
}

@imagedestroy($zebra->target_identifier);

exit;
////////////////////
class CopyrightWriterInterface
{
	private $width;
	private $height;
	public function __construct($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}
	public function write($identifier)
	{
		$copyright_img = @imagecreatefrompng( dirname(__FILE__) . '/common/img/copy-wrap.png' );
		$copyright_w = imagesx($copyright_img);
		$copyright_h = imagesy($copyright_img);
		$w = $copyright_w;
		$h = $copyright_h;
		if($this->width>0 && $this->height>0){
			$w = $this->width;
			$h = $this->height;
		}
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
		
		$base_w = imagesx($identifier);
		$base_h = imagesy($identifier);
		
		$dst_x = 0;
		$dst_y = 0;
		for($dst_y=0;$dst_y<$base_h;$dst_y+=$copyright_h)
		{
			for($dst_x=0;$dst_x<$base_w;$dst_x+=$copyright_w)
			{
				imagecopy($identifier, $copyright_img, $dst_x, $dst_y, 0, 0, $copyright_w, $copyright_h);
			}
		}
		
		@imagedestroy($copyright_img);
	}
}