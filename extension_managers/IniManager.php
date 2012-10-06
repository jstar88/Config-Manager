<?php

class IniManager extends Manager
{

    protected function decodeConfig($content)
    {
        return parse_ini_string($content,true);
    }
    protected function encodeConfig($config)
    {
        return $this->encode_to_ini($config,true);
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