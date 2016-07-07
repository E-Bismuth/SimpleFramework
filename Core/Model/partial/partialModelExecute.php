<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 09:02
 */
namespace Core\Model\partial;

use Core\Model\src\ModelException;

/** Process the action
 * Class partialModelExecute
 * @package Core\Model\partial
 */
trait partialModelExecute
{


    /** Execute insert
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    private function doInsert(){
        $query = $this->prepare();
        $data = $query->fetch();
        return $data;

    }

    /** Execute update
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    private function doUpdate(){
        $query = $this->prepare();
        $data = $query->fetch();
        return $data;

    }

    /** Execute raw
     * @return mixed
     * @throws \Core\Model\src\ModelException
     */
    private function doRaw(){
        $query = $this->prepare();
        $data = $query->fetchAll();
        return $data;

    }

    /** Execute delete
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    private function doDelete(){
        $query = $this->prepare();
        $data = $query->fetchAll();
        return $data;

    }

    /** Execute Select
     * @return array
     * @throws \Core\Model\src\ModelException
     */
    private function getData(){
        $query = $this->prepare();
        $data = $query->fetchAll();
        return $data;
    }


    /** Routing execution
     * @return array|mixed
     * @throws ModelException
     */
    private function Execute(){
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

}