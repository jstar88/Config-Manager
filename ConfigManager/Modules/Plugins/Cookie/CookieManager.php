<?php

namespace ConfigManager\Modules\Plugins\Cookie;

use \ConfigManager\Modules\System\File\Exceptions\FileNotExistException as FileNotExistException;
use \ConfigManager\Modules\System\File\Exceptions\FileViolationException as FileViolationException;
use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Modules\Plugins\Cookie\Exceptions\CookieException as CookieException;
use \ConfigManager\Modules\Plugins\Json\JsonManager as JsonManager;

class CookieManager extends JsonManager
{
    const LAST_EDIT_KEY = 'cm_last_edit';
    protected $cookieName = 'myCookie';
    protected $cookieLife = 3600;
    protected $cookiePath = '/';
    protected $cookieDomain = '';
    protected $cookieSSL = false;
    protected $cookieHttpOnly = false;
    protected $validationKey = 'dqpnrxut';

    public function __construct(Manager $driver)
    {
        parent::__construct($driver);
        $assign = array(
            'cookieName',
            'cookieLife',
            'cookiePath',
            'cookieDomain',
            'cookieSSL',
            'cookieHttpOnly',
            'validationKey');
        $this->assignDriverValues($assign);
    }
    protected function decodeConfig($content)
    {
        if (extension_loaded('zlib'))
        {
            $content = gzinflate($content);
            if ($content === false)
            {
                throw new CookieException('invalid compression format');
            }
        }
        if (extension_loaded('mcrypt'))
        {
            $key = md5($this->validationKey);
            $iv = md5($key);
            try
            {
                $content = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_CBC, $iv), "\0");
            }
            catch (exception $e)
            {
                throw new CookieException($e->getMessage());
            }
        }
        $content = parent::decodeConfig($content);
        return $content;
    }
    protected function encodeConfig($config)
    {
        $content = parent::encodeConfig($config);
        if (extension_loaded('mcrypt'))
        {
            $key = md5($this->validationKey);
            $iv = md5($key);
            try
            {
                $content = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $content, MCRYPT_MODE_CBC, $iv);
            }
            catch (exception $e)
            {
                throw new CookieException($e->getMessage());
            }
        }
        if (extension_loaded('zlib'))
        {
            $content = gzdeflate($content, 9);
            if ($content === false)
            {
                throw new CookieException('while compressing content');
            }
        }
        return $content;
    }
    protected function onlyOpenConfig($path)
    {
        if (isset($_COOKIE[$this->cookieName]))
        {
            return $_COOKIE[$this->cookieName];
        }
        throw new FileNotExistException($path);
    }
    protected function onlySaveConfig($content, $path)
    {
        if (headers_sent())
        {
            $script = "<script type=\"text/javascript\">" . PHP_EOL;
            $script .= 'document.cookie = ' . $this->cookieName . '=' . $content . ';';
            $script .= 'expires=' . $this->cookieLife + time() . ';';
            $script .= 'path=' . $this->cookiePath . ';';
            $script .= PHP_EOL . "</script>" . PHP_EOL;
            echo $script;
        }
        else
        {
            setcookie($this->cookieName, $content, time() + $this->cookieLife, $this->cookiePath, $this->cookieDomain, $this->cookieSSL, $this->cookieHttpOnly);
        }
    }
    protected function checkFileViolation($lastEdit)
    {
        if ($lastEdit != 0 && abs(parent::get_config(self::LAST_EDIT_KEY) - $lastEdit) > $this->fileTimeTol)
        {
            throw new FileViolationException($this->cookieName);
        }
    }
    protected function saveConfig($config = null)
    {
        parent::set_not_save(self::LAST_EDIT_KEY, time());
        parent::saveConfig();
    }
    protected function onFileNotExistException()
    {
        $_COOKIE[$this->cookieName] = $this->encodeConfig(parent::getConfig());
    }
}
