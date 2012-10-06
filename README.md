This is a configurations managers with automatic extension detection.

To manage your data, you can use the methods defined by this interfaces:

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
