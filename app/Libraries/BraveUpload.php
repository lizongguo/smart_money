<?php

namespace App\Libraries;

use App\Libraries\BraveSystem;

class BraveUpload {

    protected $ext = array();
    protected $size = 102400000;
    protected $filter = array('php');
    protected $error = 'error_msg';
    protected $info = '_info';
    protected $mode = 0777;
    protected $system;
    public function __construct() {
        $this->system = new BraveSystem;
    }
    
    function getValue(&$data, $key) {
        if (strlen($key) == 0) {
            return null;
        }

        // var1
        if (isset($data[$key])) {
            return $data[$key];
        }

        // var1.var2....
        $tmp = explode('.', $key);

        if (count($tmp) > 1) {
            $key = array_shift($tmp);

            if (isset($data[$key])) {
                $remain = implode('.', $tmp);
                return $this->getValue($data[$key], $remain);
            }
        }

        return null;
    }

    function files($key = null) {
        if (empty($_FILES)) {
            return null;
        }

        $files = null;

        foreach ($_FILES as $k => $v) {
            if (!is_array($v['name'])) {
                $files[$k] = $v;
                continue;
            }

            foreach ($v['name'] as $k1 => $v1) {
                if (!is_array($v1)) {
                    $files[$k][$k1]['name'] = $v1;
                    $files[$k][$k1]['type'] = $v['type'][$k1];
                    $files[$k][$k1]['tmp_name'] = $v['tmp_name'][$k1];
                    $files[$k][$k1]['error'] = $v['error'][$k1];
                    $files[$k][$k1]['size'] = $v['size'][$k1];
                    continue;
                }

                foreach ($v1 as $k2 => $v2) {
                    $files[$k][$k1][$k2]['name'] = $v2;
                    $files[$k][$k1][$k2]['type'] = $_FILES[$k]['type'][$k1][$k2];
                    $files[$k][$k1][$k2]['tmp_name'] = $_FILES[$k]['tmp_name'][$k1][$k2];
                    $files[$k][$k1][$k2]['error'] = $_FILES[$k]['error'][$k1][$k2];
                    $files[$k][$k1][$k2]['size'] = $_FILES[$k]['size'][$k1][$k2];
                }
            }
        }

        if (is_null($key)) {
            return $files;
        }

        return $this->getValue($files, $key);
    }

    function upload($config) {
        if (!$files = $this->files()) {
            return false;
        }
        foreach ($files as $k => $v) {
            if (isset($v['name']))  {
                if (strlen($v['name'])) {
                    $key = $k;
                    $param = $this->param($config, $key);
                    $this->valid($param, $v);
                    $this->handle($param, $v);
                    $_POST[$k] = isset($v['save']) ? $v['save'] : '';
                    $_POST[$k . $this->info] = $v;
                    continue;
                }
                else {
                    $_POST[$k] = '';
                }
            }
            foreach ($v as $k1 => $v1) {
                $key = "{$k}[{$k1}]";
                $param = $this->param($config, $key);

                if (isset($v1['name'])) {
                    if (strlen($v1['name'])) {
                        $this->valid($param, $v1);
                        $this->handle($param, $v1);

                        $_POST[$k][$k1] = isset($v1['save']) ? $v1['save'] : '';
                        $_POST[$k][$k1 . $this->info] = $v1;
                        continue;
                    }
                    else {
                        $_POST[$k][$k1] = '';
                    }
                }
                
                foreach ($v1 as $k2 => $v2) {
                    if (isset($v2['name'])) {
                        if (strlen($v2['name'])) {
                            $this->valid($param, $v2);
                            $this->handle($param, $v2);
                            $_POST[$k][$k1][$k2] = isset($v2['save']) ? $v2['save'] : '';;
                            $_POST[$k][$k1][$k2 . $this->info] = $v2;
                        }
                        else {
                            $_POST[$k][$k1][$k2] = '';
                        }
                    }
                }
            }
        }

        return true;
    }
    
    function param($config, $key) {
        $param = null;
        
        foreach ($config as $k => $v) {
            if ($key == $k) {
                return $v;
            }

            if (preg_match('/^\/\^(.*?)\$\/([is]*)$/i', $k) && preg_match($k, $key)) {
                return $v;
            }
        }
        
        return $param;
    }
    
    function valid($param, &$file) {
        if($file['error'] == 1) {
            $file[$this->error] = 'file_size_invalid';
            return false;
        }else if($file['error'] == 4) {
            $file[$this->error] = 'file_is_not_upload';
            return false;
        }else if (!$file['tmp_name']) {
            $file[$this->error] = 'file_tmp_null';
            return false;
        }
        if ($file['name'] == 'blob') {
            $file['name'] = $_POST['filename'];
        }

        $ext = (isset($param['ext']) && is_array($param['ext']))? $param['ext']: $this->ext;
        if (!$this->extValid($ext, $file)) {
            $file[$this->error] = 'file_ext_invalid';
        }
        
        $size = (isset($param['size']) && $param['size'])? $param['size']: $this->size;
        if (!$this->sizeValid($size, $file)) {
            $file[$this->error] = 'file_size_invalid';
        }
        
        return isset($file[$this->error])? false: true;
    }
    
    function handle($param, &$file) {
        if (isset($file[$this->error])) {
            return false;
        }
        
        $base = $file['base'] = (isset($param['base']) && strlen($param['base']))? $param['base']: $this->base;
        $save = $this->getSave($file, $param);
        $new = $base . $save;
        
        
        $this->system->mkdirs(dirname($new));
        
        if (!move_uploaded_file($file['tmp_name'], $new)) {
            $file[$this->error] = 'file_move_failure';
        }
        else {
            chmod($new, $this->mode);
        }
        //像素尺寸大小问题
        if(isset($param['pixel']) && preg_match('/(png|jpeg|jpg|gif)$/i', $save)){
            $w = $param['pixel']['w'];
            $h = $param['pixel']['h'];
            list($width, $height) = getimagesize($new);
            if($width < $w || $height < $h) {
                $file[$this->error] = 'image_pixel_failure';
            }
        }
        
        if($param['thumbnail'] && preg_match('/(png|jpeg|jpg|gif)$/i', $save)) {
            $thumbnail = new ImageThumb;
            $file['thumbnail'] = $thumbnail->thumb($save, $param['thumbnail']['w'], $param['thumbnail']['h'], $param['base'], $param['thumbnail']['type']);
        }
    }
    
    function getExt($fileName) {
        return $this->system->fileExt($fileName);
    }

    function extValid($ext, &$file) {
        $fileExt = $this->getExt($file['name']);
        
        if (empty($ext)) {
            return true;
        }
        else if (in_array($fileExt, $ext)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function filterValid($filter, $file) {
        $fileExt = $this->getExt($file['name']);

        if (empty($filter)) {
            return true;
        }
        else if (in_array($fileExt, $filter)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    function sizeValid($size, $file) {
        return ($size > $file['size'])? true: false;
    }
    
    function pixelSizeValid($size, $file) {
        return ($size > $file['size'])? true: false;
    }
    
    function getSave(&$file, $config) {
        $dir = date('Ymd');
        $name = date('His_') . md5(date('His') . uniqid(rand(1000,9999), true));
        $ext = $this->getExt($file['name']);
        $file['ext'] = $ext;
        $file['file'] = $name . '.' . $ext;
        $file['save'] = $config['url_path'] . $dir . '/' . $file['file'];
        return str_replace('/', DIRECTORY_SEPARATOR, $file['save']);
    }
}