<?php
// tutti i metodi devono essere indipendenti,cioè devono caricare,analizzare e salvare il file di configurazione se serve.
interface ExtensionManager
{
    public function set($key, $value=false);
    public function add($key, $value=false);
    public function get($key);
    public function asArray();
    public function exist($key);
    public function delete($key);

}

?>