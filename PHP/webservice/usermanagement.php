<?php
//  usermanagement.php
require_once("mysqlwrapper.php");
require_once("errorhandling.php");
class UserManager
{
    private $m_context;
    
    public function __construct($context)
    {
        $this->m_context = $context;
    }
    
    public function hasRight($userid, $right)
    {
        
    }
	
	public function getUser($userid)
	{
		$userid = $this->m_context->mysqlconn->real_escape_string($userid);
		
		$result = $this->m_context->objqueryc("SELECT * FROM USERS WHERE id = $userid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) == 1)
		{
            echo("<data>");
			echo("<user>\n");
			echo("<id>" . $result[0]->id . "</id>\n");
			echo("<username>" . htmlspecialchars($result[0]->username, ENT_XML1, 'UTF-8') . "</username>\n");
			echo("<prename>" . htmlspecialchars($result[0]->prename, ENT_XML1, 'UTF-8') . "</prename>\n");
			echo("<lastname>" . htmlspecialchars($result[0]->lastname, ENT_XML1, 'UTF-8') . "</lastname>\n");
			echo("</user>");
            echo("</data>");
		}
		else
		{
			handleError("User not found.");
		}
	}
    
    public function addUser($username, $prename, $lastname, $password)
    {
        $username = $this->m_context->mysqlconn->real_escape_string($username);
		$prename = $this->m_context->mysqlconn->real_escape_string($prename);
		$lastname = $this->m_context->mysqlconn->real_escape_string($lastname);
		$password = $this->m_context->mysqlconn->real_escape_string($password);
		
		$res = $this->m_context->arrqueryc("SELECT add_user(\"$username\", \"$prename\", \"$lastname\", \"$password\") as res;", function($message){
			handleError($message);
		});
		
		if(sizeof($res) == 1)
		{
			if($res[0]['res'])
			{
				echo("<message>User was added successfully</message>;");
			}
			else
			{
				handleError("Username \"$username\" already exists.");
			}
		}
		else
		{
			handleError("Query result invalid.");
		}
    }
    
    public function listUsers()
    {
        $result = $this->m_context->objqueryc("SELECT * FROM Users;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<users>\n");
				foreach($result as $row)
				{
					echo("<user>\n");
						echo("<id>" . $row->id . "</id>\n");
						echo("<username>" . htmlspecialchars($row->username, ENT_XML1, 'UTF-8') . "</username>\n");
						echo("<prename>" . htmlspecialchars($row->prename, ENT_XML1, 'UTF-8') . "</prename>\n");
						echo("<lastname>" . htmlspecialchars($row->lastname, ENT_XML1, 'UTF-8') . "</lastname>\n");
					echo("</user>\n");
				}			
			echo("</users>");
		}
		else
		{
			echo("<users></users>");
		}
    }
    
    public function filterUsers($filter)
    {
        
    }
    
    public function changePassword($userid, $password)
    {
        $userid = $this->m_context->mysqlconn->real_escape_string($userid);
		$password = $this->m_context->mysqlconn->real_escape_string($password);
		
		$this->m_context->arrqueryc("UPDATE Users SET passwdhash = \"$password\";", function($message) {
			handleError($message);
		});
    }
    
    public function updateUser($userid, $newprename, $newlastname)
    {
        $userid = $this->m_context->mysqlconn->real_escape_string($userid);
		$newprename = $this->m_context->mysqlconn->real_escape_string($newprename);
		$newlastname = $this->m_context->mysqlconn->real_escape_string($newlastname);
		
		$res = $this->m_context->arrqueryc("SELECT update_user($userid, \"$prename\", \"$lastname\") as res;", function($message){
			handleError($message);
		});
		
		if(sizeof($res) == 1)
		{
			if($res[0]['res'])
			{
				echo("<message>User was updated successfully</message>;");
			}
			else
			{
				handleError("User could not be updated.");
			}
		}
		else
		{
			handleError("Query result invalid.");
		}
    }
	
	public function deleteUser($userid)
	{
		$userid = $this->m_context->mysqlconn->real_escape_string($userid);
		
		$result = $this->m_context->arrqueryc("DELETE FROM Users WHERE id = $userid;", function($message){
			handleError($message);
		});
	}
    
    public function addGroup($groupname)
    {
        $groupname = $this->m_context->mysqlconn->real_escape_string($groupname);
		
		$res = $this->m_context->arrqueryc("SELECT add_group(\"$groupname\") as res;", function($message){
			handleError($message);
		});
		
		if(sizeof($res) == 1)
		{
			if($res[0]['res'])
			{
				echo("<message>Group was added successfully</message>;");
			}
			else
			{
				handleError("Group \"$groupname\" already exists.");
			}
		}
		else
		{
			handleError("Query result invalid.");
		}
    }
    
    public function updateGroup($groupid, $newgroupname)
    {
		$groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
        $newgroupname = $this->m_context->mysqlconn->real_escape_string($newgroupname);
		
		$res = $this->m_context->arrqueryc("SELECT update_group($groupid, \"$newgroupname\") as res;", function($message){
			handleError($message);
		});
		
		if(sizeof($res) == 1)
		{
			if($res[0]['res'])
			{
				echo("<message>Group was updated successfully</message>;");
			}
			else
			{
				handleError("Group could not be updated.");
			}
		}
		else
		{
			handleError("Query result invalid.");
		}
    }
    
    public function deleteGroup($groupid)
    {
        $groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
		
		$result = $this->m_context->arrqueryc("DELETE FROM Groups WHERE id = $groupid;", function($message){
			handleError($message);
		});
    }
    
    public function listGroups()
    {
        $result = $this->m_context->objqueryc("SELECT * FROM Groups;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<groups>\n");
				foreach($result as $row)
				{
					echo("<group>\n");
						echo("<id>" . $row->id . "</id>\n");
						echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
					echo("</group>\n");
				}			
			echo("</groups>");
		}
		else
		{
			echo("<groups></groups>");
		}
    }
	
	public function addUserToGroup($userid, $groupid)
	{
		$userid = $this->m_context->mysqlconn->real_escape_string($userid);
		$groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
		
		$this->m_context->arrqueryc("CALL add_usertogroup($userid, $groupid);", function($message){
			handleError($message);
		});
	}
	
	public function removeUserFromGroup($userid, $groupid)
	{
		$userid = $this->m_context->mysqlconn->real_escape_string($userid);
		$groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
		
		$this->m_context->arrqueryc("CALL remove_userfromgroup($userid, $groupid);", function($message){
			handleError($message);
		});
	}
    
    public function listRights()
    {
        $result = $this->m_context->objqueryc("SELECT * FROM Rights;", function($message) {
           handleError($message); 
        });
        
		if(sizeof($result) > 0)
		{
			echo("<rights>\n");
			foreach ($result as $row)
        	{
            	echo("<id>" . $row->id . "</id>\n");
				echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
        	}
			echo("</rights>\n");
		}
		else
		{
			echo("<rights></rights>");
		}
        
    }
    
    // public function addRight($name)
    // {
        
    // }
    
    // public function updateRight($rightid, $newname)
    // {
        
    // }
    
    // public function deleteRight($rightid)
    // {
        
    // }
    
    public function grantRight($groupid, $rightid)
    {
        $groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
		$rightid = $this->m_context->mysqlconn->real_escape_string($rightid);
		
		$this->m_context->arrqueryc("CALL grant_groupright($groupid, $rightid);", function($message){
			handleError($message);
		});
    }
    
    public function revokeRight($groupid, $rightid)
    {
        $groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
		$rightid = $this->m_context->mysqlconn->real_escape_string($rightid);
		
		$this->m_context->arrqueryc("CALL revoke_groupright($groupid, $rightid);", function($message){
			handleError($message);
		});
    }
    
    public function listGroupUsers($groupid)
    {
		$groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
        $result = $this->m_context->objqueryc("SELECT Users.* FROM Users INNER JOIN GroupUser ON User.id = GroupUser.userid WHERE GroupUser.groupid = $groupid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<users>\n");
				foreach($result as $row)
				{
					echo("<user>\n");
						echo("<id>" . $row->id . "</id>\n");
						echo("<username>" . htmlspecialchars($row->username, ENT_XML1, 'UTF-8') . "</username>\n");
						echo("<prename>" . htmlspecialchars($row->prename, ENT_XML1, 'UTF-8') . "</prename>\n");
						echo("<lastname>" . htmlspecialchars($row->lastname, ENT_XML1, 'UTF-8') . "</lastname>\n");
					echo("</user>\n");
				}			
			echo("</users>");
		}
		else
		{
			echo("<users></users>");
		}
    }
    
    public function listGroupRights($groupid)
    {
		$groupid = $this->m_context->mysqlconn->real_escape_string($groupid);
        $result = $this->m_context->objqueryc("SELECT Rights.* FROM Rights INNER JOIN GroupsRights on Rights.id = GroupsRights.rightid WHERE GroupsRights.groupid = $groupid;", function($message) {
           handleError($message); 
        });
        
		if(sizeof($result) > 0)
		{
			echo("<rights>\n");
			foreach ($result as $row)
        	{
            	echo("<id>" . $row->id . "</id>\n");
				echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
        	}
			echo("</rights>\n");
		}
		else
		{
			echo("<rights></rights>");
		}
    }
    
    public function listUserGroups($userid)
    {
		$userid = $this->m_context->mysqlconn->real_escape_string($userid);
        $result = $this->m_context->objqueryc("SELECT Groups.* FROM Groups INNER JOIN GroupUser ON Groups.id = GroupUser.groupid WHERE GroupUser.userid = $userid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<groups>\n");
				foreach($result as $row)
				{
					echo("<group>\n");
						echo("<id>" . $row->id . "</id>\n");
						echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
					echo("</group>\n");
				}			
			echo("</groups>");
		}
		else
		{
			echo("<groups></groups>");
		}
    }
	
	public function validateUser($user, $passw)
	{
		$user = $this->m_context->mysqlconn->real_escape_string($user);
		$passw = $this->m_context->mysqlconn->real_escape_string($passw);
		
		$resu = $this->m_context->arrqueryc("SELECT validateUser(\"$user\", \"$passw\") as res;", function($message){
        	handleError($message);
    	});
		
		if($resu[0]['res'])
		{
			echo('1');
		}
		else{
			echo('0');
		}
	}
}
?>