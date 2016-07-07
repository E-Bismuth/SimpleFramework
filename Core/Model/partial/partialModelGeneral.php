<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 09:05
 */
namespace Core\Model\partial;



use Core\Model\lib\Inflect;

/** Call all private property
 * Class partialModelGeneral
 * @package Core\Model\partial
 */
trait partialModelGeneral
{
    use partialModelJunction,partialModelQueryBuilder,partialModelDebug,partialModelExecute;

    /** All the field selected by tables
     * @var array
     */
    private $selectedKeys = [];
    /** All the field on where by tables
     * @var array
     */
    private $where = [];
    /**Limit SQL
     * @var
     */
    private $limit;
    /**All the field that must be bind on sql
     * @var array
     */
    private $injected = [];
    /** Save the query type that will be executed
     * @var null
     */
    private $execute = null;
    /**Save the query if the developer whant to build regular expression
     * @var null
     */
    private $rawQuery = null;
    /** Save the database connection
     * @var
     */
    private $db;

    /** Find the name of the table by the Model used
     * @param null $table
     * @return mixed
     */
    private function getTable($table = null){
        if(isset($this->table)){
            $callClass =  $this->table;
        }
        else{
            if($table == null){
                $exploded = explode('\\',get_called_class());
                $table = $exploded[(count($exploded)-1)];
                $callClass =  strtolower(Inflect::pluralize($table));
            }
            else{
                $exploded = explode('\\',$table);
                $table = $exploded[(count($exploded)-1)];
                $callClass =  strtolower($table);
            }
        }
        return $callClass;

    }

    /** Proceed Save fileds
     * @param $keys
     * @param null $table
     */
    private function saveFields($keys, $table = null){

        $table = $this->getTable($table);

        if(!array_key_exists($table,$this->selectedKeys)){
            $this->selectedKeys[$table]=[];
        }
        if(is_array($keys)){
            foreach ($keys AS $value){
                $this->selectedKeys[$table][] = $value;
            }
        }
        else{
            $this->selectedKeys[$table][] = $keys;
        }
        
    }

    /** Proceed Save Limit
     * @param $min
     * @param $max
     */
    private function saveLimit($min, $max){
        $this->limit = ['Min'=>$min,'Max'=>$max];
    }

    /** Proceed Save Where
     * @param $keys
     * @param null $table
     */
    private function saveWhere($keys, $table = null){

        $table = $this->getTable($table);

        if(!array_key_exists($table,$this->where)){
            $this->where[$table]=[];
        }

        if(is_array($keys)){
            foreach ($keys AS $value){
                $this->where[$table][] = $value;
            }
        }
        else{
            $this->where[$table][] = $keys;
        }
        
    }

    /** Proceed Save injection
     * @param array $keys
     */
    private function saveInject(array $keys){
        foreach ($keys as $key=>$value){
            $this->injected[$key] = $value;
        }
    }

}