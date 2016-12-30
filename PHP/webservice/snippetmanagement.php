<?php
//  snippetmanagement.php
class SnippetManager
{
    private $m_context;
    
    function __construct($dbcontext)
    {
        $this->m_context = $dbcontext;
    }
    
    public function getSnippetData($snipid)
    {
    $snipid = $this->m_context->mysqlconn->real_escape_string($snipid);
   
    
    $ressnipp = $this->m_context->objqueryc("SELECT * FROM snippets WHERE id = $snipid;", function($message){
        handleError($message);
    });
     
    if(sizeof($ressnipp) === 1)
    {
        echo("<data>");
        echo("<Snippet>");
        echo("<id>" . $ressnipp[0]->id . "</id>");
        echo("<name>" . htmlspecialchars($ressnipp[0]->name, ENT_XML1, 'UTF-8') . "</name>");
        echo("<description>" . htmlspecialchars($ressnipp[0]->description, ENT_XML1, 'UTF-8') . "</description>");
        echo("<langid>" . $ressnipp[0]->langid . "</langid>");
        echo("<ideid>" . $ressnipp[0]->ideid . "</ideid>");
        echo("<code>" . htmlspecialchars($ressnipp[0]->code, ENT_XML1, 'UTF-8') . "</code>");
        echo("<userid>" . $ressnipp[0]->userid . "</userid>");
        echo("<version>" . $ressnipp[0]->version . "</version>");
        echo("<timestamp>" . $ressnipp[0]->timestamp . "</timestamp>");
        echo("<closed>" . $ressnipp[0]->closed . "</closed>");
        echo("</Snippet>");
        echo("</data>");
    }
    else 
    {
        handleError("Snippet not found");
    }
    
    }
    
    public function filterSnippets($filter)
    {
      
    }
    
    public function getSnippets()
    {   
    $ressnipp = $this->m_context->objqueryc("SELECT * FROM snippets;", function($message){
        handleError($message);
    });

     
    if(sizeof($ressnipp) >= 1)
    {
        echo("<Snippets>\n");
        foreach ($ressnipp as $value) {
                  
        echo("<Snippet>\n");
        echo("<id>" . $value->id . "</id>\n");
        echo("<name>" . htmlspecialchars($value->name, ENT_XML1, 'UTF-8') . "</name>\n");
        echo("<description>" . htmlspecialchars($value->description, ENT_XML1, 'UTF-8') . "</description>\n");

        $reslang = $this->m_context->objqueryc("SELECT * FROM Language WHERE id = $value->langid;", function($message) {
			handleError($message);});        
        echo("<langid>" . $reslang[0]->id . "</langid>\n");
        echo("<langname>" . $reslang[0]->name . "</langname>\n");
        echo("<langdescription>" . htmlspecialchars($reslang[0]->description, ENT_XML1, 'UTF-8') . "</langdescription>\n");


        $reside = $this->m_context->objqueryc("SELECT * FROM IDEs WHERE id = $value->ideid;", function($message) {
			handleError($message);});
        echo("<ideid>" . $reside[0]->id . "</ideid>\n");
        echo("<idename>" . htmlspecialchars($reside[0]->name, ENT_XML1, 'UTF-8') . "</idename>\n");
        echo("<idedescription>" . htmlspecialchars($reside[0]->description, ENT_XML1, 'UTF-8') . "</idedescription>\n");


        echo("<code>" . htmlspecialchars($value->code, ENT_XML1, 'UTF-8') . "</code>\n");

        $resuser = $this->m_context->objqueryc("SELECT * FROM USERS WHERE id = $value->userid;", function($message){
			handleError($message);});
        echo("<userid>" . $resuser[0]->id . "</userid>\n");
        echo("<username>" . htmlspecialchars($resuser[0]->username, ENT_XML1, 'UTF-8') . "</username>\n");
        echo("<uprename>" . $resuser[0]->prename . "</uprename>\n");
        echo("<ulastname>" . $resuser[0]->lastname . "</ulastname>\n");

        echo("<version>" . $value->version . "</version>\n");
        echo("<timestamp>" . $value->timestamp . "</timestamp>\n");
        echo("<closed>" . $value->closed . "</closed>\n");

        $restags = $this->m_context->objqueryc("SELECT Tags.* FROM Tags INNER JOIN SnippetTag ON Tags.id = SnippetTag.tagid WHERE SnippetTag.snippetid = $value->id;", function($message){
			handleError($message);});
            
        foreach ($restags as $tag)
        {
            echo("<tag>" . htmlspecialchars($tag->name, ENT_XML1, 'UTF-8') . "</tag>\n");
        }

        $rescats =  $this->m_context->objqueryc("SELECT Categories.* FROM Categories INNER JOIN CategorySnippet ON Categories.id = CategorySnippet.categoryid WHERE CategorySnippet.snippetid = $value->id;", function($message){
			handleError($message);});
        foreach ($rescats as $cat)
        {
            echo("<category>" . htmlspecialchars($cat->name, ENT_XML1, 'UTF-8') . "</category>\n");
        }

        echo("</Snippet>\n");
    }
        echo("</Snippets>");
    }
    else 
    {
        echo("<Snippets></Snippets>");
    }
    }
    
    public function updateSnippet($snippetid, $name, $description, $code, $langid, $ideid, $user, $version)
    {
     $user = $this->m_context->mysqlconn->real_escape_string($user);
     $snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
     $name = $this->m_context->mysqlconn->real_escape_string($name);
     $description = $this->m_context->mysqlconn->real_escape_string($description);
     $code = $this->m_context->mysqlconn->real_escape_string($code);
     $langid = $this->m_context->mysqlconn->real_escape_string($langid);
     $ideid = $this->m_context->mysqlconn->real_escape_string($ideid);
     $version = $this->m_context->mysqlconn->real_escape_string($version);
   
    
    $resu = $this->m_context->objqueryc("SELECT users.id FROM users WHERE username = \"$user\";", function($message){
        handleError($message);
    });
     
    if(sizeof($resu) === 1)
    {
       
        $resupsnip = $this->m_context->arrqueryc("CALL update_snippet($snippetid,\"$name\",\"$description\",\"$code\",$langid,$ideid," . $resu[0]->id . ",\"$version\");", function($message){
        handleError($message);    });
        
        if($resupsnip) {echo("Update of Snippet(id):$snippetid sucessfull!");}
        else{handleError("Snippet could not be updated)");}
    }
    else 
    {
        handleError("Snippet could not be updated");
    }
    }
    
    public function closeSnippet($snippetid)
    {
        $snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
        
        $resclsnip = $this->m_context->arrqueryc("CALL close_snippet($snippetid);", function($message){
        handleError($message);    });
        
        if($resclsnip){echo("Close of Snippet(id):$snippetid sucessfull!");}
        else{handleError("Snippet could not be closed)");}       
    }
    
    public function reopenSnippet($snippetid)
    {
        $snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
        
        $resclsnip = $this->m_context->arrqueryc("CALL reopen_snippet($snippetid);", function($message){
        handleError($message);    });
        
        if($resclsnip){echo("Reopen of Snippet(id):$snippetid sucessfull!");}
        else{handleError("Snippet could not be reopened)");}       
    }
    
    public function deleteSnippet($snippetid)
    {
        $snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
        
        $resclsnip = $this->m_context->arrqueryc("CALL delete_snippet($snippetid);", function($message){
        handleError($message);    });
        
        if($resclsnip){echo("Delete of Snippet(id):$snippetid sucessfull!");}
        else{handleError("Snippet could not be deleted)");}   
    }
    
    public function addSnippet($user, $name, $description, $code, $langid, $ideid, $version, $tags, $categories)
    {
     $user = $this->m_context->mysqlconn->real_escape_string($user);
     $name = $this->m_context->mysqlconn->real_escape_string($name);
     $description = $this->m_context->mysqlconn->real_escape_string($description);
     $code = $this->m_context->mysqlconn->real_escape_string($code);
     $langid = $this->m_context->mysqlconn->real_escape_string($langid);
     $ideid = $this->m_context->mysqlconn->real_escape_string($ideid);
     $version = $this->m_context->mysqlconn->real_escape_string($version);
     $tags = $this->m_context->mysqlconn->real_escape_string($tags);
     $categories = $this->m_context->mysqlconn->real_escape_string($categories);
   
    
    $resu = $this->m_context->objqueryc("SELECT users.id FROM users WHERE username = \"$user\";", function($message){
        handleError($message);
    });
     
    if(sizeof($resu) === 1)
    {
       
        $resupsnip = $this->m_context->arrqueryc("CALL add_snippet(\"$name\",\"$description\",\"$code\",$langid,$ideid," . $resu[0]->id . ",\"$version\", \"$categories\", \"$tags\");", function($message){
        handleError($message);    });
        
        if($resupsnip) {echo("Insert of Snippet(name):$name sucessfull!");}
        else{handleError("Snippet could not be inserted)");}
    }
    else 
    {
        handleError("Snippet could not be updated");
    }
    }
    
     public function rollbackSnippet($snippetid, $versionid)
     {
         
     }
	 
	 public function getSnippetsByTag($tagid)
	 {
		 $tagid = $this->m_context->mysqlconn->real_escape_string($tagid);
		 $ressnipp = $this->m_context->objqueryc("SELECT Snippets.* FROM Snippets INNER JOIN SnippetTag ON Snippet.id = SnippetTag.snippetid WHERE SnippetTag.tagid = $tagid;", function($message){
			 handleError();
		 });
		 
		 if(sizeof($ressnipp) >= 1)
    	{
        	echo("<Snippets>");
        	foreach ($ressnipp as $value)
			{                  
				echo("<Snippet>");
				echo("<id>" . $value->id . "</id>");
				echo("<name>" . htmlspecialchars($value->name, ENT_XML1, 'UTF-8') . "</name>");
				echo("<description>" . htmlspecialchars($value->description, ENT_XML1, 'UTF-8') . "</description>");
				echo("<langid>" . $value->langid . "</langid>");
				echo("<ideid>" . $value->ideid . "</ideid>");
				echo("<code>" . htmlspecialchars($value->code, ENT_XML1, 'UTF-8') . "</code>");
				echo("<userid>" . $value->userid . "</userid>");
				echo("<version>" . $value->version . "</version>");
				echo("<timestamp>" . $value->timestamp . "</timestamp>");
				echo("<closed>" . $value->closed . "</closed>");
				echo("</Snippet>");
    		}
        	echo("</Snippets>");
    	}
    	else 
    	{
        	echo("<Snippets></Snippets>");
    	}
	 }
	 
	 public function getSnippetsByCategory($catid)
	 {
		 $catid = $this->m_context->mysqlconn->real_escape_string($catid);
		 $ressnipp = $this->m_context->objqueryc("SELECT Snippets.* FROM Snippets INNER JOIN CategorySnippet ON Snippet.id = CategorySnippet.snippetid WHERE CategorySnippet.categoryid = $catid;", function($message){
			 handleError();
		 });
		 
		 if(sizeof($ressnipp) >= 1)
    	{
        	echo("<Snippets>");
        	foreach ($ressnipp as $value)
			{                  
				echo("<Snippet>");
				echo("<id>" . $value->id . "</id>");
				echo("<name>" . htmlspecialchars($value->name, ENT_XML1, 'UTF-8') . "</name>");
				echo("<description>" . htmlspecialchars($value->description, ENT_XML1, 'UTF-8') . "</description>");
				echo("<langid>" . $value->langid . "</langid>");
				echo("<ideid>" . $value->ideid . "</ideid>");
				echo("<code>" . htmlspecialchars($value->code, ENT_XML1, 'UTF-8') . "</code>");
				echo("<userid>" . $value->userid . "</userid>");
				echo("<version>" . $value->version . "</version>");
				echo("<timestamp>" . $value->timestamp . "</timestamp>");
				echo("<closed>" . $value->closed . "</closed>");
				echo("</Snippet>");
    		}
        	echo("</Snippets>");
    	}
    	else 
    	{
        	echo("<Snippets></Snippets>");
    	}
	 }
	 
	 public function getSnippetsJoinedAndFiltered($categories, $tags, $filter)
	 {
		 $categories = $this->m_context->mysqlconn->real_escape_string($categories);
		 $tags = $this->m_context->mysqlconn->real_escape_string($tags);
		 $filter = $this->m_context->mysqlconn->real_escape_string($filter);
		 
		  
	 }
	 
	 public function getSnippetJoined($snippetid)
	 {
		 
	 }
}
?>