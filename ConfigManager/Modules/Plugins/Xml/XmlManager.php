<?php

namespace ConfigManager\Modules\Plugins\Xml;

use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Utils\DataFormat as DataFormat;
use \ConfigManager\Modules\Plugins\Xml\Exceptions\XmlException as XmlException;
use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemNotUniqueException as ItemNotUniqueException;
use \ConfigManager\Modules\System\File\FileManager as FileManager;

class XmlManager extends FileManager
{
    protected $name = 'name';
    protected $value = 'value';
    protected $config = 'config';
    public function __construct(Manager $driver)
    {
        parent::__construct($driver);
        $this->assignDriverValue('name');
        $this->assignDriverValue('value');
        $this->assignDriverValue('config');
    }
    //----  methods of interfaces ----
    protected function get_config($key)
    {
        return DataFormat::unserialize((string )$this->get_xml_entity($key)->value);
    }
    protected function delete_config($key)
    {
        $seg = $this->get_xml_entity($key);
        $dom = dom_import_simplexml($seg);
        $dom->parentNode->removeChild($dom);
    }
    protected function asArray_config()
    {
        $config = array();
        $x = parent::getConfig()->children();
        foreach ($x as $xmlObject)
        {
            $string = (string )$xmlObject->value;
            $config[(string )$xmlObject->name] = DataFormat::unserialize($string);
        }
        return $config;
    }
    protected function exist_config($key)
    {
        try
        {
            $this->get_xml_entity($key);
        }
        catch (ItemNotExistException $e)
        {
            return false;
        }
        return true;
    }
    //--------------------------------

//---- override ----
    protected function checkExist($key)
    {
        //do nothing
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
    protected function assign($key, $value, $check)
    {
        $this->get_xml_entity($key, $check)->value = DataFormat::serialize($value);
    }
    protected function decodeConfig($content)
    {
        $config = simplexml_load_string($content);
        if ($config === false)
            throw new XmlException('parsing xml file');
        return $config;
    }
    protected function encodeConfig($config)
    {
        $content = $config->asXML();
        if ($content === false)
        {
            throw new XmlException('there are syntax errors inside xml file');
        }
        return $content;
    }
    protected function onFileNotExistException()
    {
        $this->saveConfig($this->decodeConfig('<?xml version="1.0"?><configurations></configurations>'));
    }

    //------------------

    /**
     * xml->get_xml_entity()
     * Search in the xml for a entity rappresented by $config_name 
     * 
     * @param String $config_name: the key
     * @return SimpleXMLElement object
     */
    private function get_xml_entity($config_name, $check = '')
    {
        //searching inside <configurations> and where config name=$config_name
        $result = $this->doXpathQuery("/configurations/config[{$this->name}=\"{$config_name}\"]");
        //if don't exist create it

        if (empty($result))
        {
            if ($check == 'checkSet' || $check == 'checkAdd')
            {
                $new_conf = parent::getConfig()->addChild($this->config);
                $new_conf->addChild($this->name, $config_name);
                $new_conf->addChild($this->value);
                $result = $new_conf;
            }
            else
            {
                throw new ItemNotExistException($config_name);
            }
        }
        //if multiple result are returned so key is not unique
        elseif (count($result) !== 1)
        {
            throw new ItemNotUniqueException($config_name);
        }
        list($result) = $result;
        return $result;
    }
    /**
     * xml->doXpathQuery()
     * This function execute a Xpath query
     * 
     * @param String $query
     * @return Array
     */
    private function doXpathQuery($query)
    {
        $result = parent::getConfig()->xpath($query);
        if ($result === false)
        {
            throw new XmlException('there is an error in the xpath query');
        }
        return $result;
    }
}
