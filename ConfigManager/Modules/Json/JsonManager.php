<?php

namespace ConfigManager\Modules\Json;

use Exceptions\JsonException as JsonException;

class JsonManager extends FileManager
{
    protected function decodeConfig($content)
    {
        $content = json_decode($content, true);
        $error = '';
        switch (json_last_error())
        {
            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON';
                break;
        }
        if (!empty($error))
        {
            throw new JsonException($error);
        }
        return $content;
    }
    protected function encodeConfig($config)
    {
        return json_encode($config);
    }

}