<?php
require_once (MANAGERS.'PhpManager.php');
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
        if (!file_exists($path))
        {
            parent::add_config( $this->primitive->asArray());
            parent::saveConfig();
        }
        return parent::openConfig($path);
    }
    public function add_config($key, $value = false)
    {
        parent::add_config($key,$value);
        $this->primitive->add($key, $value);
    }
    public function set_config($key, $value = false)
    {
        parent::set_config($key,$value);
        $this->primitive->set($key, $value);
    }
    public function delete_config($key)
    {
        parent::delete_config($key);
        $this->primitive->delete($key);
    }
}

?>