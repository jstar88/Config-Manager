<?php

namespace ConfigManager\Modules\Plugins\Yaml;

use \ConfigManager\Core\ConfigManager as ConfigManager;
use \ConfigManager\Modules\System\File\FileManager as FileManager;

require_once (\ConfigManager\Core\LIBS . 'spyc-0.5' . DIRECTORY_SEPARATOR . 'spyc.php');
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