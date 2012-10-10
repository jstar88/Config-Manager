<?php
require_once(ROOT.'libs/spyc-0.5/spyc.php');
class YamlManager extends Manager
{
    protected function decodeConfig($content)
    {
        return Spyc::YAMLLoad($content);
    }
    protected function encodeConfig($config)
    {
        return Spyc::YAMLDump($config);
    }

}

?>