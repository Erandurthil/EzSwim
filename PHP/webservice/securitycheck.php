<?php
//  securitycheck.php
require_once("mysqlwrapper.php");
require_once("errorhandling.php");

function checkUserAndRight($db, $username, $pwd, $rightname)
{
    $username = $db->mysqlconn->real_escape_string($username);
    $pwd = $db->mysqlconn->real_escape_string($pwd);
    $rightname = $db->mysqlconn->real_escape_string($rightname);
    
    $resu = $db->arrqueryc("SELECT validateUser(\"$username\", \"$pwd\") as res;", function($message){
        handleError($message);
    });
    
    $resr = $db->arrqueryc("SELECT hasRightN(\"$username\", \"$rightname\") as res;", function($message)
    {
        handleError($message);
    });
    
    if($resr[0]['res'] && $resu[0]['res'])
    {
        return true;
    }
    else 
    {
        return false;
    }
}
?>