<?php

require_once 'ModelBase.php';

/**
 * Khai báo class quản lý database model
 * @dbt Table_Name: t_customer
 * @dbpri Table_Primary: id
 */
class T_Customer extends ModelBase
{
    /**
     * @dbf code: VARCHAR(200)
     */
    public $code;

    /**
     * @dbf name: VARCHAR(200)
     */
    public $name;

    /**
     * @dbf phone: VARCHAR(20)
     */
    public $phone;

    /**
     * @dbf address: VARCHAR(4096)
     */
    public $address;

    /**
     * @dbf note: VARCHAR(4096)
     */
    public $note;

    /**
     * @dbf qdodai: DOUBLE DEFAULT NULL
     */
    public $qdodai;

    /**
     * @dbf qlung: DOUBLE
     */
    public $qlung;

    /**
     * @dbf qmong: DOUBLE
     */
    public $qmong;

    /**
     * @dbf qday: DOUBLE
     */
    public $qday;

    /**
     * @dbf qdui: DOUBLE
     */
    public $qdui;

    /**
     * @dbf adodai: DOUBLE
     */
    public $adodai;

    /**
     * @dbf avai: DOUBLE
     */
    public $avai;

    /**
     * @dbf atay: DOUBLE
     */
    public $atay;

    /**
     * @dbf anguc: DOUBLE
     */
    public $anguc;

    /**
     * @dbf aeo: DOUBLE
     */
    public $aeo;

    /**
     * @dbf among: DOUBLE
     */
    public $among;

    /**
     * @dbf aco: DOUBLE
     */
    public $aco;

    function __construct()
    {
    }
}