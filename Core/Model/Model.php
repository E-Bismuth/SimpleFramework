<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 01/07/2016
 * Time: 13:37
 */

namespace Core\Model;

use Core\Model\src\ModelException;

/** List of all the possibilities of the model
 * INFORMATION!! better use method debug before execute your SQL
 * Class Model
 * @package Core\Model
 */
abstract class Model extends innerModel
{


    /** ----------------------------   Model parameters  ------------------------------------ */



    /** Block the possibility to make an update without where
     * if true = blocked
     * if false = not blocked (Dangerous)
     * @var bool
     */
    protected $needWhereOnUpdate = true;
    /** Block the possibility to make a delete without where
     * if true = blocked
     * if false = not blocked (Dangerous)
     * @var bool
     */
    protected $needWhereOnDelete = true;

    /**Define table
     * if null = name of table will be the plurialize of the model in lowercase
     * if defined on child class = name of table will be the defined name
     * @var null
     */
    protected $table = null;

    /**
     * Model constructor.
     * @param $db
     * @throws ModelException
     */
    public function __construct(SPDO $db = null){
        if($db == null)
            throw new ModelException('No database Selected');

        $this->db = $db;
    }

    /**Method that must be declare on child class that give the relation that can be made
     * between tables
     * exemple : array('users'=>array('users_id','id'))
     * @return array
     */
    abstract protected function Relations();

    /** ----------------------------   Query Builder  ------------------------------------ */

    /** Field or list of Fields
     * on Select = Fields that will be selected
     * on Update = Fields that will be updated
     * on Insert = Fileds that will be inserted
     * @param $keys string|array
     * @param null $table
     * @return $this
     */
    public function fields($keys, $table = null){
        $this->saveFields($keys, $table);
        return $this;
    }

    /** Where or list of Where
     * @param $keys string|array
     * @param null $table
     * @return $this
     */
    public function where($keys, $table = null){
        $this->saveWhere($keys, $table);
        return $this;
    }

    /** Limit of the SQL result
     * @param $min
     * @param $max
     * @return $this
     */
    public function limit($min, $max){
        $this->saveLimit($min, $max);
        return $this;
    }

    /**Adding a group by to the sql
     * @param $keys
     * @param null $table
     * @return $this
     */
    public function groupBy($keys, $table = null){
        $this->saveGroupBy($keys,$table);
        return $this;
    }

    /**Adding an order by to the sql
     * in order to make a DESC just add a '!' before the field name
     * @param $keys
     * @param null $table
     * @return $this
     */
    public function orderBy($keys, $table = null){
        $this->saveOrderBy($keys,$table);
        return $this;
    }

    /** Build a join,
     * ATENTION!!! = on Callable will return an object of type Model
     * @param $direction string = (left/right/inner) default left
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    protected function join($direction, Model $class, callable $callable = null){
        return $this->juncture($direction, $class, $callable);
    }

    /** List of injected params on
     * ATENTION!!! = the key must have the save name as the code given
     * Exemple = SELECT * WHERE id = :id AND date < :date, inject will be : ['id'=>5,'date'=>'NOW()']
     * @param associative array $keys
     * @return $this
     */
    public function inject(array $keys){
        $this->saveInject($keys);
        return $this;
    }

    /** ----------------------------   Query Action  ------------------------------------ */

    /** Action will be a select query
     * @return $this
     */
    public function select(){
        $this->execute = 'select';
        return $this;
    }

    /** Action will be a insert query
     * @return $this
     */
    public function insert(){
        $this->execute = 'insert';
        return $this;
    }

    /** Action will be a update query
     * @return $this
     */
    public function update(){
        $this->execute = 'update';
        return $this;
    }

    /** Action will be a delete query
     * @return $this
     */
    public function delete(){
        $this->execute = 'delete';
        return $this;
    }

    /** Can pass a query without using the method fields/where/limit/join
     * Can use method inject in order to bind values
     * @param $query
     * @return $this
     */
    public function raw($query){
        $this->execute = 'raw';
        $this->rawQuery = $query;
        return $this;
    }


    /** ----------------------------   Action Result  ------------------------------------ */

    /**Return datas like fetchAll
     * @return array
     */
    public function get(){
        $return = $this->getData();
        $this->reInit();
        return $return->fetchAll();
    }

    /**Return the first Element
     * @return array
     */
    public function first(){
        $return = $this->limit(0,1)->getData();
        $this->reInit();
        return $return->fetch();
    }

    /**Return datas as Json
     * @return string
     */
    public function json(){
        $return = $this->getData();
        $this->reInit();
        return json_encode($return->fetchAll());
    }

    /**Return the PDO Statement as object
     * @return string
     */
    public function pdoStatement(){
        $return = $this->getData();
        $this->reInit();
        return $return;
    }


    /**Execute
     * ATENTION!!! = Require for insert/update/delete/raw
     * Could be use in place of method get on select
     * @return array|mixed
     */
    public function exec(){
        $return = $this->Execute();
        $this->reInit();
        return $return;
    }


    /**Debug the query
     * @return array
     * @throws ModelException
     */
    public function debug(){
        $return = $this->debugQuery();
        $this->reInit();
        return $return;
    }

}