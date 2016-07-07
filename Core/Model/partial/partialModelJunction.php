<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 08:55
 */
namespace Core\Model\partial;


use Core\Model\Model;

/** Take care of all the process in order to make a join 
 * Class partialModelJunction
 * @package Core\Model\partial
 */
trait partialModelJunction
{
    /** List of Jonction
     * @var array
     */
    private $Join = [];

    /** Process the join
     * @param $direction
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    private function makeJoin($direction, Model $class, callable $callable = null){
        $thisTable = $this->getTable();
        $table = $class->getTable();
        $thisRelation = $this->Relations();
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
        }

        return $this;
    }

    /** route the junction to the good method
     * @param $direction (left/right/inner) default left
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    private function juncture($direction, Model $class, callable $callable = null){
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
    private function leftJoin(Model $class, callable $callable = null){
        return $this->makeJoin('LEFT',$class,$callable);
    }

    /** route the junction to the good method
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    private function rightJoin(Model $class, callable $callable = null){
        return $this->makeJoin('RIGHT',$class,$callable);
    }

    /** route the junction to the good method
     * @param Model $class
     * @param callable|null $callable
     * @return $this
     */
    private function innerJoin(Model $class, callable $callable = null){
        return $this->makeJoin('INNER',$class,$callable);
    }
}