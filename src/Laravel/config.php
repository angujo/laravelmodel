<?php
/**
 * @author       bangujo ON 2021-04-17 09:11
 * @project      laravelmodel
 * @ide          PhpStorm
 * @originalFile config.php
 */

/*
 * This is the configuration page for custom setup.
 * Worth noting that this library relies on directory levels for namespace
 * Accessible models will be directly placed on "base_dir"
 * Any subsequent extension or supporting directories will be set deeper into "base_dir"
 */

use Illuminate\Database\Eloquent\Model;

return [
    /*
     * Set the model connection defined
     */
    'define_connection'       => false,
    /*
     * Set the date format for DB, serialization in array or json
     */
    'date_format'             => null,
    /*
     * Enable to add @date on each Base Model every time it is run
     * If set to False, @date will be set on first instance
     */
    'date_base'               => false,
    /*
     * Separate Models based on the database/schema
     * Recommended for cross database/schema system
     */
    'db_directories'          => false,
    /*
     * Set Column names as CONST within the models
     * Allows column names to be called as User::EMAIL for email.
     */
    'constant_column_names'   => false,
    /*
     * When [constant_column_names] is enable,
     * set the prefix to use.
     * e.g. prefix = 'COL_' then column email becomes User::COL_EMAIL
     */
    'constant_column_prefix'  => null,
    /*
     * Column names that are used for soft delete.
     * If different naming across tables, add them here.
     * NOTE: No two names should be on same table.
     */
    'soft_delete_columns'     => ['deleted_at'],
    /*
     * Column names to mark as create columns
     * If different naming across tables, add them here.
     * NOTE: No two names should be on same table.
     */
    'create_columns'          => ['created_at', 'created'],
    /*
     * Columns to be used as update
     * If different naming across tables, add them here.
     * NOTE: No two names should be on same table.
     */
    'update_columns'          => ['updated_at', 'updated'],

    /*
     * Tables to be excluded from model generation
     */
    'excluded_tables'         => ['migrations', 'password_resets', 'oauth_access_tokens', 'oauth_auth_codes', 'oauth_clients', 'oauth_personal_access_clients', 'oauth_refresh_tokens',],
    /*
     *Tables to be run ONLY
     * The reset will be excluded
     */
    'only_tables'             => [],
    /*
     * While naming relations you need to select the order in which the names will be picked.
     * Ordering should start with most preferred.
     * Can only contain any of three entries; [column],[table],[constraint]
     * [column]
     * The column name will be used to identify the relation name
     * @see column_relation_pattern, relation_remove_prx and relation_remove_sfx
     * [table]
     * The target table name will be used.
     * [constraint]
     * The foreign key constrain name will be used.
     *
     * The checking and order preference is based on usage.
     * E.g. for column [manager_user_id] referencing table [users] with constraint [managers_user_id_foreign],
     * will be processed by checking if relation [manager_user] has been created by another relation,
     * if used will check if [user] has been created,
     * if used will check if [managers_user_id_foreign] has been used.
     * If all options used, will skip the relation
     */
    'relation_naming'         => ['column', 'table', 'constraint'],
    /*
     * Column naming pattern to auto identify relations for Foreign Keys
     * This will check column names and set relation name based off them.
     * Percentage similarity will be set to 70%
     * Set to empty or null so as not to use.
     * E.g 1: if = [{relation_name}_id] or = [fk_{relation_name}] or = [fk_{relation_name}_id] when column name is user_manager_id then relation name will be userManager
     * i.e. use {relation_name} to indicate which part of column to be used as relation name.
     */
    'column_relation_pattern' => '{relation_name}_id',
    /*
     * Enable creation of relations based on column name.
     * This allows the using only [column_relation_pattern] on the column name to create a relation.
     * To work, the {relation_name} should referenced a table name in singular/plural format.
     * Foreign keys will not be used for further checks
     */
    'column_auto_relate'      => true,
    /*
     * Class to be used for each and every generated model
     * Ensure it is or extends \Illuminate\Database\Eloquent\Model::class
     */
    'model_class'             => Model::class,
    /*
     * Directory path to put the models
     */
    'base_dir'                => app_path('Models'),
    /*
     * Enable composite keys in laravel
     */
    'composite_keys'          => true,
    /*
     * Name of class to be used in customizing Eloquent to accommodate composite keys.
     */
    'eloquent_extension_name' => 'EloquentExtension',
    /*
     * Create abstract classes to act as BASE Class for the tables
     */
    'base_abstract'           => true,
    /*
     * Namespace for the models
     */
    'namespace'               => 'App\Models',
    /*
     * If you wish to rename pivot tables in belongsToMany relation,
     * Set regex for naming pattern below. The name should be in teh table's comment
     * E.g if set as '{pivot:(\w+)}', a table with comment "This is a table comment for {pivot:role_users}" will rename pivot to role_users instead of default pivot
     */
    'pivot_name_regex'    => '{pivot:(\w+)}',
    /*
     * @link https://laravel.com/docs/eloquent-relationships#polymorphic-relations
     * Add polymorphic tables as well.
     * To set this up column naming should be as described in laravel
     * On the "_type" list all tables to be referenced on the column's comments (Optional)
     * The primaryKey of the referenced table will be considered by default, otherwise,
     * the column name should be indicated if different from primaryKey
     * If the morph is not one to many, an indicator should be added i.e. 1-1=One to One, 1-0=One to Many(default), 0-0=Many to Many
     *
     * Syntax 1: table_name1,table_name2,.... table_nameN -- primary column used and one to many assumed
     * Syntax 2: table_name1:column_name1,table_name2:column_name2,... table_nameN:column_nameN -- primary column not used but one to many assumed
     * Syntax 3: table_name1:1-1,table_name2:1,... table_nameN:1-1, -- One to One reference with primaryKey used
     * Syntax 4: table_name1:column_name1:1-1,table_name2:column_name2:1-1,... table_nameN:column_nameN:1-1 -- primary column not used and one to one used
     * Syntax 5: table_name1:column_name1:0-0,table_name2:column_name2:0-0,... table_nameN:column_nameN:0-0 -- primary column not used and many to many used
     *
     * For Many to Many Polymorph,
     * The "_id" column's comment should contain third table's reference.
     * Otherwise, the table should have three columns where the third will be assumed as the end point table.
     *
     * In addition to above, you can add schema/database name, enclosed in a bracket, to the table name i.e. (db_name)table_name1,...
     * This is in case the referenced table belongs to a different schema
     *
     * ONLY POSSIBLE FOR NON-COMPOSITE PRI-KEYs
     * All listing should be separated by a comma
     */
    'polymorph'               => true,
    /*
     * @see https://laravel.com/docs/eloquent-mutators#attribute-casting
     * Type Casting for properties and database values.
     * You can cast using a column name or data type.
     * To cast data type e.g. tinyint(1) to be boolean,
     * start with "type:" followed by the type i.e. "type:tinyint(1)"=>'boolean'
     */
    'type_casts'              => ['type:tinyint(1)' => 'boolean', '%_json' => 'array', '%_array' => 'array', 'is_%' => 'boolean',
                                  'type:date'       => 'date:Y-m-d', 'type:datetime' => 'datetime:Y-m-d H:i:s'],
    /*
     * Overwrite files during generation.
     * Will be overwritten by the -f(--force) option in artisan cli
     * Need to be explicitly called on console to be implemented,
     * otherwise the value below is ignored
     */
    'overwrite_models'        => false,
    /*
     * Fully import classes even on same namespace (FQDN)
     */
    'full_namespace_import'   => false,
    /*
     * If handling multiple schema/DBs and there's need to separate schema configurations,
     * Use below with options above to be replaced.
     * An example has been commented out.
     * Schemas ignored by default are: mysql,sys,information_schema,master,template
     */
    'schemas'                 => [
        /*
          'information_schema' => [
            'excluded_tables' => ['migrations', 'password_resets'],
            'only_tables'     => [],
        ],
        */
    ],
];