## Introduction

This is a configurations managers with automatic extension detection.

It is very easy and powerfull at the same time!


Don't warry about caching or others IO procedures, it manages them in _atomic_ way: data is always ready, updated an served with a cache if possible.


All what you need to know are 2 thinks:


* how istantiate the config-Manager: using the static method **getInstance()** with the data file as first parameter. 

``` php
        <?php
         include 'ConfigManager.php';
         $myXmlConfigFile='myConfig.xml';
         $data=ConfigManager::getInstance($myXmlConfigFile);
        ?>
```

* how you can manage your data:
using the methods defined by this interface:

``` php
        <?php
        interface ExtensionManager
        {
                //return a value stored with the key passed as param
                public function get($key);
                //delete a value stored with the key passed as param 
                public function delete($key);
                //store an value that can be an object,array or basic type in the corrispective existing key, or store all variables inside an array or object passed like key  
                public function set($key, $value=false);
                //like before, but this time you can add new key only if not exist
                public function add($key, $value=false);
                //return true if the corrispective value exist
                public function exist($key);
                //return an associative array of key,value of all data
                public function asArray();
        }
        ?>
```

for example:

``` php
        <?php
         include 'ConfigManager.php';
         $myXmlConfigFile='myConfig.xml';
         $data=ConfigManager::getInstance($myXmlConfigFile);
         $data->get('aKey'); 
        ?>
```

## Caching
To set on the caching you just need to provide the name of cached file(with php extension) as second parameter of _getInstance()_ method

for example:

``` php
        <?php
         include 'ConfigManager.php';
         $myXmlConfigFile='configs/myConfig.xml';
         $data=ConfigManager::getInstance($myXmlConfigFile,$myXmlConfigFile.'.php');
         $data->get('aKey'); 
        ?>
```

## Compatible cache systems 

* PHP
* MYSQL - MEMORY Storage Engine (working on)
* MEMCACHE (working on)
* MEMCACHED (working on)
* APC (working on)
* XCACHE (working on)

## Compatible data formats

* XML
* PHP
* JSON
* INI
* YAML
* MYSQL

## Extensions standard
Some types of markup language require to follow conventions to work on ConfigManager:
* _XML_:
 Must respect the format:

``` xml
     <?xml version="1.0"?>
    <configurations>
        <config>
            <name>a key </name>
            <value>a value </value>
        </config>
    <configurations>
```
* _PHP_: 
 Must have defined a variable _$config_ :

``` php
        <?php
         $config=array(
         'a key' => 'a value'
         );
        ?>
```
* _MYSQL_:
 You need a configuration file with database informations like
 
 ``` php
      <?php
         $config=array(
            'server' => 'localhost',
            'user' =>'root',
            'pass' => '',
            'name' => 'game',
            'table' => 'config',
            'key' => 'config_name',
            'value' => 'config_value'
         );
      ?>
```
 