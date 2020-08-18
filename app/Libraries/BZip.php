<?php
namespace App\Libraries;

/**
 * BZip 
 *
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2019-01-22 14:00:41
 * @copyright   Copyright(C) www.kbftech.cn Inc.
 */
class BZip {

    private $version = '1.0';
    private $encoding = 'UTF-8';
    private $name = null;
    private $zip = null;
    protected $config = null;
    protected $currentConfig = null;

    public function __construct($configKey = 'default') {
        $this->config = config('zip');
        if (empty($configKey) || !isset($this->config[$configKey])) {
            $this->currentConfig = $this->config['default'];
        } else {
            $this->currentConfig = $this->config[$configKey];
        }
    }
    
    /**
     * @param type $configKey
     * @return boolean
     */
    public function setting($configKey) {
        if (isset($this->config[$configKey])) {
            $this->currentConfig = $this->config[$configKey];
            return true;
        }
        return false;
    }
    
    /**
     * 压缩文件
     * 
     * @param type $files 数组、文件、目录混合形式
     * @param type $overwrite 覆盖已有文件
     * @param type $name 压缩文件名带后缀
     * @param type $overwrite 覆盖已有文件
     * @return boolean|string 压缩文件相对路径，返回false表示压缩失败  
     */
    public function createZip($files, $name = null, $overwrite = false)
    {
        if (!empty($name)) {
            $this->name = strpos($name, '.zip') >= 0 ? $name : $name . ".zip" ;
        } else {
            $this->name = $this->createZipName();
        }
        
        //创建目录
        if ($this->createDir($this->currentConfig['base'] . $this->currentConfig['url_path']) === false) {
            return false;
        }
        $this->zip = new \ZipArchive();
        $file_path = $this->currentConfig['base'] . $this->currentConfig['url_path'] . $this->name;
        
        $rs = $this->zip->open($file_path, $overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        if($rs !== true){
            $this->zip->close();
            return false;
        }
        
        if (is_dir($files)) {  //给出文件夹，打包文件夹
            $this->addDirToZip($files);
        } else if (is_array($files)) {  //以数组形式给出文件路径
            foreach ($files as $file) {
                $this->addFile($file);
            }
        } else {
            //只给出一个文件
            $this->addFile($files);
        }
        
        $this->zip->close();
        
        if (!file_exists($file_path)) {
            //压缩失败
            return false;
        }
        //压缩成功 返回压缩后相对地址
        return $this->currentConfig['url_path'] . $this->name;
    }
    
    /**
     * 添加文件到zip 压缩包
     * @param type $file
     */
    protected function addFile($file) {
        if (is_dir($file)) {
            $this->addDirToZip($file);
        }else if (file_exists($file)) {
            $this->zip->addFile($file, basename($file));
        }
    }

    /*
     * 创建压缩包文件名
     */
    protected function createZipName() {
        return date('YmdHis') . '.zip';
    }
    
    /**
     * 将目录文件添加到zip中
     * @param type $dir
     */
    protected function addDirToZip($dir) {
        $handler = opendir($dir); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                //文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($dir . "/" . $filename)) {
                // 如果读取的某个对象是文件夹，则递归
                    $this->addDirToZip($dir . "/" . $filename);
                } else { 
                    //将文件加入zip对象
                    $this->zip->addFile($dir . "/" . $filename, $filename);
                }
            }
        }
        @closedir($handler);
    }
    
    protected function createDir($dir) {
        if (!file_exists($dir)) {
            if (mkdir($dir, 0777, true)){
                chmod($dir, 0777);
                return true;
            }else {
                return false;
            }
        }
    }
    
    /**
     * 解压zip文件
     * @param type $zipfile 解压压缩文件
     * @param type $destination 解压目录
     * @param array|null $entries 解压的文件
     * @return boolean
     */
    public function unzip($zipfile, $destination, $entries = null) {
        if (!file_exists($zipfile)) {
            return false;
        }
        //创建目录
        if ($this->createDir($destination) === false) {
            return false;
        }
        $this->zip = new \ZipArchive();
        if ($this->zip->open($zipfile) !== true) {
            $this->zip->close();
            return false;
        }
        if (!!$entries) {
            $rs = $this->zip->extractTo($destination, $entries);
        } else {
            $rs = $this->zip->extractTo($destination);
        }
        $this->zip->close();
        return $rs;
    }
}
