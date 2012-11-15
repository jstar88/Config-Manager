<?php

namespace ConfigManager\Modules\Pdo;

use \ConfigManager\Exceptions\ItemNotUniqueException as ItemNotUniqueException;
use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Core\ConfigManager as ConfigManager;
use \ConfigManager\Modules\File\FileManager as FileManager;
use \PDO as PDO;
use \ConfigManager\Interfaces\Manager as Manager;

class PdoManager extends FileManager
{
    private $addList = array();
    private $setList = array();
    private $replaceList = array();
    private $debug = array();
    private $debug_on = true;

    protected $dbType, $dbHost, $dbName, $userName, $userPassword, $driverOptions, $tableName, $keyColumn, $valueColumn;
    
    //---- override ----
    protected function checkExist($key)
    {
    }
    protected function checkAdd($key)
    {
        //do nothing
    }
    protected function checkSet($key)
    {
        //do nothing
    }
    protected function checkReplace($key)
    {
        //do nothing
    }
    protected function checkFileViolation($lastEdit)
    {
        //do nothing
    }
    
    public function __construct(Manager $driver)
    {
        $service = 'php';
        if($driver->exist('service'))
        {
            $service = $driver->get('service');
        }
        $managerClass = "\\ConfigManager\\Modules\\$service\\{$service}Manager";
        $driver = new $managerClass($driver);
        parent::__construct($driver);
        $assign = array(
            'dbType',
            'dbHost',
            'dbName',
            'userName',
            'userPassword',
            'driverOptions',
            'tableName',
            'keyColumn',
            'valueColumn');
        foreach ($assign as $name)
        {
            $this->assignDriverValue($name);
        }
    }
    protected function openConfig($path)
    {
        $dsn = "{$this->dbType}:host={$this->dbHost};dbname={$this->dbName}";
        $db = new PDO($dsn, $this->userName, $this->userPassword, $this->driverOptions);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
    protected function get_config($key)
    {
        $db = parent::getConfig();
        $queryStr = "SELECT {$this->valueColumn} FROM {$this->tableName} WHERE {$this->keyColumn} = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $this->debug($queryStr.': '.__FUNCTION__.'='.$key);
        $count = $query->rowCount();
        if ($count > 1)
        {
            throw new ItemNotUniqueException($key);
        }
        if ($count == 0)
        {
            throw new ItemNotExistException($key);
        }
        $return = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return unserialize($return[$this->keyColumn]);

    }
    protected function asArray_config()
    {
        $db = parent::getConfig();
        $queryStr = "SELECT {$this->keyColumn},{$this->valueColumn} FROM {$this->tableName}";
        $query = $db->prepare($queryStr);
        $query->execute();
        $rows = $query->fetchAll();
        $query->closeCursor();
        $this->debug($queryStr.': '.__FUNCTION__);
        $return = array();
        foreach ($rows as $row)
        {
            $return[$row[$this->keyColumn]] = unserialize($row[$this->keyColumn]);
        }
        return $return;
    }
    protected function exist_config($key)
    {
        $db = parent::getConfig();
        $queryStr = "SELECT {$this->valueColumn} FROM {$this->tableName} WHERE {$this->keyColumn} = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $count = $query->rowCount();
        $query->closeCursor();
        $this->debug($queryStr.': '.__FUNCTION__.'='.$key);
        if ($count > 0)
        {
            return true;
        }
        return false;

    }
    protected function delete_config($key)
    {
        $db = parent::getConfig();
        $queryStr = "DELETE FROM {$this->tableName} WHERE {$this->keyColumn} = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $query->closeCursor();
        $this->debug($queryStr);
    }
    protected function assign($key, $value, $check)
    {
        if ($check == 'checkAdd')
        {
            $this->addList[$key] = $value;
        }
        elseif($check  == 'checkSet')
        {
            $this->setList[$key] = $value;
        }
        else
        {
            $this->replaceList[$key] = $value;
        }
    }
    protected function saveConfig()
    {
        $db = parent::getConfig();
        $addQuery = '';
        $setQuery = '';

        try
        {
            $db->beginTransaction();

            if (!empty($this->setList))
            {

                $addQuery = "INSERT INTO {$this->tableName} ({$this->keyColumn}, {$this->valueColumn} ) VALUES ";
                foreach ($this->setList as $key => $value)
                {
                    $value = $db->quote(serialize($value));
                    $key = $db->quote($key);
                    $addQuery .= "( $key , $value ),";
                }
                $addQuery = substr($addQuery, 0, -1);
                $db->exec($addQuery);
                $this->debug($addQuery);
            }
            if (!empty($this->replaceList))
            {
                $ids = implode("','", array_keys($this->setList));
                $setQuery = "UPDATE {$this->tableName} SET {$this->valueColumn} = CASE {$this->keyColumn} ";
                foreach ($this->setList as $key => $value)
                {
                    $value = $db->quote(serialize($value));
                    $key = $db->quote($key);
                    $setQuery .= "WHEN $key THEN $value ";
                }
                $setQuery .= "END WHERE {$this->keyColumn} IN ('$ids')";
                $db->exec($setQuery);
                $this->debug($setQuery);
            }
            $db->commit();
        }
        catch (exception $e)
        {
            $db->rollBack();
            throw $e;
        }
        $this->addList= array();
        $this->setList=array();
        $this->replaceList=array();
    }
    private function debug($sql)
    {
        if ($this->debug_on)
        {
            ConfigManager::debug(__class__, $sql);
        }
    }
    public function showDebug()
    {
        echo implode('<br>' . PHP_EOL, ConfigManager::$debug[__class__]['trace']);
    }
    

}
