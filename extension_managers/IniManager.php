<?php

class IniManager extends Manager
{
    protected function get_config($key)
    {
        if (!is_array($key))
        {
            $key = array('default', $key);
        }
        $section = parent::get_config($key[0]);
        $value = $section[$key[1]];
        return DataFormat::unserialize($value);

    }
    protected function assign($key, $value, $can_add)
    {
        
        if (is_array($value))
        {
            $sectionName = $key;
            $key = $value[0];
            $value = $value[1];
        }
        else
        {
            $sectionName = 'default';
        }
        $value=DataFormat::serialize($value);
        if (parent::exist_config($sectionName))
        {
            $section = parent::get_config($sectionName);
            $section[$key] = $value;
            parent::assign($sectionName, $section,$can_add);
        }
        else
        {
            parent::assign($sectionName, array($key => $value),$can_add);
        }


    }
    protected function exist_config($key)
    {
        if (is_array($key))
        {
            $sectionName = $key[0];
            $key = $key[1];
        }
        else
        {
            $sectionName = 'default';
        }
        if (parent::exist_config($sectionName))
        {
            $section = parent::get_config($sectionName);
            return isset($section[$key]);
        }
        return false;
    }
    protected function delete_config($key)
    {
        if (is_array($key))
        {
            $sectionName = $key[0];
            $key = $key[1];
        }
        else
        {
            $sectionName = 'default';
        }
        $section = parent::get_config($sectionName);
        unset($section[$key]);
        if(empty($section))
            parent::delete_config($sectionName);
        else
            parent::assign($sectionName, $section,true);
    }
    protected function decodeConfig($content)
    {
        return parse_ini_string($content, true);
    }
    protected function encodeConfig($config)
    {
        return $this->encode_to_ini($config, true);
    }
    private function encode_to_ini($assoc_arr, $has_sections = false)
    {
        $content = "";
        if ($has_sections)
        {
            foreach ($assoc_arr as $key => $elem)
            {
                $content .= "[" . $key . "]\n";
                foreach ($elem as $key2 => $elem2)
                {
                    if (is_array($elem2))
                    {
                        for ($i = 0; $i < count($elem2); $i++)
                        {
                            $content .= $key2 . "[] = \"" . $elem2[$i] . "\"\n";
                        }
                    }
                    else
                        if ($elem2 == "")
                            $content .= $key2 . " = \n";
                        else
                            $content .= $key2 . " = \"" . $elem2 . "\"\n";
                }
            }
        }
        else
        {
            foreach ($assoc_arr as $key => $elem)
            {
                if (is_array($elem))
                {
                    for ($i = 0; $i < count($elem); $i++)
                    {
                        $content .= $key2 . "[] = \"" . $elem[$i] . "\"\n";
                    }
                }
                else
                    if ($elem == "")
                        $content .= $key2 . " = \n";
                    else
                        $content .= $key2 . " = \"" . $elem . "\"\n";
            }
        }

        return $content;
    }


}

?>