<?php
/**
 * Created by PhpStorm.
 * User: zxf1001
 * Date: 2019/1/11
 * Time: 15:05
 */
namespace App\Models;
use App\Exceptions\Model;
use Illuminate\Support\Facades\DB;

class ProjGenPic extends Model
{
    public function doit($filename,$newsinfo)
    {
        $sourcefile = $filename;
        $getimagesizefilename = getimagesize($filename);
        if($getimagesizefilename[0]<720){
            $sourcefile = $this->resize(720,$filename.'resize',$filename);
        }else{
            $sourcefile = $this->resize(720,$filename.'resize',$filename);
        }

        $image = imagecreatefrompng($sourcefile);
        if($image){
            //imagefilter($image, IMG_FILTER_GRAYSCALE);
            //imagefilter($image, IMG_FILTER_BRIGHTNESS, -30);
            $image = $this->txt($image,$newsinfo);
            //$image = $this->mask($image);
            $rand_value = md5(''.rand(0,9999999));

            $basePicUrl = base_path();
            $filedir = $basePicUrl.'/storage/app/public/image/lianxun';
            $markedImageFilename = $filedir.'/'.$rand_value. '.png';
            imagepng($image, $markedImageFilename);
            $md5_value = md5_file($markedImageFilename);
            rename($markedImageFilename, $filedir.'/'.$newsinfo['no']."_".$md5_value. '.png');
            return $md5_value;
        }
    }

    public function txt($img,$newsinfo)
    {
        $time = strtotime($newsinfo['date']);
        $date = date("m", $time)."/".date("d", $time);
        $no = $newsinfo['no'];
        $lunar_month = $newsinfo['lunar_month'];
        $lunar_day = $newsinfo['lunar_day'];
        $title = $newsinfo['title'];
        $content = $newsinfo['content'];
        $lunar_text = $lunar_month."月 ".$lunar_day."日";

        $no_left = 69/1.3;
        $no_top = 286/1.3;
        $date_left = 572/1.3;
        $date_top = 286/1.3;
        $lunar_text_left = 663/1.3;
        $lunar_text_top = 286/1.3;
        $title_left = 234/1.3;
        $title_top = 330/1.3;
        $content_left = 104/1.3;
        $content_top = 530/1.3;
        $w = imagesx($img);
        $h = imagesy($img);
        $font_height = 14;
        $font_height_large = 25;
        $font_height_middle = 22;
        $font_size = $font_height *1280/$h;
        $font_size_large = $font_height_large *1280/$h;
        $font_size_middle = $font_height_middle *1280/$h;
        $font = env('APP_URL').'/fonts/msyh.ttf';
        print_r($font);
        $black_color = imagecolorallocatealpha($img,0,0,0,0);
        $white_color = imagecolorallocatealpha($img,255,255,255,0);

        imagettftext($img,$font_size,0,$no_left,
            $no_top+$font_height,$black_color,$font,"NO.".$no);

        imagettftext($img,$font_size,0,$date_left,
            $date_top+$font_height,$black_color,$font,$date);

        imagettftext($img,$font_size,0,$lunar_text_left,
            $lunar_text_top+$font_height,$white_color,$font,$lunar_text);

        foreach($title as $i=>$line)
        {
            imagettftext($img,$font_size_large,0,$w/2-(mb_strlen($line)*30/2),
                $title_top+($font_height_large+20)*($i+1),$black_color,$font,$line);
        }

        foreach($content as $i=>$line)
        {
            imagettftext($img,$font_size_middle,0,$content_left,
                $content_top+($font_height_middle+15)*($i+1),$white_color,$font,$line);
        }
        return $img;
    }

    public function resize($newWidth, $targetFile, $originalFile) {

        $info = getimagesize($originalFile);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

            default:
                throw new Exception('Unknown image type.');
        }

        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);

        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if (file_exists($targetFile)) {
            unlink($targetFile);
        }
        $image_save_func($tmp, "$targetFile.$new_image_ext");
        return "$targetFile.$new_image_ext";
    }
}