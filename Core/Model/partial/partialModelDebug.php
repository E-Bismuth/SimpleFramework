<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 09:00
 */

namespace Core\Model\partial;


use Core\Model\src\ModelException;

/** Debuging sql
 * Class partialModelDebug
 * @package Core\Model\partial
 */
trait partialModelDebug
{
    /** Route method
     * @return array
     * @throws ModelException
     */
    private function debugQuery(){
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
    private function debugQueryReplace($query){
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
    private function debugMatchPrepared($query){
        preg_match_all("/:(\w+)\s/", $query, $output_array);
        return $output_array[1];
    }

    /** Send to the view the Debug array with potential errors
     * @param $prepare
     * @return array
     */
    private function debugError($prepare){
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

}