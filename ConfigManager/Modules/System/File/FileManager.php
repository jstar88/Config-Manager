<?php

namespace ConfigManager\Modules\System\File;

use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Utils\SafeIO as SafeIO;
use \ConfigManager\Modules\System\File\Exceptions\FileNotExistException as FileNotExistException;
use \ConfigManager\Modules\System\File\Exceptions\FileViolationException as FileViolationException;
use \ConfigManager\Core\ConfigManager as ConfigManager;
use \ConfigManager\Modules\System\Simple\SimpleManager as SimpleManager;

class FileManager extends SimpleManager
{
    protected $dataPath = '';
    protected $lastEdit;
    protected $checkFileViolationValue = true;
    protected $initialized;
    protected $fileTimeTol = 3;
    protected $driver;

    public function __construct(Manager $driver = null)
    {
        parent::__construct();
        $this->driver = $driver;
        $this->assignDriverValue('dataPath');
        $this->assignDriverValue('checkFileViolationValue');
        $this->assignDriverValue('fileTimeTol');
        $this->lastEdit = 0;
        $this->initialized = false;
        $this->id = $this->dataPath;
    }
    //----  methods of interfaces ----
    public function set($key, $value = false)
    {
        parent::set($key, $value);
        $this->saveConfig();
    }
    public function add($key, $value = false)
    {
        parent::add($key, $value);
        $this->saveConfig();
    }
    public function replace($key, $value = false)
    {
        parent::replace($key);
        $this->saveConfig();
    }
    public function delete($key)
    {
        parent::delete($key);
        $this->saveConfig();
    }
    public function set_not_save($key, $value = false)
    {
        parent::set($key, $value);    
    }
    //---------------------------
    protected function checkFileViolation($lastEdit)
    {
        clearstatcache();
        ConfigManager::debug(get_class($this), 'clearstatcache');
        if ($lastEdit != 0 && abs(filemtime($this->dataPath) - $lastEdit) > $this->fileTimeTol)
        {
            throw new FileViolationException($this->dataPath);
        }
    }
    protected function checkService()
    {
        if ($this->initialized)
        {
            if ($this->checkFileViolationValue)
            {
                $this->checkFileViolation($this->lastEdit);
            }
            return;
        }
        parent::setConfig($this->decodeConfig($this->openConfig($this->dataPath)));
        $this->initialized = true;
        ConfigManager::debug(get_class($this), 'checkService');
    }
    protected function getPath()
    {
        return $this->path;
    }
    protected function saveConfig($config = null)
    {
        if ($config !== null)
            parent::setConfig($config);
        $this->onlySaveConfig($this->encodeConfig(parent::getConfig()), $this->dataPath);
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
