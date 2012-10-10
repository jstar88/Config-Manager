<?php

class Manager implements ExtensionManager
{
    private $config;
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }
    public function get($key)
    {
        $this->checkParse();
        return $this->config[$key];
    }
    public function add($key, $value = false)
    {
        $this->checkParse();
        $this->write_config($key, $value, true);
    }
    public function asArray()
    {
        $this->checkParse();
        return clone $this->config;
    }
    public function set($key, $value = false)
    {
        $this->checkParse();
        $this->write_config($key, $value, false);
    }
    public function delete($key)
    {
        $this->checkParse();
        unset($this->config[$key]);
    }
    public function exist($key)
    {
        $this->checkParse();
        return isset($this->config[$key]);
    }
    protected function write_config($config_name, $config_value, $can_add)
    {

        if (is_array($config_name) || is_object($config_name))
        {
            foreach ($config_name as $key => $value)
            {
                if (!isset($key) || $can_add)
                {
                    $this->assign($key,$value,$can_add);
                }
            }
        }
        else
        {
            if (!isset($config_name) || $can_add)
            {
                $this->assign($config_name,$config_value,$can_add);
            }
        }
        $this->saveConfig();
    }
    protected function assign($key,$value,$can_add)
    {
        $this->config[$key] = $value;    
    }
    protected function checkParse()
    {
        if (!empty($this->config))
            return;
        $this->config = $this->decodeConfig($this->openConfig($this->path));
        ConfigManager::incrementParseCount(__class__);
    }
    protected function getPath()
    {
        return $this->path;
    }
    protected function getConfig()
    {
        return $this->config;
    }
    protected function saveConfig()
    {
        SafeIO::save($this->encodeConfig($this->config), $this->path);
    }
    protected function openConfig($path)
    {
        return SafeIO::open($path);
    }
    protected function decodeConfig($content)
    {
        return $content;
    }
    protected function encodeConfig($config)
    {
        return $config;
    }
}

?>