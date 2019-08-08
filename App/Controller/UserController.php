<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Base/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Base/Request.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Model/M_User.php';

class UserController extends Controller
{
    public function __construct() {
    }

    public static function login (Request $request)
    {
        $params = $request->getBody();        
        $username = $params->username;
        $password = $params->password;
        $passwordEnCode = hash("sha512", $password);
        $users = M_User::getByMutilFieldsAndLike(["username"=>$username, "password"=>$passwordEnCode]);
        
        $result = [];
        $user = null;
        $code = -1;
        if (count($users) > 0)
        {
            $code = 0;
            $user = $users[0];
            goto TheEnd;
        }
        
        TheEnd:    
        $result['code'] = $code;
        $result['content'] = $user;
        self::jsons($result); 
    }

    public static function add (Request $request)
    {
        $params = $request->getBody();
        $username = $params['username'];
        $password = $params['password'];
        $passwordEnCode = hash("sha512", $password);
        $user = M_User::getByMutilFieldsAndLike(["username"=>$username, "password"=>$passwordEnCode]);
        self::jsons($user);
    }
}