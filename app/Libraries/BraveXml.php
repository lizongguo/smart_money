<?php

namespace App\Libraries;

/**
 * ArrayToXml
 *
 * @author      yutao
 * @since       PHP 7.0.1
 * @version     1.0.0
 * @date        2018-1-11 13:57:41
 * @copyright   Copyright(C) bravesoft Inc.
 */
class BraveXml
{
    private $version    = '1.0';
    private $encoding   = 'UTF-8';
    private $root       = 'root';
    private $xml        = null;
    
    public function __construct($root = '')
    {
        $this->xml = new \XMLWriter();
        if (!empty($root)) {
            $this->root = $root;
        }
    }

    public function arrayToXml($array, $eIsArray = false)
    {
        if (!$eIsArray) {
            $this->xml->openMemory();
            $this->xml->startDocument($this->version, $this->encoding);
            $this->xml->startElement($this->root);
        }
        foreach ($array as $key => $value) {
            $key = $this->getKey($key);
            if (is_array($value)) {
                $this->xml->startElement($key);
                $this->arrayToXml($value, true);
                $this->xml->endElement();
                continue;
            }
            $this->xml->writeElement($key, $value);
        }
        if (!$eIsArray) {
            $this->xml->endElement();
            return $this->xml->outputMemory(true);
        }
    }
    
    public function xmlToArray($xml = '', $isFile = false)
    {
        $return = array();
        if (!$isFile && !$xml) {
            return $return;
        }
        
        if ($isFile) {
            $xmlObject = simplexml_load_file($xml);
        } else {
            $xmlObject = simplexml_load_string($xml);
        }
        if (!$xmlObject) {
            return $return;
        }
        
        if ((float)PHP_VERSION < 5.3) {
            $str = serialize($xmlObject);
            $str = str_replace('O:16:"SimpleXMLElement"', 'a', $str);
            $return = unserialize($str);
        } else {
            $return = $this->parseXmlObject($xmlObject);
        }
        
        return $return;
    }
    
    protected function parseXmlObject($xmlObject)
    {
        $array = array();
        foreach ($xmlObject->children() as $key => $child) {
            if (count($child->children())) {
                $tmp = $this->parseXmlObject($child);
            } else {
                $tmp = (string)$child;
            }
            if (count($child->attributes())) {
                foreach ($child->attributes() as $k => $v) {
                    $tmp['@attributes'][$k] = (string)$v;
                }
            }
            if (count($xmlObject->{$key}) > 1) {
                $array[$key][] = $tmp;
            } else {
                $array[$key] = $tmp;
            }
        }
        
        return  $array;
    }
    
    public function setRootValue($root)
    {
        if (!empty($root)) {
            $this->root = $root;
        }
    }
    
    private function getKey($key)
    {
        if (is_numeric($key)) {
            return 'item';
        } elseif (preg_match("/^__([\w]+)__[\d]+$/", $key, $match)) {
            return $match[1];
        }
        return $key;
    }
}
