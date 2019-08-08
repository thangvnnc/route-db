<?php

require_once 'ModelBase.php';

/**
 * Khai báo class quản lý database model
 * @dbt Table_Name: m_user
 * @dbpri Table_Primary: id
 */
class M_User extends ModelBase
{
    /**
     * @dbf username: varchar(200)
     */
    public $username;

    /**
     * @dbf password: varchar(128)
     */
    public $password;

    /**
     * @dbf expired: timestamp  DEFAULT current_timestamp()
     */
    public $expired;

    function __construct()
    {
    }
}