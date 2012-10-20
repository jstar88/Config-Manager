<?php
namespace ConfigManager\Managers;
use \ConfigManager\ConfigManager as ConfigManager;

require_once(\ConfigManager\LIBS .'spyc-0.5'.DIRECTORY_SEPARATOR.'spyc.php');
class YamlManager extends Manager
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

?>