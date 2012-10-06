This is a configurations managers with automatic extension detection.

It is very easy and powerfull at the same time!
Don't warry about caching or others IO procedures, it manages them in _atomic_ way: data is always ready, updated an served with a cache if possible.
All what you need to know are 2 thinks:


1. how istantiate the config-Manager: using the static method **getIstance()**

``` php
        <?php
         include 'ConfigManager.php';
         $myXmlConfigFile='myConfig.xml';
         $data=ConfigManager::getIstance($myXmlConfigFile);
        ?>
```

2. how you can manage your data:
using the methods defined by this interface:

``` php
        <?php
        interface ExtensionManager
        {
                public function get($key);
                public function delete($key);      
                public function set($key, $value=false);
                public function add($key, $value=false);
                public function exist($key);
                public function asArray();
        }
        ?>
```

for example:

``` php
        <?php
         include 'ConfigManager.php';
         $myXmlConfigFile='myConfig.xml';
         $data=ConfigManager::getIstance($myXmlConfigFile);
         $data->get('aKey'); 
        ?>
```
