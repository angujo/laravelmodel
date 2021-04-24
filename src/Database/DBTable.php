<?php
/**
 * @author       bangujo ON 2021-04-12 14:07
 * @project      laravelmodel
 * @ide          PhpStorm
 * @originalFile DBTable.php
 */

namespace Angujo\LaravelModel\Database;


use Angujo\LaravelModel\Config;
use Angujo\LaravelModel\Database\Traits\BaseDBClass;
use Angujo\LaravelModel\Database\Traits\HasComment;
use Angujo\LaravelModel\Database\Traits\HasName;

/**
 * Class DBTable
 *
 * @package Angujo\LaravelModel\Database
 *
 * @property string                      $name
 * @property string                      $foreign_column_name
 * @property string                      $class_name
 * @property string                      $relation_name_singular
 * @property string                      $relation_name_plural
 * @property string                      $fqdn
 * @property string                      $class_name_key
 * @property string                      $comment
 * @property DBColumn[]|array            $columns
 * @property DBColumn[]|array            $primary_columns
 * @property DBForeignConstraint[]|array $foreign_keys
 * @property DBForeignConstraint[]|array $referencing_foreign_keys
 * @property DBColumn|null               $primary_column
 */
class DBTable extends BaseDBClass
{
    /** @var DatabaseSchema */
    private $db;

    public function __construct(DatabaseSchema $database, $values = [])
    {
        $this->db = $database;
        parent::__construct($values);
    }

    protected function relation_name_singular()
    {
        return function_name_single($this->name);
    }

    protected function relation_name_plural()
    {
        return function_name_plural($this->name);
    }

    protected function fqdn()
    {
        return Config::namespace().'\\'.class_name($this->name);
    }

    protected function class_name()
    {
        return class_name($this->name);
    }

    protected function class_name_key()
    {
        return $this->class_name.'::class';
    }

    protected function foreign_column_name()
    {
        return strtolower(\Str::singular(\Str::snake($this->name)).'_'.Config::LARAVEL_PRIMARY_KEY);
    }

    /**
     * @return DBColumn[]|array
     */
    protected function columns()
    {
        return $this->db->getColumn($this->name);
    }

    protected function foreign_keys()
    {
        return $this->db->getForeignKey($this->name);
    }

    protected function referencing_foreign_keys()
    {
        return $this->db->getReferencingForeignKeys($this->name);
    }

    protected function primary_columns()
    {
        return array_filter($this->columns, function(DBColumn $col){ return $col->is_primary; });
    }

    protected function primary_column()
    {
        return 1 < count($this->primary_columns) ? null : \Arr::first($this->primary_columns);
    }
}