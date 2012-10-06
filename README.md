This is a configurations managers with automatic extension detection.

It is very easy and powerfull at the same time!


Don't warry about caching or others IO procedures, it manages them in _atomic_ way: data is always ready, updated an served with a cache if possible.


All what you need to know are 2 thinks:


* how istantiate the config-Manager: using the static method **getIstance()** with the data file as first parameter and (optionally but necessary for caching) the name that will have the cached data. 

``` php
        <?php
         include 'ConfigManager.php';
         $myXmlConfigFile='myConfig.xml';
         $data=ConfigManager::getIstance($myXmlConfigFile);
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
                //store an value that can be an object,array or basic type in the corrispective key, or store all variables inside an array or object passed like key  
                public function set($key, $value=false);
                //like before, but this time you can also add new key
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
         $data=ConfigManager::getIstance($myXmlConfigFile);
         $data->get('aKey'); 
        ?>
```
## Extensions supported

* XML: Extensible Markup Language
* PHP: Hypertext Preprocessor
* JSON: JavaScript Object Notation
* INI
* YAML: YAML Ain't Markup Language

## Specif extension standards
Some types of markup language require to follow conventions to work on ConfigManager:
* XML: Extensible Markup Language
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
* PHP: Hypertext Preprocessor 
 Must have defined a variable _$config_ :

``` php
        <?php
         $config=array(
         'a key' => 'a value'
         );
        ?>
```
