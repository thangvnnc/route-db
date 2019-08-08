<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/TCustomMVC/Database/Model.php';

class ModelBase extends Model
{
    /**
     * @dbf id: BIGINT(20) UNSIGNED NOT NULL
     */
    public $id;

    /**
     * @dbf created_at: TIMESTAMP  DEFAULT current_timestamp()
     */
    public $created_at;

    /**
     * @dbf created_by: BIGINT
     */
    public $created_by;

    /**
     * @dbf updated_at: TIMESTAMP  DEFAULT current_timestamp() ON UPDATE current_timestamp()
     */
    public $updated_at;

    /**
     * @dbf updated_by: BIGINT
     */
    public $updated_by;
}