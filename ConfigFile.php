<?php
class ConfigFile
{
    public $dataPath;
    public $driverPath;
    public $cacheDriverPath;
    
    public function getId()
    {
        return $this->dataPath.$this->driverPath.$this->cacheDriverPath;
    }
}
?>