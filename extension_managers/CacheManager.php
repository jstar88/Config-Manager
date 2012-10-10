<?php
include_once 'PhpManager.php';
class CacheManager extends PhpManager
{
    private $primitive;

    public function __construct($path, ExtensionManager $primitive)
    {
        parent::__construct($path);
        $this->primitive = $primitive;
    }
    protected function openConfig($path)
    {
        if (!file_exists(parent::getPath()))
        {
            parent::saveConfig();
            parent::add( $this->primitive->asArray());
        }
        return parent::openConfig($path);
    }
    public function add($key, $value = false)
    {
        $this->primitive->add($key, $value);
        parent::add($key,$value);
    }
    public function set($key, $value = false)
    {
        $this->primitive->set($key, $value);
        parent::set($key,$value);
    }
    public function delete($key)
    {
        $this->primitive->delete($key);
        parent::delete($key);
    }
}

?>