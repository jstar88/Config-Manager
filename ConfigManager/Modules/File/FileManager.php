<?php

namespace ConfigManager\Modules\File;

use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Utils\SafeIO as SafeIO;
use \ConfigManager\Modules\File\Exceptions\FileNotExistException as FileNotExistException;
use \ConfigManager\Modules\File\Exceptions\FileViolationException as FileViolationException;
use \ConfigManager\Core\ConfigManager as ConfigManager;
use \ConfigManager\Modules\Simple\SimpleManager as SimpleManager;

class FileManager extends SimpleManager
{
    private $dataPath = '';
    private $lastEdit;
    private $checkFileViolationValue = true;
    private $initialized;
    private $fileTimeTol = 3;
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
    protected function assignDriverValue($name)
    {
        if ($this->driver == null || !$this->driver->exist($name))
            return;
        $this->{$name} = $this->driver->get($name);
    }
    //----  methods of interfaces ----
    public function set($key, $value = false)
    {
        $this->checkParse();
        parent::set($key, $value);
        $this->saveConfig();
    }
    public function add($key, $value = false)
    {
        $this->checkParse();
        parent::add($key, $value);
        $this->saveConfig();
    }
    public function replace($key, $value = false)
    {
        $this->checkParse();
        parent::replace($key);
        $this->saveConfig();
    }
    public function get($key)
    {
        $this->checkParse();
        return parent::get($key);
    }
    public function asArray()
    {
        $this->checkParse();
        return parent::asArray();
    }
    public function exist($key)
    {
        $this->checkParse();
        return parent::exist($key);
    }
    public function delete($key)
    {
        $this->checkParse();
        parent::delete($key);
        $this->saveConfig();
    }
    public function merge(Manager $from)
    {
        $this->checkParse();
        parent::merge($from);
    }
    //---------------------------
    protected function checkFileViolation($lastEdit)
    {
        clearstatcache();
        ConfigManager::debug(__class__, 'clearstatcache');
        if ($lastEdit != 0 && abs(filemtime($this->dataPath) - $lastEdit) > $this->fileTimeTol)
        {
            throw new FileViolationException($this->dataPath);
        }
    }
    protected function checkParse()
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
        ConfigManager::debug(__class__, 'checkParse');
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
