<?php
namespace ConfigManager\Managers;

class JsonManager extends Manager
{
    protected function decodeConfig($content)
    {
        return json_decode($content);
    }
    protected function encodeConfig($config)
    {
        return json_encode($config);
    }

}

?>