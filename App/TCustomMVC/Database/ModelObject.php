<?php

// Khai báo define
define("CHAR_SPLIT", '@');
define("PREF_TABLE_NAME", "dbt");
define("PREF_TABLE_NAME_DELIMITE", ":");
define("PREF_TABLE_FIELD", "dbf");
define("PREF_TABLE_FIELD_DELIMITE", ":");
define("PREF_TABLE_FIELD_PRIMARY", "dbpri");
define("PREF_TABLE_FIELD_PRIMARY_DELIMITE", ":");
define("PREF_TABLE_FIELD_PRIMARY_DELIMITE_NAME", ",");

/**
 * Model object quản lý model tương tác với csdl
 */
class ModelObject
{
    private $clazzCall = null;

    /**
     * Tên class cần tương tác csdl
     */
    function __construct($clazzCall)
    {
        $this->clazzCall = $clazzCall;
    }

    public function getSQLCreateTable()
    {
        $arraySql = array();
        $prikeys = $this->getPrimaryKeys();
        $tbName = $this->getTableName();
        $tbFields = $this->getTableFields();
        $tbFieldDefines = $this->getTableFieldDefines();

        $sql = "";
        $sql .= "CREATE TABLE `$tbName`" . " (";
        for ($idx = 0; $idx < count($tbFields); $idx++)
        {
            $tbField = $tbFields[$idx];
            $tbFieldDefine = $tbFieldDefines[$idx];
            $sql .= "`" . $tbField . "` " . $tbFieldDefine;
            if($idx != count($tbFields) - 1)
            {
                $sql .= ", ";
            }
        }
        $sql .= ");";
        array_push($arraySql, $sql);

        $sql = "";
        $sql .= "ALTER TABLE `$tbName` ADD PRIMARY KEY (`";
        for ($idx = 0; $idx < count($prikeys); $idx++)
        {
            $prikey = $prikeys[$idx];
            $sql .= $prikey;
            if($idx != count($prikeys) - 1)
            {
                $sql .= ", ";
            }
        }
        $sql .= "`);";
        array_push($arraySql, $sql);

        $sql = "";
        $sql.= "ALTER TABLE `$tbName` MODIFY `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;";
        array_push($arraySql, $sql);

        return $arraySql;
    }

    /**
     * Hàm lấy tên table của db
     */
    public function getTableName()
    {
        // Lấy comment của class
        foreach ($this->getDocumentClass() as $comment)
        {
            // Kiểm tra lấy tên class
            if (strpos($comment, PREF_TABLE_NAME) !== false)
            {
                $commentSplist = explode(PREF_TABLE_NAME_DELIMITE, $comment);
                $tableName = trim($commentSplist[1]);
                return $tableName;
            }
        }

        return null;
    }

    /**
     * Hàm lấy tên primary table của db
     */
    public function getPrimaryKeys()
    {
        // Kiểm tra lấy danh sách primary key được định nghĩa trên class
        foreach ($this->getDocumentClass() as $comment) 
        {
            if (strpos($comment, PREF_TABLE_FIELD_PRIMARY) !== false)
            {
                $commentSplist = explode(PREF_TABLE_FIELD_PRIMARY, $comment);
                $commentSplist = explode(PREF_TABLE_FIELD_PRIMARY_DELIMITE, $commentSplist[1]);
                $commentSplist = explode(PREF_TABLE_FIELD_PRIMARY_DELIMITE_NAME, $commentSplist[1]);
                $fieldPrimaryNames = array_map("trim" , $commentSplist);
                return $fieldPrimaryNames;
            }
        }   

        return null;
    }

    /**
     * Hàm lấy danh sách tên các field của db
     */
    public function getTableFields()
    {
        $fieldNames = array();

        // Lấy danh sách field định nghĩa csdl
        foreach ($this->getDocumentProperties() as $comment)
        {
            // Kiểm tra lấy đúng tên field
            if (strpos($comment, PREF_TABLE_FIELD) !== false)
            {
                $commentSplist = explode(PREF_TABLE_FIELD, $comment);
                $commentSplist = explode(PREF_TABLE_FIELD_DELIMITE, $commentSplist[1]);
                $fieldName = trim($commentSplist[0]);
                array_push($fieldNames, $fieldName);
            }
        }

        return $fieldNames;
    }

    /**
     * Hàm lấy danh sách tên các field của db
     */
    public function getTableFieldDefines()
    {
        $fieldNames = array();

        // Lấy danh sách field định nghĩa csdl
        foreach ($this->getDocumentProperties() as $comment)
        {
            // Kiểm tra lấy đúng tên field
            if (strpos($comment, PREF_TABLE_FIELD) !== false)
            {
                $commentSplist = explode(PREF_TABLE_FIELD, $comment);
                $commentSplist = explode(PREF_TABLE_FIELD_DELIMITE, $commentSplist[1]);
                $fieldName = trim($commentSplist[1]);
                array_push($fieldNames, $fieldName);
            }
        }

        return $fieldNames;
    }

    /**
     * Hàm lấy comment của class
     */
    private function getDocumentClass()
    {
        $commentAll = array();
        $refClass = new ReflectionClass($this->clazzCall);
        $comment = $refClass->getDocComment();
        $commentContent = substr($comment, 11);
        $commentContent = substr_replace($commentContent, "", -2);
        $commentSplitContents = explode("\n", $commentContent);
        foreach($commentSplitContents as $comment)
        {
            if (strpos($comment, CHAR_SPLIT) !== false) 
            {
                $indexCharacter = stripos($comment, CHAR_SPLIT);
                $lengthCut = strlen($comment) - $indexCharacter;
                $commentDB = substr($comment, $indexCharacter, $lengthCut);
                array_push($commentAll, $commentDB);
            }
        }   
        return $commentAll;
    }
    
    /**
     * Hàm lấy comment của properties
     * Chỉ lấy dòng comment có dấu @
     */
    public function getDocumentProperties()
    {
        $commentAll = array();
        $refClass = new ReflectionClass($this->clazzCall);
        $refClass->getDocComment();

        foreach ($refClass->getProperties() as $refProperty) {
            $comment = $refProperty->getDocComment();
            $commentContent = substr($comment, 11);
            $commentContent = substr_replace($commentContent, "", -2);
            $commentSplitContents = explode("\n", $commentContent);
            foreach($commentSplitContents as $comment)
            {
                if (strpos($comment, CHAR_SPLIT) !== false) 
                {
                    $indexCharacter = stripos($comment, CHAR_SPLIT);
                    $lengthCut = strlen($comment) - $indexCharacter;
                    $commentDB = substr($comment, $indexCharacter, $lengthCut);
                    array_push($commentAll, $commentDB);
                }
            }   
        }

        return $commentAll;
    }
}