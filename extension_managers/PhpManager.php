<?php
require_once(EXCEPTIONS.'FileNotExistException.php');
class PhpManager extends Manager
{
    protected function onlyOpenConfig($path)
    {
        if(!file_exists($path))
            throw new FileNotExistException($path);
        require $path;
        return $config;
    }
    protected function encodeConfig($config)
    {
        $content = '<?php';
        $content .= ' $config = ' . var_export($config, true) . '; ';
        $content .= '?>';
        return $content;
    }
}

?>