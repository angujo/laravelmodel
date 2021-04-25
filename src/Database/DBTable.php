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
 * @property boolean                     $is_pivot
 * @property boolean                     $has_pivot
 * @property string                      $foreign_column_name
 * @property string                      $class_name
 * @property string                      $relation_name_singular
 * @property string                      $relation_name_plural
 * @property string                      $fqdn
 * @property string                      $class_name_key
 * @property string                      $pivot_table_name
 * @property string                      $pivot_end_table_name
 * @property string                      $comment
 * @property DBTable|null                $pivot_table
 * @property DBTable|null                $pivot_end_table
 * @property string[]|array              $pivot_table_names
 * @property DBTable[]|array             $pivot_tables
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

    protected function pivot_tables()
    {
        return $this->is_pivot ? array_combine($this->_props['pivot_table_names'], array_map(function($name){ return $this->db->getTable($name); }, $this->_props['pivot_table_names'])) : [];
    }

    protected function pivot_table()
    {
        return $this->has_pivot ? $this->db->getTable($this->pivot_table_name) : null;
    }

    protected function pivot_end_table()
    {
        return $this->has_pivot ? $this->db->getTable($this->pivot_end_table_name) : null;
    }

    /**
     * @param string $table_name
     *
     * @return DBColumn|null
     */
    public function pivotedColumn(string $table_name)
    {
        if (!$this->is_pivot || !($table = $this->pivot_tables[$table_name] ?? null)) {
            return null;
        }
        /** @var DBForeignConstraint|null $fk */
        if ($fk = \Arr::first($this->foreign_keys, function(DBForeignConstraint $constraint) use ($table_name){ return 0 === strcasecmp($constraint->referenced_table_name, $table_name); })) {
            return $fk->column;
        }
        return \Arr::first($this->columns, function(DBColumn $column) use ($table){ return 0 === strcasecmp($column->name, $table->foreign_column_name); });
    }

    public function setIsPivot(array $combinations)
    {
        $this->_props['is_pivot']          = true;
        $this->_props['pivot_table_names'] = $combinations;
        return $this;
    }

    public function setEndPivot(string $pivot_table_name, string $pivot_end_table_name)
    {
        $this->_props['has_pivot']            = true;
        $this->_props['pivot_table_name']     = $pivot_table_name;
        $this->_props['pivot_end_table_name'] = $pivot_end_table_name;
        return $this;
    }
}