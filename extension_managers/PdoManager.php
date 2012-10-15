<?php

require_once (MANAGERS . 'PhpManager.php');
require_once (EXCEPTIONS . 'ItemNotUniqueException.php');
class PdoManager extends Manager
{
    private $driver;
    private $addList;
    private $setList;
    private $debug=array();
    private $debug_on=false;

    public function __construct($path, ExtensionManager $driver = null)
    {
        parent::__construct('');
        $this->driver = $driver;
        $this->addList = array();
        $this->setList = array();
    }
    protected function openConfig($path)
    {
        $dbType = $this->driver->get('db_type');
        $dbHost = $this->driver->get('db_host');
        $dbName = $this->driver->get('db_name');
        $username = $this->driver->get('user_name');
        $password = $this->driver->get('user_password');
        $driver_options = $this->driver->get('driver_options');

        $dsn = "$dbType:host=$dbHost;dbname=$dbName";
        $db = new PDO($dsn, $username, $password, $driver_options);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
    protected function get_config($key)
    {
        $key_name = $this->driver->get('key');
        $key_value = $this->driver->get('value');
        $table = $this->driver->get('table');
        $db = parent::getConfig();
        $queryStr="SELECT $key_value FROM $table WHERE $key_name = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $this->debug($queryStr);
        $count = $query->rowCount();
        if ($count > 1)
            throw new ItemNotUniqueException($key);
        if ($count == 0)
            throw new ItemNotExistException($key);
        $return = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return unserialize($return[$key_value]);

    }
    protected function asArray_config()
    {
        $key_name = $this->driver->get('key');
        $key_value = $this->driver->get('value');
        $table = $this->driver->get('table');
        $db = parent::getConfig();
        $queryStr="SELECT $key_name,$key_value FROM $table";
        $query = $db->prepare($queryStr);
        $query->execute();
        $rows = $query->fetchAll();
        $query->closeCursor();
        $this->debug($queryStr);
        $return = array();
        foreach ($rows as $row)
        {
            $return[$row[$key_name]] = unserialize($row[$key_value]);
        }
        return $return;
    }
    protected function exist_config($key)
    {
        $key_name = $this->driver->get('key');
        $key_value = $this->driver->get('value');
        $table = $this->driver->get('table');
        $db = parent::getConfig();
        $queryStr="SELECT $key_value FROM $table WHERE $key_name = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $count = $query->rowCount();
        $query->closeCursor();
        $this->debug($queryStr);
        if ($count == 1)
            return true;
        return false;

    }
    protected function delete_config($key)
    {
        $key_name = $this->driver->get('key');
        $key_value = $this->driver->get('value');
        $table = $this->driver->get('table');
        $db = parent::getConfig();
        $queryStr="DELETE FROM $table WHERE $key_name = :key_name";
        $query = $db->prepare($queryStr);
        $query->bindValue(':key_name', $key, PDO::PARAM_STR);
        $query->execute();
        $query->closeCursor();
        if (isset($this->addList[$key]))
            unset($this->addList[$key]);
        if (isset($this->setList[$key]))
            unset($this->setList[$key]);
        $this->debug($queryStr);
    }
    protected function assign($key, $value, $can_add)
    {
        if ($can_add)
            $this->addList[$key] = $value;
        else
            $this->setList[$key] = $value;
    }
    protected function saveConfig()
    {
        $key_name = $this->driver->get('key');
        $key_value = $this->driver->get('value');
        $table = $this->driver->get('table');
        $db = parent::getConfig();

        $addQuery = '';
        $setQuery = '';

        try
        {
            $db->beginTransaction();

            if (!empty($this->addList))
            {

                $addQuery = "INSERT INTO $table ($key_name, $key_value ) VALUES ";
                foreach ($this->addList as $key => $value)
                {
                    $value = $db->quote(serialize($value));
                    $key = $db->quote($key);
                    $addQuery .= "( $key , $value ),";
                }
                $addQuery = substr($addQuery, 0, -1);
                $db->exec($addQuery);
                $this->debug($addQuery);
            }
            if (!empty($this->setList))
            {
                $ids = implode("','", array_keys($this->setList));
                $setQuery = "UPDATE $table SET $key_value = CASE $key_name ";
                foreach ($this->setList as $key => $value)
                {
                    $value = $db->quote(serialize($value));
                    $key = $db->quote($key);
                    $setQuery .= "WHEN $key THEN $value ";
                }
                $setQuery .= "END WHERE $key_name IN ('$ids')";
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
    }
    private function debug($sql)
    {
        if($this->debug_on)
        {
            $this->debug[]=$sql;
        }
    }
    public function showDebug()
    {
        echo implode('<br>',$this->debug);
    }
    //---- override ----
    protected function checkExist($key)
    {
        //do nothing
    }
    protected function checkNotExist($key)
    {
        //do nothing
    }

}

?>