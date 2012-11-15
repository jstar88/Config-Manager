<?php

namespace ConfigManager\Modules\Yaml;

use \ConfigManager\Core\ConfigManager as ConfigManager;
use \ConfigManager\Modules\File\FileManager as FileManager;

require_once (\ConfigManager\LIBS . 'spyc-0.5' . DIRECTORY_SEPARATOR . 'spyc.php');
class YamlManager extends FileManager
{
    protected function decodeConfig($content)
    {
        return \Spyc::YAMLLoad($content);
    }
    protected function encodeConfig($config)
    {
        return \Spyc::YAMLDump($config);
    }

}