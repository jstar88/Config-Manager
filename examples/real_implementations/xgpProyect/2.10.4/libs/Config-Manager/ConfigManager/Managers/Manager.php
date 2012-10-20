<?php

namespace ConfigManager\Managers;

use \ConfigManager\Interfaces\ExtensionManager as ExtensionManager;
use \ConfigManager\Utils\SafeIO as SafeIO;
use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;
use \ConfigManager\Exceptions\FileNotExistException as FileNotExistException;
use \ConfigManager\Exceptions\FileViolationException as FileViolationException;
use \ConfigManager\ConfigManager as ConfigManager;

define(__namespace__ . '\FILETIME_TOLLERANCE', 3);
class Manager implements ExtensionManager
{
    private $config;
    private $path;
    private $lastEdit;

    public function __construct($path, ExtensionManager $driver = null)
    {
        $this->path = $path;
        $this->config = array();
        $this->lastEdit = 0;
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
            throw new ItemNotExistException($key);
        }
    }
    protected function checkNotExist($key)
    {
        if ($this->exist_config($key))
        {
            throw new ItemAlreadyExistException($key);
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
    protected function checkFileViolation($lastEdit)
    {
        clearstatcache();
        if ($lastEdit != 0 && filemtime($this->path) - $lastEdit > FILETIME_TOLLERANCE)
        {
            throw new FileViolationException($this->path);
        }
    }
    protected function checkParse()
    {
        if (!empty($this->config))
        {
            $this->checkFileViolation($this->lastEdit);
            return;
        }
        $this->config = $this->decodeConfig($this->openConfig($this->path));
        ConfigManager::debug(__class__);
    }
    protected function getPath()
    {
        return $this->path;
    }
    protected function getConfig()
    {
        return $this->config;
    }
    protected function saveConfig($config = null)
    {
        if ($config !== null)
            $this->config = $config;
        $this->onlySaveConfig($this->encodeConfig($this->config), $this->path);
        $this->lastEdit = time();
    }
    protected function openConfig($path)
    {
        $content = '';
        try
        {
            $content = $this->onlyOpenConfig($path);
        }
        catch (FileNotExistException $e)
        {
            $this->onFileNotExistException();
            $content = $this->onlyOpenConfig($path);
        }
        return $content;
    }
    protected function onFileNotExistException()
    {
        $this->saveConfig();
    }
    protected function onlyOpenConfig($path)
    {
        return SafeIO::open($path);
    }
    protected function onlySaveConfig($content, $path)
    {
        SafeIO::save($content, $path);
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