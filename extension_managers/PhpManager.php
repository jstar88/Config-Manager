<?php

class PhpManager extends Manager
{
    protected function openConfig($path)
    {
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