<?php

class MysqlManager extends Manager
{
    private $addList;
    private $setList;
    public function __construct($path)
    {
        parent::__construct(new PhpManager($path));
        $this->addList = array();
        $this->setList = array();
    }

    //----  methods of interfaces ----

    public function get_config($key)
    {
        $key = mysql_real_escape_string($key);
        $key_name = $this->getPath()->get('key');
        $key_value = $this->getPath()->get('value');
        $result = $this->doquery("SELECT $key_value FROM {{table}} WHERE $key_name = '$key';");
        $count = mysql_num_rows($result);
        if ($count > 1)
            throw new Exception(sprintf('Item with id "%s" is not unique.', $key));
        if ($count == 0)
            throw new Exception(sprintf('Item with id "%s" does not exists.', $key));
        $result = mysql_fetch_array($result);
        return DataFormat::unserialize(stripcslashes($result[$key_value]));

    }
    public function asArray_config()
    {
        $key_name = $this->getPath()->get('key');
        $key_value = $this->getPath()->get('value');
        $result = $this->doquery("SELECT $key_name,$key_value FROM {{table}} ;");
        $info = array();
        while ($row = mysql_fetch_array($result))
        {
            $info[$row[$key_name]] = DataFormat::unserialize($row[$key_value]);
        }
        return $info;
    }
    public function exist_config($key)
    {
        $key_name = $this->getPath()->get('key');
        $key_value = $this->getPath()->get('value');
        $result = $this->doquery("SELECT $key_value FROM {{table}} WHERE $key_name = '$key';");
        return mysql_num_rows($result) > 0;
    }
    public function delete_config($key)
    {
        $key_name = $this->getPath()->get('key');
        $key_value = $this->getPath()->get('value');
        $this->doquery("DELETE FROM {{table}} WHERE $key_name = '$key'");
        if(isset($this->addList[$key]))
            unset($this->addList[$key]);
        if(isset($this->setList[$key]))
            unset($this->setList[$key]);
    }
    //--------------------------------

    //---- override ----

    protected function assign($key, $value, $can_add)
    {
        if ($can_add)
            $this->addList[$key] = $value;
        else
            $this->setList[$key] = $value;
    }
    protected function saveConfig()
    {
        $key_name = $this->getPath()->get('key');
        $key_value = $this->getPath()->get('value');

        if (!empty($this->addList))
        {

            $addString = "INSERT INTO {{table}} ($key_name, $key_value ) VALUES ";
            foreach ($this->addList as $key => $value)
            {
                $value=mysql_real_escape_string( DataFormat::serialize($value));
                $addString .= "( '$key' , '$value' ),";
            }
            $addString = substr($addString, 0, -1);
            $addString .= ';';
            $this->doquery($addString);
        }
        if (!empty($this->setList))
        {
            $ids = implode(',', array_keys($this->setList));
            $setString = "UPDATE {{table}} SET $key_value = CASE $key_name ";
            foreach ($this->setList as $key => $value)
            {
                $value=mysql_real_escape_string(DataFormat::serialize($value));                
                $setString .= sprintf("WHEN '%d' THEN '%d' ", $key, $value);
            }
            $setString .= "END WHERE $key_name IN ($ids);";
            $this->doquery($setString);
        }
    }
    protected function openConfig($dbsettings)
    {
        $id = mysql_connect($dbsettings->get("server"), $dbsettings->get("user"), $dbsettings->get("pass")) or $this->debug('DB Connection failed: wrong server or account');
        mysql_select_db($dbsettings->get("name")) or $this->debug('DB Connection failed: name');
        return $id;
    }
    //--------------------------------
    private function debug($error)
    {
        throw new Exception($error);
    }
    private function doquery($query)
    {
        //echo $query.'<br>';
        $sql = str_replace("{{table}}", $this->getPath()->get("table"), $query);
        $sqlquery = mysql_query($sql) or $this->debug(mysql_error() . "<br />$sql<br />", "SQL Error");
        return $sqlquery;

    }
    public function __destruct()
    {
        $id = parent::getConfig();
        if (!empty($id))
            mysql_close($id);
    }
}

?>