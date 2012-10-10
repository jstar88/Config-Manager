<?php

class XmlManager extends Manager
{
    private $config;

    public function get($key)
    {
        parent::checkParse();        
        return $this->un_serialize((string) $this->get_xml_entity($key)->value);
    }
    public function delete($key)
    {
        parent::checkParse();  
        $seg=$this->get_xml_entity($key);
        $dom=dom_import_simplexml($seg);
        $dom->parentNode->removeChild($dom);
        parent::saveConfig();        
    }
    public function exist($key)
    {
        parent::checkParse();        
        try
        {
            $this->get_xml_entity($key,false);
        }
        catch (Exception $e) 
        {
            return false;    
        } 
        return true;
    }
    public function asArray()
    {
        parent::checkParse();
        $config = array();
        $x = parent::getConfig()->children();
        foreach ($x as $xmlObject)
        {
            $string=(string )$xmlObject->value;           
            $config[(string )$xmlObject->name] = $this->un_serialize($string);
        }
        return $config;
    }
    private function un_serialize($string)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $var=  unserialize($string); 
        return ($var=== false)? $string : $var;       
    }

    /**
     * xml->get_xml_entity()
     * Search in the xml for a entity rappresented by $config_name 
     * 
     * @param String $config_name: the key
     * @return SimpleXMLElement object
     */
    private function get_xml_entity($config_name, $can_add = false)
    {
        //searching inside <configurations> and where config name=$config_name
        $result = $this->doXpathQuery('/configurations/config[name="' . $config_name . '"]');
        //if don't exist create it

        if (empty($result))
        {
            if ($can_add)
            {
                $new_conf = parent::getConfig()->addChild('config');
                $new_conf->addChild('name', $config_name);
                $new_conf->addChild('value');
                $result = $new_conf;
            }
            else
            {
                throw new Exception(sprintf('Item with id "%s" does not exists.', $config_name));
            }
        }
        //if multiple result are returned so key is not unique
        elseif (count($result) !== 1)
        {
            throw new Exception(sprintf('Item with id "%s" is not unique.', $config_name));
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
            throw new Exception('there is an error in the xpath query');
        }
        return $result;
    }
    protected function assign($key,$value,$can_add)
    {
        if(is_array($value) || is_object($value))
        {
            $value=serialize($value);
        }
        $this->get_xml_entity($key, $value,$can_add)->value = $value; 
    }
    protected function decodeConfig($content)
    {
        $config = simplexml_load_string($content);
        if ($config === false)
            throw new Exception('Error parsing xml file');
        return $config;
    }
    protected function encodeConfig($config)
    {
        $content = $config->asXML();
        if ($content === false)
        {
            throw new Exception('Error: there are syntax errors on xml file');
        }
        return $content;
    }
}

?>