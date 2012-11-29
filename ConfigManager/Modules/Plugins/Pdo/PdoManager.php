<?php

namespace ConfigManager\Modules\Plugins\Pdo;

use \ConfigManager\Exceptions\ItemNotUniqueException as ItemNotUniqueException;
use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Core\ConfigManager as ConfigManager;
use \ConfigManager\Modules\System\NonPersistentService\NonPersistentServiceManager as NonPersistentServiceManager;
use \PDO as PDO;
use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Modules\Plugins\Pdo\Exceptions\PdoException as PdoException;

class PdoManager extends NonPersistentServiceManager
{
    private $debug_on = true;
    protected $dbType, $dbHost, $dbName, $userName, $userPassword, $driverOptions, $tableName, $keyColumn, $valueColumn;

    public function __construct(Manager $driver)
    {
        $names = array(
            'dbType',
            'dbHost',
            'dbName',
            'userName',
            'userPassword',
            'driverOptions',
            'tableName',
            'keyColumn',
            'valueColumn');
        $this->assignDriverValues($names);
    }
    public function getId()
    {
        return "{$this->dbType}:host={$this->dbHost};dbname={$this->dbName}";
    }
    protected function startService()
    {
        $dsn = $this->getId();
        $db = new PDO($dsn, $this->userName, $this->userPassword, $this->driverOptions);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->service = $db;
    }
    protected function stopService()
    {
        $this->service = null;
    }
    protected function get_config($key)
    {
        $db = $this->service;
        $queryStr = "SELECT {$this->valueColumn} FROM {$this->tableName} WHERE {$this->keyColumn} = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $this->debug($queryStr . ': ' . __function__ . '=' . $key);
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
        $db = $this->service;
        $queryStr = "SELECT {$this->keyColumn},{$this->valueColumn} FROM {$this->tableName}";
        $query = $db->prepare($queryStr);
        $query->execute();
        $rows = $query->fetchAll();
        $query->closeCursor();
        $this->debug($queryStr . ': ' . __function__ );
        $return = array();
        foreach ($rows as $row)
        {
            $return[$row[$this->keyColumn]] = unserialize($row[$this->keyColumn]);
        }
        return $return;
    }
    protected function exist_config($key)
    {
        $db = $this->service;
        $queryStr = "SELECT {$this->valueColumn} FROM {$this->tableName} WHERE {$this->keyColumn} = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $count = $query->rowCount();
        $query->closeCursor();
        $this->debug($queryStr . ': ' . __function__ . '=' . $key);
        if ($count > 0)
        {
            return true;
        }
        return false;

    }
    protected function delete_config($key)
    {
        $db = $this->service;
        $queryStr = "DELETE FROM {$this->tableName} WHERE {$this->keyColumn} = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $query->closeCursor();
        $this->debug($queryStr);
    }
    protected function write_config($config_name, $config_value, $check)
    {
        parent::write_config($config_name, $config_value, $check);
        $db = $this->service;
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
            throw new PdoException($e->getMessage());
        }
        $this->addList = array();
        $this->setList = array();
        $this->replaceList = array();
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
        if(isset(ConfigManager::$debug[__class__]))
            echo implode('<br>' . PHP_EOL, ConfigManager::$debug[__class__]['trace']);
    }


}
