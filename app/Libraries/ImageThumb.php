<?php
namespace App\Libraries;
/**
 * ImageThumb
 * 
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-7-25 10:49:46
 * @copyright   Copyright(C) bravesoft Inc.
 */
class ImageThumb {

    private $error;

    public function getError() {

        return $this->error;
    }
    /**
     *
     * 制作缩略图
     * @param $src_path string 原图路径
     * @param $max_w int 画布的宽度
     * @param $max_h int 画布的高度
     * @param $type  是否是等比缩略图  为0按比例缩放，1按固定宽度缩放，2按固定高度缩放，3按固定宽高缩放
     * @param $prefix string 缩略图的前缀  默认为'thumb_'
     *
     */
    public function thumb($src_path, $max_w, $max_h, $base_path, $type = 0, $prefix = 'thumb_') {

        //获取文件的后缀
        list($width, $height, $itype, $attr) = getimagesize($base_path . $src_path);
        //判断文件格式
        switch ($itype) {
            case '1':
                $cate = 'gif';
                break;
            case '2':
                $cate = 'jpeg';
                break;
            case '3':
                $cate = 'png';
                break;
            case '15':
                $cate = 'wbmp';
                break;
            default:
                $this->error = '文件格式不正确';
                return false;
        }
        //拼接打开图片的函数
        $open_fn = 'imagecreatefrom' . $cate;
        $save_fn = 'imagepng';// . $cate;
        
        //打开源图
        $src = $open_fn($base_path . $src_path);
        
        if(!$src) {
            return false;
        }
        
        //获取图片情报，旋转图片
        $exif = \exif_read_data($base_path . $src_path);
        
        
        //源图的宽
        $src_w = imagesx($src);
       
        //源图的高
        $src_h = imagesy($src);
        $thumbnail_name = preg_replace("#^(.*[\/|\\\])([^\\\/]+)$#is", "$1{$prefix}$2", $src_path);
        $thumb_path = $base_path . $thumbnail_name;
        
        //装换 \ 为 url模式 /
        $thumbnail_name = preg_replace("#[\\\]+#", '/', $thumbnail_name);
        
        //如果原图尺寸小于缩略图尺寸，直接copy原图
        if($src_w < $max_w && $src_h < $max_h)
		{
			$state = $save_fn($src, $thumb_path);
            imagedestroy($src);
			return $state ? $thumbnail_name : false;
		}
        
        $dst_x = 0;
        $dst_y = 0;
        if ($type == '1') { //按固定宽缩放
            $dst_w = $max_w;
            $dst_h = $dst_w * $src_h / $src_w;
            $max_h = $dst_h;
        } elseif ($type == '2') {//按固定高缩放
            $dst_h = $max_h;
            $dst_w = $src_w / $src_h * $dst_h;
            $max_w = $dst_w;
        }else if ($type == '3') { //按固定宽高缩放
            $dst_h = $max_h;
            $dst_w = $max_w;
        } else { //0等比缩放
            
            //求目标图片的宽高
            if ($max_w / $max_h < $src_w / $src_h) {
                //横屏图片以宽为标准
                $dst_w = $max_w;
                $dst_h = $max_w * $src_h / $src_w;
            } else {
                //竖屏图片以高为标准
                $dst_h = $max_h;
                $dst_w = $max_h * $src_w / $src_h;
            }
            //在目标图上显示的位置
            $dst_x = (int) (($max_w - $dst_w) / 2);
            $dst_y = (int) (($max_h - $dst_h) / 2);
        }
        
        //创建目标图
        $dst = imagecreatetruecolor($max_w, $max_h);
        
        if($type == 0) {
            //上下左右居中， 背景透明
            $color = imagecolorallocate($dst, 1000, 1000, 1000);
            imagefill($dst, 0, 0, $color);
            imagecolortransparent($dst, $color);
        }
        //生成缩略图
        imagecopyresized($dst, $src, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        
        if($exif['Orientation'] == 3) {
            $dst = imagerotate($dst, 180, 0);
        } elseif($exif['Orientation'] == 6) {
            $dst = imagerotate($dst, -90, 0);
        } elseif($exif['Orientation'] == 8) {
            $dst = imagerotate($dst, 90, 0);
        }
        //把缩略图上传到指定的文件夹
        $statue = $save_fn($dst, $thumb_path);
        //销毁图片资源
        imagedestroy($dst);
        imagedestroy($src);
        
        //返回新的缩略图相对地址
        return $statue ? $thumbnail_name : false;
    }
}
