<?php

require_once 'ModelObject.php';
require_once 'Define.php';

/**
 * Class xử lý db model
 */
class Model
{
    // Biến lưu giữ message khi truy vấn lỗi
    public static $errCode    = 0;
    public static $errMessage = null;

    /**
     * Hàm chuyển đổi dữ liệu trong db thành class 
     * @param $data: Dữ liệu dưới dạng array_map
     * @param $clazz: Đối tượng cần parser
     * @return : Model sau khi parser
     */
    public static function parserModel(array $data, $clazz)
    {
        $model = new $clazz();

        foreach($data as $key => $val)
        {
            if(property_exists($model, $key))
            {
                $model->$key =  $val;
            }
        }

        return $model;
    }

    /**
     * Hàm tạo csdl
     */
    public static function createTable()
    {
        $isSucces = false;
        $clazz = get_called_class();
        $mo = new ModelObject($clazz);
        $sqls = $mo->getSQLCreateTable();
        // Lấy kết nối db
        $db = self::getConnection();
        $db->begin_transaction();

        if($db === null)
        {
            return null;
        }

        foreach ($sqls as $sql)
        {
            // Kiểm tra chuẩn bị truy vấn
            $stmt = $db->prepare($sql);
            if ($stmt === false)
            {
                goto TheEnd;
            }
            $stmt->execute();
        }
        $isSucces = true;

    TheEnd:

        if ($isSucces === true)
        {
            $db->commit();
        }
        else
        {
            $db->rollback();
        }

        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }

        return $isSucces;
    }

    /**
     * Hàm thêm dữ liệu db
     * @param $model: Dữ liệu cần insert
     */
    public static function insert($model)
    {
        $result = false;

        // Lấy kết nối db
        $db = self::getConnection();
        if($db === null)
        {
            return null;
        }

        $clazz = get_called_class();
        $modelObject = new ModelObject($clazz);
        $tableName = $modelObject->getTableName();
        $fields = $modelObject->getTableFields();

        // Tạo câu truy vấn
        $sql = "INSERT INTO $tableName" . " ";

        // Xử lý tạo câu truy vấn sql
        $columnName = "(";
        $valueName = "VALUE(";
        $numberColumn = count($fields);
        $sections = "";
        $arrParams = array();
        foreach($fields as $field)
        {
            array_push($arrParams, $model->$field);
            $columnName .= $field . " ";
            $valueName .= "?" . " ";
            $numberColumn -= 1;
            $sections .= "s";
            if ($numberColumn === 0)
            {
                $columnName .= ")" . " ";
                $valueName .= ")" . " ";
            }
            else 
            {
                $columnName .= "," . " ";
                $valueName .= "," . " ";
            }
        }

        $sql .= $columnName . $valueName;

        // Kiểm tra chuẩn bị truy vấn
        $stmt = $db->prepare($sql);
        if ($stmt === false)
        {
            goto TheEnd;
        }

        // Thực thi truy vấn lấy dữ liệu
        $stmt->bind_param($sections, ...$arrParams);
        $stmt->execute();  
         
        // Kiểm tra kết quả thực thi truy vấn
        if($stmt->affected_rows === 1)
        {
            $result = true;
        }
        
    TheEnd:
        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }
        return $result;
    }

    /**
     * Hàm cập nhật dữ liệu db
     * @param $keys: Key so sánh update dữ liệu
     * @param $model: Dữ liệu cần update
     */
    public static function update($keys, $model)
    {
        $result = false;

        // Lấy kết nối db
        $db = self::getConnection();
        if($db === null)
        {
            return null;
        }

        $clazz = get_called_class();
        $modelObject = new ModelObject($clazz);
        $tableName = $modelObject->getTableName();
        $fields = $modelObject->getTableFields();

        // Tạo câu truy vấn
        $sql = "UPDATE $tableName SET" . " ";

        // Xử lý tạo câu truy vấn sql
        $sqlSetValue = "";
        $numberColumn = count($fields);
        $sections = "";
        $arrParams = array();
        foreach($fields as $field)
        {
            array_push($arrParams, $model->$field);
            $sqlSetValue .= $field . " " . " = ?" . " ";
            $numberColumn -= 1;
            $sections .= "s";
            if ($numberColumn !== 0)
            {
                $sqlSetValue .= "," . " ";
            }
        }

        $whereCondition = "WHERE" . " ";

        $numberKeys = count($keys);
        foreach($keys as $key => $value)
        {
            $numberKeys -= 1;
            $whereCondition .= "$key = $value" . " ";
            if ($numberKeys !== 0)
            {
                $whereCondition .= "AND" . " ";
            }
        }

        $sql .= $sqlSetValue . $whereCondition;

        // Kiểm tra chuẩn bị truy vấn
        $stmt = $db->prepare($sql);
        if ($stmt === false)
        {
            goto TheEnd;
        }

        // Thực thi truy vấn lấy dữ liệu
        $stmt->bind_param($sections, ...$arrParams);
        $stmt->execute();

        // Kiểm tra kết quả thực thi truy vấn
        // if($stmt->affected_rows === 1)
        // {
        //     $result = true;
        // }

        $result = true;

        TheEnd:
        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }
        return $result;
    }

    /**
     * Hàm xóa dữ liệu db
     * @param $keys: Key so sánh update dữ liệu
     */
    public static function delete($keys)
    {
        $result = false;

        // Lấy kết nối db
        $db = self::getConnection();
        if($db === null)
        {
            return null;
        }

        $clazz = get_called_class();
        $modelObject = new ModelObject($clazz);
        $tableName = $modelObject->getTableName();

        // Tạo câu truy vấn
        $sql = "DELETE FROM `$tableName` ";

        $whereCondition = "WHERE" . " ";

        $numberKeys = count($keys);
        foreach($keys as $key => $value)
        {
            $numberKeys -= 1;
            $whereCondition .= "$key = $value" . " ";
            if ($numberKeys !== 0)
            {
                $whereCondition .= "AND" . " ";
            }
        }

        $sql .= $whereCondition;

        // Kiểm tra chuẩn bị truy vấn
        $stmt = $db->prepare($sql);
        if ($stmt === false)
        {
            goto TheEnd;
        }

        $stmt->execute();

        // Kiểm tra kết quả thực thi truy vấn
        if($stmt->affected_rows === 1)
        {
            $result = true;
        }

    TheEnd:
        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }
        return $result;
    }

    /**
     * Hàm lấy danh sách theo điều kiện or
     * @param $keys: Điều kiện truy vấn db
     * @return : Danh sách truy vấn db
     */
    public static function getByMutilFieldsAndOperator($keys)
    {
        return self::getByMutilFields($keys, true, false);
    }

    /**
     * Hàm lấy danh sách theo điều kiện or
     * @param $keys: Điều kiện truy vấn db
     * @return : Danh sách truy vấn db
     */
    public static function getByMutilFieldsOrOperator($keys)
    {
        return self::getByMutilFields($keys, false, false);
    }

    /**
     * Hàm lấy danh sách theo điều kiện or
     * @param $keys: Điều kiện truy vấn db
     * @return : Danh sách truy vấn db
     */
    public static function getByMutilFieldsAndLike($keys)
    {
        return self::getByMutilFields($keys, true, true);
    }

    /**
     * Hàm lấy danh sách theo điều kiện or
     * @param $keys: Điều kiện truy vấn db
     * @return : Danh sách truy vấn db
     */
    public static function getByMutilFieldsOrLike($keys)
    {
        return self::getByMutilFields($keys, false, true);
    }

    /**
     * Hàm lấy kết nối db
     */
    private static function getConnection()
    {
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PWD, DB_NAME, DB_PORT);
        if ($db !== false)
        {
            $db->set_charset("utf8");
            return $db;
        }
        self::$errCode = 2002;
        self::$errMessage = "Error connect database!";
        return null;
    }

    /**
     * Hàm lấy danh sách nhiều field theo điều kiện where
     * @param $keys: Điều kiện truy vấn db
     * @param $isAnd: true : Điều kiện and, false: Điều kiện or
     * @param $isLike: true : Điều kiện tương đối and, false: Điều kiện tương đối or
     * @return null|array
     */
    private static function getByMutilFields($keys, $isAnd, $isLike)
    {
        $results = array();

        // Lấy kết nối db
        $db = self::getConnection();
        if($db === null)
        {
            return null;
        }

        $oparatorWhere = "AND";
        if ($isAnd === false)
        {
            $oparatorWhere = "OR";
        }

        // Lấy thông tin class get
        $clazzCall = get_called_class();
        $modelObject = new ModelObject($clazzCall);
        $tableName = $modelObject->getTableName();
        
        // Tạo câu truy vấn
        $sql = "SELECT * FROM $tableName" . " ";
        $sql = $sql . "WHERE" . " ";

        // Tạo điều kiện where and
        $numberKeys = count($keys);
        $arrParams = array();
        $sections = "";

        foreach($keys as $key => $value)
        {
            array_push($arrParams, $value);
            $sql = $sql .  $key . " ";
            if($isLike !== true)
            {
                $sql .= " = ?" . " ";
            }
            else
            {
                $sql .= " like concat(concat('%',?),'%')" . " ";
            }
            $numberKeys -= 1;
            if ($numberKeys !== 0)
            {
                $sql .= $oparatorWhere . " ";
            }
            $sections .= "s";
        }

        $sql .= " LIMIT 0, 100 ";

        $rsModel = null;

        // Kiểm tra chuẩn bị truy vấn
        $stmt = $db->prepare($sql);
        if ($stmt === false)
        {
            goto TheEnd;
        }
        
        // Thực thi truy vấn lấy dữ liệu
        $stmt->bind_param($sections, ...$arrParams);
        $stmt->execute();

        // Parser dữ liệu từ db
        $rows = $stmt->get_result();
        while ($row = $rows->fetch_assoc())
        {
            $rsModel = self::parserModel($row, $clazzCall);
            array_push($results, $rsModel);
        }
        $stmt->free_result();

    TheEnd:
        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }
        return $results;
    }

    /**
     * Hàm lấy tất cả dữ liệu
     */
    public static function getAlls()
    {
        $results = array();

        // Lấy kết nối db
        $db = self::getConnection();
        if($db === null)
        {
            return null;
        }

        // Lấy thông tin class get
        $clazzCall = get_called_class();
        $modelObject = new ModelObject($clazzCall);
        $tableName = $modelObject->getTableName();

        // Tạo câu truy vấn
        $sql = "SELECT * FROM $tableName";

        $rsModel = null;

        // Kiểm tra chuẩn bị truy vấn
        $stmt = $db->prepare($sql);
        if ($stmt === false)
        {
            goto TheEnd;
        }

        // Thực thi truy vấn lấy dữ liệu
        $stmt->execute();

        // Parser dữ liệu từ db
        $rows = $stmt->get_result();
        while ($row = $rows->fetch_assoc())
        {
            $rsModel = self::parserModel($row, $clazzCall);
            array_push($results, $rsModel);
        }
        $stmt->free_result();

        TheEnd:
        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }
        return $results;
    }

    /**
     * Hàm lấy danh sách nhiều field theo điều kiện where
     * @param $params: Dữ liệu phân trang
     * @return null|array
     */
    public static function getByFindWithPaginations($params)
    {
        $query = isset($params->query)? $params->query : "";
        $pageNumber = isset($params->pageNumber)? $params->pageNumber : 0;
        $numberItemPage = isset($params->numberItemPage)? $params->numberItemPage : 50;
        $isLike = true;

        // Lấy kết nối db
        $db = self::getConnection();
        if($db === null)
        {
            return null;
        }

        // Lấy thông tin class get
        $clazzCall = get_called_class();
        $modelObject = new ModelObject($clazzCall);
        $tableName = $modelObject->getTableName();
        
        // Tạo câu truy vấn
        $sql = "SELECT * FROM $tableName" . " ";
        $sql = $sql . "WHERE" . " ";

        $clazz = get_called_class();
        $mo = new ModelObject($clazz);
        $tbFields = $mo->getTableFields();
        $keys = [];
        foreach($tbFields as $tbField)
        {
            $keys[$tbField] = $query;
        }        

        // Tạo điều kiện where and
        $numberKeys = count($keys);
        $arrParams = array();
        $sections = "";

        $oparatorWhere = "OR";

        foreach($keys as $key => $value)
        {
            array_push($arrParams, $value);
            $sql = $sql .  $key . " ";
            if($isLike !== true)
            {
                $sql .= " = ?" . " ";
            }
            else
            {
                $sql .= " like concat(concat('%',?),'%')" . " ";
            }
            $numberKeys -= 1;
            if ($numberKeys !== 0)
            {
                $sql = $sql . $oparatorWhere . " ";
            }
            $sections .= "s";
        }
        
        $cursor = $pageNumber * $numberItemPage;
        $sql .= " ORDER BY `updated_at` DESC ";
        $sql .= " LIMIT $cursor,$numberItemPage";
        $rsModel = null;

        // Kiểm tra chuẩn bị truy vấn
        $stmt = $db->prepare($sql);
        if ($stmt === false)
        {
            goto TheEnd;
        }
        
        $results = array();
        $items = array();

        // Thực thi truy vấn lấy dữ liệu
        $stmt->bind_param($sections, ...$arrParams);
        $stmt->execute();

        // Parser dữ liệu từ db
        $rows = $stmt->get_result();
        while ($row = $rows->fetch_assoc())
        {
            $rsModel = self::parserModel($row, $clazzCall);
            array_push($items, $rsModel);
        }
        $stmt->free_result();
        
    TheEnd:
        // Kiểm tra đóng kết nối
        if ($stmt)
        {
            $stmt->close();
        }
        if ($db)
        {
            mysqli_close($db);
        }

        $linkUrl = $_SERVER['REDIRECT_URL'];
        $results['pageNumber'] = $pageNumber;
        $results['numberItemPage'] = $numberItemPage;
        $results['items'] = $items;
        $pageNext = $pageNumber + 1;
        $results['next'] = $linkUrl."?query=$query&pageNumber=$pageNext&numberItemPage=$numberItemPage";
        $pagePrevious = $pageNumber - 1;
        if($pagePrevious < 0)
        {
            $results['previous'] = null;
        }
        else
        {
            $results['previous'] = $linkUrl."?query=$query&pageNumber=$pagePrevious&numberItemPage=$numberItemPage";
        }

        return $results;
    }
}