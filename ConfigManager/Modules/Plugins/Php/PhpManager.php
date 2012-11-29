<?php

namespace ConfigManager\Modules\Plugins\Php;

use \ConfigManager\Modules\System\File\Exceptions\FileNotExistException as FileNotExistException;
use \ConfigManager\Modules\System\File\FileManager as FileManager;

class PhpManager extends FileManager
{
    protected function onlyOpenConfig($path)
    {
        if (!file_exists($path))
        {
            throw new FileNotExistException($path);
        }
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