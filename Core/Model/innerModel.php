<?php
/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 08/07/2016
 * Time: 10:29
 */

namespace Core\Model;

use Core\Model\src\ModelException;
use Core\Model\lib\Inflect;

abstract class innerModel
{


    /** ----------------------------   General Part  ------------------------------------ */
    /** All the field selected by tables
     * @var array
     */
    protected $selectedKeys = [];

    /**List of field name already use in order to rename if an
     * other one is required with the same name
     * @var array
     */
    protected $existingKeys = [];
    /** All the field on where by tables
     * @var array
     */
    protected $where = [];
    /**Limit SQL
     * @var
     */
    protected $limit;
    /**Contain all the group by request
     * @var
     */
    protected $groupBy = [];
    /**Contain all the order by request
     * @var
     */
    protected $orderBy = [];
    /**All the field that must be bind on sql
     * @var array
     */
    protected $injected = [];
    /** Save the query type that will be executed
     * @var null
     */
    protected $execute = null;
    /**Save the query if the developer whant to build regular expression
     * @var null
     */
    protected $rawQuery = null;
    /** Save the database connection
     * @var
     */
    protected $db;
    
    /** List of Jonction
     * @var array
     */
    protected $Join = [];

    /** Find the name of the table by the Model used
     * @param null $table
     * @return mixed
     */
    protected function getTable($table = null){
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
    protected function saveFields($keys, $table = null){

        if($table == null){
            $table = $this->getTable($table);
        }

        if(!array_key_exists($table,$this->selectedKeys)){
            $this->selectedKeys[$table]=[];
        }
        if(is_array($keys)){
            foreach ($keys AS $key=>$value){
                if(is_array($value)){
                    if(in_array($key,$this->existingKeys)){
                        $key = $table . ucfirst($key);
                    }
                    $this->existingKeys[]=$key;
                    $this->selectedKeys[$table][$key] = $value;
                }
                else{
                    if(in_array($value,$this->existingKeys)){
                        $valueAs = $table . ucfirst($value);
                        $this->selectedKeys[$table][] = $value. ' AS '.$valueAs;
                        $saveValue = $valueAs;
                    }
                    else{
                        $this->selectedKeys[$table][] = $value;
                        $saveValue = $value;
                    }
                    $this->existingKeys[]=$saveValue;
                }
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
    protected function saveLimit($min, $max){
        $this->limit = ['Min'=>$min,'Max'=>$max];
    }

    /**Proceed Group By
     * @param $keys
     * @param null $table
     */
    protected function saveGroupBy($keys, $table = null){
        $table = $this->getTable($table);
        if(is_array($keys)){
            foreach ($keys AS $value){
                $this->groupBy[$table][] = $value;
            }
        }
        else{
            $this->groupBy[$table][] = $keys;
        }
    }

    /**Proceed Order By
     * @param $keys
     * @param null $table
     */
    protected function saveOrderBy($keys, $table = null){
        $table = $this->getTable($table);
        if(is_array($keys)){
            foreach ($keys AS $value){
                if(substr($value,0,1) == '!'){
                    $this->orderBy[$table][] = substr($value,1) . ' DESC';
                }
                else{
                    $this->orderBy[$table][] = $value . ' ASC';
                }
            }
        }
        else{
            if(substr($keys,0,1) == '!'){
                $this->orderBy[$table][] = substr($keys,1) . ' DESC';
            }
            else{
                $this->orderBy[$table][] = $keys . ' ASC';
            }
        }
    }

    /** Proceed Save Where
     * @param $keys
     * @param null $table
     */
    protected function saveWhere($keys, $table = null){

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
    protected function saveInject(array $keys){
        foreach ($keys as $key=>$value){
            $this->injected[$key] = $value;
        }
    }
    /** ----------------------------   Execut Part  ------------------------------------ */
    /** Execute insert
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    protected function doInsert(){
        return $this->prepare();

    }

    /** Execute update
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    protected function doUpdate(){
        return $this->prepare();

    }

    /** Execute raw
     * @return mixed
     * @throws \Core\Model\src\ModelException
     */
    protected function doRaw(){
        return $this->prepare();

    }

    /** Execute delete
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    protected function doDelete(){
        return $this->prepare();

    }

    /** Execute Select
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    protected function getData(){
        $this->execute = 'select';
        return $this->prepare();
    }


    /** Routing execution
     * @return array|mixed
     * @throws ModelException
     */
    protected function Execute(){
        switch ($this->execute){
            case 'update':
                return $this->doUpdate();
                break;
            case 'insert':
                return $this->doInsert();
                break;
            case 'select':
                return $this->getData();
                break;
            case 'delete':
                return $this->doDelete();
                break;
            case 'raw':
                return $this->doRaw();
                break;
            default:
                throw new ModelException('No Action Selected');
                break;
        }
    }

    /** Reinitializing value for further query
     *
     */
    protected function reInit(){
        $this->selectedKeys = [];
        $this->existingKeys = [];
        $this->where = [];
        $this->limit = null;
        $this->groupBy = [];
        $this->orderBy = [];
        $this->injected = [];
        $this->execute = null;
        $this->rawQuery = null;
        $this->Join = [];
    }

    /** ----------------------------   Debug Part  ------------------------------------ */
    
    /** Route method
     * @return array
     * @throws ModelException
     */
    protected function debugQuery(){
        switch ($this->execute){
            case 'update':
                $query = $this->updateQueryBuilder();
                break;
            case 'insert':
                $query = $this->insertQueryBuilder();
                break;
            case 'select':
                $query = $this->selectQueryBuilder();
                break;
            case 'delete':
                $query = $this->deleteQueryBuilder();
                break;
            case 'raw':
                if($this->rawQuery == null)
                    throw new ModelException('The Query cannot be null');
                $query = $this->rawQuery;
                break;
            default:
                throw new ModelException('No Action Selected');
                break;
        }
        $prepare = $this->debugMatchPrepared($query);
        $executQuery = $this->debugQueryReplace($query);

        return array(
            'Query'=>array(
                'notPrepared'=>$query,
                'Prepared'=>$executQuery,
            ),
            'Injected'=>array(
                'All'=>$this->injected,
                'Count'=>count($this->injected)
            ),
            'Prepared'=>array(
                'All'=>$prepare,
                'Count'=>count($prepare)
            ),
            'Error'=>$this->debugError($prepare)
        );
    }

    /** Replace the injected vars into the query in order to see the request like it will be send
     * @param $query
     * @return mixed
     */
    protected function debugQueryReplace($query){
        $code = [];
        $rpl = [];
        foreach ($this->injected AS $key=>$value){
            $code[] = ':'.$key;
            $rpl[] = '"'.$value.'"';

        }
        return str_replace($code,$rpl,$query);

    }

    /** looking for vars that must be injected on the sql
     * @param $query
     * @return mixed
     */
    protected function debugMatchPrepared($query){
        preg_match_all("/:(\w+)\s/", $query, $output_array);
        return $output_array[1];
    }

    /** Send to the view the Debug array with potential errors
     * @param $prepare
     * @return array
     */
    protected function debugError($prepare){
        $prepareArr = $injectedArr = false;
        $prepareDiff = array_diff($prepare,array_keys($this->injected));
        if(count($prepareDiff)==0){
            $prepareArr = 'All prepare vars are injected';
        }
        else{
            foreach ($prepareDiff AS $diff){
                $prepareArr[]="Prepare Var : $diff is not injected";
            }
        }
        $injectedDiff = array_diff(array_keys($this->injected),$prepare);
        if(count($injectedDiff)==0){
            $injectedArr = 'All injected vars are used';
        }
        else{
            foreach ($injectedDiff AS $diff){
                $injectedArr[]="Injected Var : $diff is not prepared on SQL";
            }
        }
        $countErrors =
            ((is_array($injectedArr)) ? count($injectedArr) : 0)
            +
            ((is_array($prepareArr)) ? count($prepareArr) : 0)
        ;
        return array(
            'Numbers of Errors'=>$countErrors,
            'Prepared vars'=>$prepareArr,
            'Injected vars'=>$injectedArr,
        );
    }
    
    /** ----------------------------   Junction Part  ------------------------------------ */

    /** Process the join
     * @param $direction
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    protected function makeJoin($direction, Model $class, callable $callable = null){
        $thisTable = $this->getTable();
        $table = $class->getTable();
        $thisRelation = $this->Relations();
        if((!empty($thisRelation)) && (array_key_exists($table,$thisRelation))){
            if(!array_key_exists($table,$this->Join)){
                $this->Join[$table]="$direction JOIN $table ON $thisTable.".$thisRelation[$table][0]."=$table.".$thisRelation[$table][1];
            }

            if($callable != null){
                $calledModel = call_user_func($callable, $class );

                if(is_array($calledModel->selectedKeys)){
                    foreach($calledModel->selectedKeys AS $key=>$values){
                        $this->fields($values,$key);
                    }
                }
                if(is_array($calledModel->Join)){
                    foreach($calledModel->Join AS $key=>$values){
                        $this->Join[$key] = $values;
                    }
                }
                if(is_array($calledModel->where)){
                    foreach($calledModel->where AS $key=>$values){
                        $this->where[$key] = $values;
                    }
                }
                if(is_array($calledModel->injected)){
                    foreach($calledModel->injected AS $key=>$values){
                        $this->injected[$key] = $values;
                    }
                }
            }
        }
        else{
            throw new ModelException('Trying to join 2 tables where no relation exist');
        }

        return $this;
    }

    /** route the junction to the good method
     * @param $direction (left/right/inner) default left
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    protected function juncture($direction, Model $class, callable $callable = null){
        switch ($direction){
            case 'left':
                $this->leftJoin($class, $callable);
                break;
            case 'right':
                $this->rightJoin($class, $callable);
                break;
            case 'inner':
                $this->innerJoin($class, $callable);
                break;
            default:
                $this->leftJoin($class, $callable);
                break;
        }
        return $this;
    }

    /** route the junction to the good method
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    protected function leftJoin(Model $class, callable $callable = null){
        return $this->makeJoin('LEFT',$class,$callable);
    }

    /** route the junction to the good method
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    protected function rightJoin(Model $class, callable $callable = null){
        return $this->makeJoin('RIGHT',$class,$callable);
    }

    /** route the junction to the good method
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    protected function innerJoin(Model $class, callable $callable = null){
        return $this->makeJoin('INNER',$class,$callable);
    }

    /** ----------------------------   Query Builder Part  ------------------------------------ */
    /** Execute all query
     * @return string
     * @throws ModelException
     */
    protected function prepare(){
        switch ($this->execute){
            case 'update':
                $query = $this->updateQueryBuilder();
                break;
            case 'insert':
                $query = $this->insertQueryBuilder();
                break;
            case 'select':
                $query = $this->selectQueryBuilder();
                break;
            case 'delete':
                $query = $this->deleteQueryBuilder();
                break;
            case 'raw':
                if($this->rawQuery == null)
                    throw new ModelException('The Query cannot be null');
                $query = $this->rawQuery;
                break;
            default:
                throw new ModelException('No Action Selected');
                break;
        }

        $query = $this->db->prepare($query);

        if(is_array($this->injected)){
            $execute = $query->execute($this->injected);
        }
        else{
            $execute = $query->execute();
        }

        switch ($this->execute){
            case 'update':
            case 'delete':
                $return = $execute;
                break;
            case 'insert':
                $return = $this->db->lastInsertId();
                break;
            default:
                $return = $query->fetchAll();
                break;
        }

        return $return;
    }


    /** Build Insert Query
     * @return string
     * @throws ModelException
     */
    protected function insertQueryBuilder(){
        $table = $this->getTable();
        if(!empty($this->selectedKeys)){
            foreach ($this->selectedKeys AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrVar[]=$key;
                }
            }
            $fields = implode(',',$arrVar);
            $values = implode(' ,:',$arrVar);
            $values = ':'.$values.' ';
        }
        else{
            throw new ModelException('No fields selected');
        }
        $query = "
                    INSERT INTO $table ($fields) 
                    VALUES ($values)
        ";
        return $query;
    }

    /** Build Delete Query
     * @return string
     * @throws ModelException
     */
    protected function deleteQueryBuilder(){
        $table = $this->getTable();
        $where = '';
        if(!empty($this->where)){

            foreach ($this->where AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrWhere[]= "(" . $tables.'.'.$key . ")";
                }
            }

            $where = 'WHERE '. implode(' AND ',$arrWhere);
        }
        elseif($this->needWhereOnDelete){
            throw new ModelException('Can\'t execute this DELETE request without define a where');
        }
        $query = "
                    DELETE FROM $table 
                    $where
        ";
        return $query;

    }

    /** Build Update Query
     * @return string
     * @throws ModelException
     */
    protected function updateQueryBuilder(){
        $table = $this->getTable();
        $set = '';
        if(!empty($this->selectedKeys)){
            foreach ($this->selectedKeys AS $tables=>$keys){
                foreach ($keys AS $key){
                    $set .= $key . '=:'.$key.' ,';
                }
            }
            $set = substr($set,0,-1);
        }
        else{
            throw new ModelException('No fields selected');
        }
        $where = '';
        if(!empty($this->where)){

            foreach ($this->where AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrWhere[]= "(" . $tables.'.'.$key . ")";
                }
            }

            $where = 'WHERE '. implode(' AND ',$arrWhere);
        }
        elseif($this->needWhereOnUpdate){
            throw new ModelException('Can\'t execute this UPDATE request without define a where');
        }
        $query = "
                    UPDATE $table 
                    SET $set
                    $where
        ";
        return $query;
    }

    /** Build Select Query
     * @return string
     */
    protected function selectQueryBuilder(){
        $table = $this->getTable();
        $var = '*';
        $leftJoin = '';
        $where = '';
        $limit = '';
        $groupBy = '';
        $orderBy = '';

        if(is_array($this->limit)) {
            $limit = "LIMIT " . $this->limit['Min'] . ", " . $this->limit['Max'];
        }
        if(!empty($this->where)){
            foreach ($this->where AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrWhere[]= "(" . $tables.'.'.$key . ")";
                }
            }

            $where = 'WHERE '. implode(' AND ',$arrWhere);
        }
        if(!empty($this->groupBy)){
            foreach ($this->groupBy AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrGroupBy[]=$tables.'.'.$key;
                }
            }

            $groupBy = 'GROUP BY '. implode(',',$arrGroupBy);
        }
        if(!empty($this->orderBy)){
            foreach ($this->orderBy AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrOrderBy[]=$tables.'.'.$key;
                }
            }

            $orderBy = 'ORDER BY '. implode(',',$arrOrderBy);
        }
        if(!empty($this->selectedKeys)){
            foreach ($this->selectedKeys AS $tables=>$keys){
                foreach ($keys AS $key=>$value){
                    if(is_array($value)){
                        if((!array_key_exists('Action',$value)) || (!array_key_exists('Value',$value))){
                            throw new ModelException('You have to provide "Action" and "Value" in order to define a special field');
                        }
                        else{
                            $string = '';
                            foreach ($value['Value'] AS $k=>$v){
                                if(preg_match("/[a-zA-Z-_0-9]+/", $v)){
                                    $string .= $tables.'.'.$v.',';
                                }
                                else{
                                    $string .= '"'.$v.'",';
                                }
                            }
                            $arrVar[] = $value['Action']. '(' .substr($string,0,-1) . ') AS '.$key;
                        }
                    }
                    else{
                        $arrVar[]=$tables.'.'.$value;
                    }
                }
            }
            $var = implode(',',$arrVar);

        }
        if(!empty($this->Join)){
            foreach ($this->Join AS $tables=>$keys){
                $leftJoin .= $keys.' ';
            }
        }


        $query = "
                    SELECT $var 
                    FROM $table 
                    $leftJoin 
                    $where
                    $groupBy
                    $orderBy
                    $limit
        ";

        return $query;
    }

}