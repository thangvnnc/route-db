<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Base/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Base/Request.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Model/M_User.php';

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public static function home (Request $request)
    {
        M_User::createTable();
        T_Customer::createTable();
    }

    public static function index (Request $request)
    {
        self::redirectTo("/public/www/index.html");
    }
}