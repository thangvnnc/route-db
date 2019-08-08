<?php

class Router
{
    private $request;

    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;
        if(!in_array(strtoupper($name), $this->supportedHttpMethods))
        {
            $this->invalidMethodHandler();
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        // Danh sách các routes map xử lý
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};

        // Toàn bộ link. VD: /abc?demo=123
        $fullRoute = $this->formatRoute($this->request->requestUri);

        // Cắt lấy giãn lượt dùng map xử lý. VD: /abc
        $formatedRoute = explode('?', $fullRoute)[0];

        // Kiểm tra router /*
        foreach ($methodDictionary as $key => $value)
        {
            // Duyệt route có dấu * cuối cùng
            if ($key[strlen($key) - 1] == "*")
            {
                // Kiểm tra các route /* được định nghĩa
                $routeAny = substr($key, 0, strlen($key) - 2);

                // Là route root
                if ($routeAny === "")
                {
                    $formatedRoute = $key;
                    break;
                }

                // Kiểm tra dấu * ở các route không phải route root
                $contain = strpos($formatedRoute , $routeAny);
                if($contain !== false) {
                    $formatedRoute = $key;
                    break;
                }
            }
        }

        if (!isset($methodDictionary[$formatedRoute]))
        {
            $this->defaultRequestHandler();
            return;
        }

        $method = $methodDictionary[$formatedRoute];
        if(is_null($method))
        {
            $this->defaultRequestHandler();
            return;
        }
        
        echo call_user_func_array($method, array($this->request));

    }

    function __destruct()
    {
        $this->resolve();
    }
}