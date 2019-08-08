<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Base/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Base/Request.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Model/T_Customer.php';

class CustomerController extends Controller
{
    public function __construct()
    {
    }

    public static function getAlls (Request $request)
    {
        $customerPage = T_Customer::getAlls();
        $result['code'] = 0;
        $result['content'] = $customerPage;
        self::jsons($result);
    }

    public static function getCustomerPagination (Request $request)
    {
        $params = $request->getBody();
        $customerPage = T_Customer::getByFindWithPaginations($params);
        $result['code'] = 0;
        $result['content'] = $customerPage;
        self::jsons($result);
    }

    public static function findCustomer (Request $request)
    {
        $params = $request->getBody();
        $data = get_object_vars($params->data);
        $customerPage = T_Customer::getByMutilFieldsAndLike($data);

        $message = [];
        $message['code'] = 0;
        $message['content'] = $customerPage;   
        self::jsons($message);
    }

    public static function add (Request $request)
    {
        $params = $request->getBody();
        $data = $params->data;
        $customer               = new T_Customer();
        $customer->code         = isset($data->code)       ? $data->code  : "";
        $customer->name         = isset($data->name)       ? $data->name : "";
        $customer->phone        = isset($data->phone)      ? $data->phone : "";
        $customer->address      = isset($data->address)    ? $data->address : "";
        $customer->note         = isset($data->note)       ? $data->note : "";
        $customer->qdodai       = isset($data->qdodai)     ? $data->qdodai : null;
        $customer->qlung        = isset($data->qlung)      ? $data->qlung : null;
        $customer->qmong        = isset($data->qmong)      ? $data->qmong : null;
        $customer->qday         = isset($data->qday)       ? $data->qday : null;
        $customer->qdui         = isset($data->qdui)       ? $data->qdui : null;
        $customer->adodai       = isset($data->adodai)     ? $data->adodai : null;
        $customer->avai         = isset($data->avai)       ? $data->avai : null;
        $customer->atay         = isset($data->atay)       ? $data->atay : null;
        $customer->anguc        = isset($data->anguc)      ? $data->anguc : null;
        $customer->aeo          = isset($data->aeo)        ? $data->aeo : null;
        $customer->among        = isset($data->among)      ? $data->among : null;
        $customer->aco          = isset($data->aco)        ? $data->aco : null;
        $customer->created_by   = isset($data->created_by) ? $data->created_by : 0;
        $customer->updated_by   = isset($data->updated_by) ? $data->updated_by : 0;
        $result = T_Customer::insert($customer);

    TheEnd:
        $message = [];
        if ($result === true)
        {
            $message['code'] = 0;
        }
        else
        {
            $message['code'] = -1;
        }

        self::jsons($message);
    }
    
    public static function edit (Request $request)
    {
        $result = false;

        $params = $request->getBody();
        $id = $params->id;
        $data = $params->data;

        $customers = T_Customer::getByMutilFieldsAndOperator(["id" => $id]);
        if(count($customers) <= 0)
        {
            goto TheEnd;
        }

        $customer               = T_Customer::getByMutilFieldsAndOperator(["id" => $id])[0];
        $customer->code         = isset($data->code)       ? $data->code : $customer->code;
        $customer->name         = isset($data->name)       ? $data->name : $customer->name;
        $customer->phone        = isset($data->phone)      ? $data->phone : $customer->phone;
        $customer->address      = isset($data->address)    ? $data->address : $customer->address;
        $customer->note         = isset($data->note)       ? $data->note : $customer->note;
        $customer->qdodai       = isset($data->qdodai)     ? $data->qdodai : $customer->qdodai;
        $customer->qlung        = isset($data->qlung)      ? $data->qlung : $customer->qlung;
        $customer->qmong        = isset($data->qmong)      ? $data->qmong : $customer->qmong;
        $customer->qday         = isset($data->qday)       ? $data->qday : $customer->qday;
        $customer->qdui         = isset($data->qdui)       ? $data->qdui : $customer->qdui;
        $customer->adodai       = isset($data->adodai)     ? $data->adodai : $customer->adodai;
        $customer->avai         = isset($data->avai)       ? $data->avai : $customer->avai;
        $customer->atay         = isset($data->atay)       ? $data->atay : $customer->atay;
        $customer->anguc        = isset($data->anguc)      ? $data->anguc : $customer->anguc;
        $customer->aeo          = isset($data->aeo)        ? $data->aeo : $customer->aeo;
        $customer->among        = isset($data->among)      ? $data->among : $customer->among;
        $customer->aco          = isset($data->aco)        ? $data->aco : $customer->aco;
        $customer->created_by   = isset($data->created_by) ? $data->created_by : $customer->created_by;
        $customer->created_at   = isset($data->created_at) ? $data->created_at : $customer->created_at;
        $customer->updated_by   = isset($data->updated_by) ? $data->updated_by : $customer->updated_by;
        $customer->updated_at   = null;

        $result = T_Customer::update(["id" => $id], $customer);

    TheEnd:
        $message = [];
        if ($result === true)
        {
            $message['code'] = 0;
        }
        else
        {
            $message['code'] = -1;
        }

        self::jsons($message);
    }
    
    public static function remove (Request $request)
    {
        $params = $request->getBody();
        $id = $params->id;
        $result = T_Customer::delete(["id" => $id]);

    TheEnd:
        $message = [];
        if ($result === true)
        {
            $message['code'] = 0;
        }
        else
        {
            $message['code'] = -1;
        }

        self::jsons($message);
    }
}