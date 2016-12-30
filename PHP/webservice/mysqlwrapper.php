<?php
//  mysqlwrapper.php
require_once("config.php");
require_once("errorhandling.php");

function createConnection()
{
    $db = new DBContext(CONN_SERVER, CONN_DB, CONN_USER, CONN_PWD);
    
    $db->connectc(function($message){
        handleError($message); 
    });
    
    return $db;
}

class DBContext
{
    private $m_server;
    private $m_database;
    private $m_user;
    private $m_password;
    private $m_autocommit;
    private $m_autorollback;
    
    public $mysqlconn;
    
    function __construct($server, $database, $user, $password)
    {
        $this->m_server = $server;
        $this->m_database = $database;
        $this->m_user = $user;
        $this->m_password = $password;
        $this->m_autocommit = true;
        $this->m_autorollback = true;
    }
    
    function __destruct()
    {
        $this->disconnect();
    }
    
    public function connect()
    {
		try
		{
			// Create connection
			$this->mysqlconn = new mysqli($this->m_server, $this->m_user, $this->m_password, $this->m_database);

			// Check connection
			if ($this->mysqlconn->connect_error)
			{
				return false;
			}
			else
			{ 
				$this->mysqlconn->autocommit(false);
				return true;
			}
		}
		catch(Exception $e)
		{
			handleError($e->getMessage());
		}
    }
    
    public function connectc($errorcallback)
    {
		try
		{
			// Create connection
			$this->mysqlconn = new mysqli($this->m_server, $this->m_user, $this->m_password, $this->m_database);

			// Check connection
			if ($this->mysqlconn->connect_error)
			{
				$errorcallback("MySql Connection Error: $this->mysqlconn->connect_error");
				return false;
			}
			else
			{ 
				$this->mysqlconn->autocommit(false);
				return true;
			}
		}
		catch(Exception $e)
		{
			handleError($e->getMessage());
		}
    }
    
    public function disconnect()
    {
        if($this->mysqlconn)
            return $this->mysqlconn->close();
    }
    
    public function setTransactHandling($autocommit, $autorollback)
    {
        $this->m_autocommit = $autocommit;
        $this->m_autorollback = $autorollback;
    }
    
    public function commit()
    {
        if($this->m_autocommit === false && $this->mysqlconn)
            $this->mysqlconn->commit();
        
    }
    
    public function rollback()
    {
        if($this->m_autorollback === false && $this->mysqlconn)
            $this->mysqlconn->rollback();
    }
    
    public function objquery($querystring)
    {
        if($this->mysqlconn)
        {
            $result = $this->mysqlconn->query($querystring);
            if($result) //success
            {
                if($result === true)        //successful command
                {
                    if($this->m_autocommit === true)
                        $this->mysqlconn->commit();
                    return true;
                }
                else                        //successful query
                {
                    $results = array();
                    while($obj = $result->fetch_object())
                    {
                        array_push($results, $obj);
                    }
                    return $results;                    
                }
            }
            else        //error
            {
                if($this->m_autorollback === true)
                    $this->mysqlconn->rollback();
                return false;
            }            
        }
        else 
        {  
            return false;
        }
    }
    
    public function arrquery($querystring)
    {
        if($this->mysqlconn)
        {
            $result = $this->mysqlconn->query($querystring);
            if($result) //success
            {
                if($result === true)        //successful command
                {
                    if($this->m_autocommit === true)
                        $this->mysqlconn->commit();
                    return true;
                }
                else                        //successful query
                {
                    $results = array();
                    while($row = $result->fetch_assoc())
                    {
                        array_push($results, $row);
                    }
                    return $results;                    
                }
            }
            else        //error
            {
                if($this->m_autorollback === true)
                    $this->mysqlconn->rollback();
                return false;
            }            
        }
        else 
        {  
            return false;
        }
    }
    
    public function arrqueryc($querystring, $errorcallback)
    {
        if($this->mysqlconn)
        {
             $result = $this->mysqlconn->query($querystring);
             if($result) //success
            {
                if($result === true)        //successful command
                {
                    if($this->m_autocommit === true)
                        $this->mysqlconn->commit();
                    return true;
                }
                else                        //successful query
                {
                    $results = array();
                    while($row = $result->fetch_assoc())
                    {
                        array_push($results, $row);
                    }
                    return $results;   
                }
            }
            else        //error
            {
				$tmperr = $this->mysqlconn->error;
				$tmperrno = $this->mysqlconn->errno;
                if($this->m_autorollback === true)
                    $this->mysqlconn->rollback();
                $errorcallback("MYSQLERROR:\nCODE:\n$tmperrno\nMESSAGE:\n$tmperr");
                return false;
            }
        }
        else 
        {
            $errorcallback("ERROR: Invalid MySqlConnection");
            return false;
        }
    }
    
    public function objqueryc($querystring, $errorcallback)
    {
        if($this->mysqlconn)
        {
            
            $result = $this->mysqlconn->query($querystring);
             if($result) //success
            {
                if($result === true)        //successful command
                {
                    if($this->m_autocommit === true)
                        $this->mysqlconn->commit();
                    return true;
                }
                else                        //successful query
                {
                    $results = array();
                    while($obj = $result->fetch_object())
                    {
                        array_push($results, $obj);
                    }
                    return $results;       
                }
            }
            else        //error
            {
				$tmperr = $this->mysqlconn->error;
				$tmperrno = $this->mysqlconn->errno;
                if($this->m_autorollback === true)
                    $this->mysqlconn->rollback();
                $errorcallback("MYSQLERROR:\nCODE:\n$tmperrno\nMESSAGE:\n$tmperr");
                return false;
            }
        }
        else 
        {
            $errorcallback("ERROR: Invalid MySqlConnection");
            return false;
        }
    }
}
?>