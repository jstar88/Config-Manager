<?php

class Manager implements ExtensionManager
{
    private $config;
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    //----  methods of interfaces ----
    public function set($key, $value = false)
    {
        $this->checkParse();
        $this->set_config($key, $value);
        $this->saveConfig();
    }
    public function add($key, $value = false)
    {
        $this->checkParse();
        $this->add_config($key, $value);
        $this->saveConfig();
    }
    public function get($key)
    {
        $this->checkParse();
        $this->checkExist($key);
        return $this->get_config($key);
    }
    public function asArray()
    {
        $this->checkParse();
        return $this->asArray_config();
    }
    public function exist($key)
    {
        $this->checkParse();
        return $this->exist_config($key);
    }
    public function delete($key)
    {
        $this->checkParse();
        $this->checkExist($key);
        $this->delete_config($key);
        $this->saveConfig();
    }
    //--------------------------------
    //---- Auto updated functions ----
    protected function set_config($key, $value = false)
    {
        $this->write_config($key, $value, false);
    }
    protected function add_config($key, $value = false)
    {
        $this->write_config($key, $value, true);
    }
    protected function get_config($key)
    {
        return $this->config[$key];
    }
    protected function asArray_config()
    {
        return $this->config;
    }
    protected function exist_config($key)
    {
        return isset($this->config[$key]);
    }
    protected function delete_config($key)
    {
        unset($this->config[$key]);
    }
    //---------------------------------
    protected function checkExist($key)
    {
        if (!$this->exist_config($key))
        {
            throw new Exception(sprintf('Item with id "%s" does not exists.', $key));
        }
    }
    protected function checkNotExist($key)
    {
        if ($this->exist_config($key))
        {
            throw new Exception(sprintf('Item with id "%s" already exists.', $key));
        }
    }
    protected function write_config($config_name, $config_value, $can_add)
    {

        if (is_array($config_name) || is_object($config_name))
        {
            foreach ($config_name as $key => $value)
            {
                if (!$can_add)
                {
                    $this->checkExist($key);
                }
                else
                {
                    $this->checkNotExist($key);
                }
                $this->assign($key, $value, $can_add);

            }
        }
        else
        {
            if (!$can_add)
            {
                $this->checkExist($config_name);
            }
            else
            {
                $this->checkNotExist($config_name);
            }
            $this->assign($config_name, $config_value, $can_add);

        }
    }
    protected function assign($key, $value, $can_add)
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
    protected function &getConfig()
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