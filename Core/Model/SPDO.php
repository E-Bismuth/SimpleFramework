<?php

/**
 * Created by PhpStorm.
 * User: Eytan Bismuth
 * Date: 06/07/2016
 * Time: 11:28
 */
namespace Core\Model;

use Core\Model\src\ModelException;
use PDO;

abstract class SPDO
{

    /**
     * Instances of the PDO class Master,Replication Default
     *
     * @var PDO
     * @access private
     */
    private $PDOInstance = null;
    private $PDOMasterInstance = null;
    private $PDOReplicationInstance = null;

    /**
     * Constant: DB Hostnames
     *
     * @var string
     */
    protected $SQL_MASTER_HOST = false;
    protected $SQL_REPL_HOST = false;

    /**
     * Constant: DB usernames
     *
     * @var string
     */
    protected $SQL_MASTER_USER = false;
    protected $SQL_REPL_USER = false;

    /**
     * Constant: DB passwwords
     *
     * @var string
     */
    protected $SQL_MASTER_PASS = false;
    protected $SQL_REPL_PASS = false;

    /**
     * Constant: DB Name
     *
     * @var string
     */
    protected $SQL_DTB = false;


    /**
     * Master Construct PDO
     *
     * @see PDO::__construct()
     * @param void
     * @access private
     */
    private function ConstructMaster()
    {
        $this->PDOMasterInstance = new PDO('mysql:dbname='.$this->SQL_DTB.';host='.$this->SQL_MASTER_HOST,$this->SQL_MASTER_USER ,$this->SQL_MASTER_PASS,
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_PERSISTENT => true
            )
        );
        $this->PDOMasterInstance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Replication Construct PDO
     *
     * @see PDO::__construct()
     * @param void
     * @access private
     */
    private function ConstructReplication()
    {
        $this->PDOReplicationInstance = new PDO('mysql:dbname='.$this->SQL_DTB.';host='.$this->SQL_REPL_HOST,$this->SQL_REPL_USER ,$this->SQL_REPL_PASS,
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_PERSISTENT => true
            )
        );
        $this->PDOReplicationInstance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }


    /**
     * List of Query that write on DB
     *
     * @param void
     * @access private
     * @return array()
     */
    private function NeedMaster()
    {
        $Action = array(
            "insert",
            "insert into",
            "update",
            "drop",
            "alter",
            "delete",
            "truncate",
            "grant",
            "create"
        );
        return $Action;
    }


    /**
     * Check the Query if it must be read on replica or write on master
     *
     * @param string $query The SQL query
     * @access private
     * @return True or False (True if need write to DB)
     */
    private function CheckQuery($query)
    {
        $chr = array();
        foreach($this->NeedMaster() as $needle) {
            $res = stripos($query, $needle, 0);
            if ($res !== false){$chr[$needle] = $res;break;}
        }
        if(empty($chr)) {return false;} 	//no one of the forbidden action are detected
        else            {return true;}		// at least one of the forbidden character found
    }

    /**
     * Launch the query test and buid the PDO object
     *
     * @param string $query The SQL query
     * @access private
     */
    private function CheckBefore($query)
    {
        if((!$this->SQL_MASTER_HOST) && (!$this->SQL_REPL_HOST)){
            throw new ModelException('No configuration decalred');
        }
        else if(!$this->SQL_REPL_HOST){
            if(is_null($this->PDOMasterInstance))
            {
                $this->ConstructMaster();
            }
            $this->PDOInstance = $this->PDOMasterInstance;
        }
        else if(!$this->SQL_MASTER_HOST){
            if(is_null($this->PDOMasterInstance))
            {
                $this->ConstructReplication();
            }
            $this->PDOInstance = $this->PDOReplicationInstance;
        }
        else{
            if($this->CheckQuery($query)===true){
                if(is_null($this->PDOMasterInstance))
                {
                    $this->ConstructMaster();
                }
                $this->PDOInstance = $this->PDOMasterInstance;
            }
            else{
                if(is_null($this->PDOReplicationInstance))
                {
                    $this->ConstructReplication();
                }
                $this->PDOInstance = $this->PDOReplicationInstance;
            }
        }

    }


    /**
     * Execute a query SQL with PDO
     *
     * @param string $query The SQL query
     * @return PDOStatement Return the object PDOStatement
     */
    public function query($query)
    {
        $this->CheckBefore($query);
        return $this->PDOInstance->query($query);
    }
    public function prepare($query,$driver_options  = [])
    {
        $this->CheckBefore($query);
        return $this->PDOInstance->prepare($query);
    }
    public function lastInsertId($name = NULL)
    {
        return $this->PDOInstance->lastInsertId();
    }

}