<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 08:59
 */
namespace Core\Model\partial;
use Core\Model\src\ModelException;

/** Build the Sql query and execute
 * Class partialModelQueryBuilder
 * @package Core\Model\partial
 */
trait partialModelQueryBuilder
{

    /** Execute all query
     * @return string
     * @throws ModelException
     */
    private function prepare(){
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
            $query->execute($this->injected);
        }
        else{
            $query->execute();
        }

        return $query;
    }


    /** Build Insert Query
     * @return string
     * @throws ModelException
     */
    private function insertQueryBuilder(){
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
    private function deleteQueryBuilder(){
        $table = $this->getTable();
        $where = '';
        if(!empty($this->where)){

            foreach ($this->where AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrWhere[]=$tables.'.'.$key;
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
    private function updateQueryBuilder(){
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
                    $arrWhere[]=$tables.'.'.$key;
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
    private function selectQueryBuilder(){
        $table = $this->getTable();
        $var = '*';
        $leftJoin = '';
        $where = '';
        $limit = '';

        if(is_array($this->limit)) {
            $limit = "LIMIT " . $this->limit['Min'] . ", " . $this->limit['Max'];
        }
        if(!empty($this->where)){
            foreach ($this->where AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrWhere[]=$tables.'.'.$key;
                }
            }

            $where = 'WHERE '. implode(' AND ',$arrWhere);
        }
        if(!empty($this->selectedKeys)){
            foreach ($this->selectedKeys AS $tables=>$keys){
                foreach ($keys AS $key){
                    $arrVar[]=$tables.'.'.$key;
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
                    $limit
        ";

        return $query;
    }
}