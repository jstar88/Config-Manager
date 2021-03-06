<?php
namespace ConfigManager\Managers;

use \ConfigManager\Exceptions\FileNotExistException as FileNotExistException;

class PhpManager extends Manager
{
    protected function onlyOpenConfig($path)
    {
        if (!file_exists($path))
            throw new FileNotExistException($path);
        $config = require $path;
        return $config;
    }
    protected function encodeConfig($config)
    {
        $content = '<?php';
        $content .= ' return ' . var_export($config, true) . '; ';
        $content .= '?>';
        return $content;
    }
}

?>