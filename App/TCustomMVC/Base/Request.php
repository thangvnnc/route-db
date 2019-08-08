<?php

require_once 'IRequest.php';

class Request implements IRequest
{
    function __construct()
    {
        foreach($_SERVER as $key => $value)
        {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);
        foreach($matches[0] as $match)
        {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }
        return $result;
    }

    public function getBody()
    {
        header("Content-Type: application/json");

        if($this->requestMethod === "GET")
        {
            return $_GET;
        }

        if ($this->requestMethod === "POST")
        {
            $body = array();
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            // Kiểm tra request url post data nếu có
            if(count($body) > 0)
            {
                return $body;
            };
            
            // Kiểm tra body json
            $bodyJson = file_get_contents('php://input');
            return json_decode($bodyJson, false, 512, JSON_UNESCAPED_UNICODE);
        }
    }
}