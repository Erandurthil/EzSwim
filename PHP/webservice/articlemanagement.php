<?php
//  articlemanagement.php
require_once("mysqlwrapper.php");
require_once("errorhandling.php");
require_once("snippetmanagement.php");
class ArticleManager
{
    private $db;
	private $snippetmanager;
    function __construct($context)
    {
        $this->db = $context;
		$this->snippetmanager = new SnippetManager($context);
    }
    
    public function getArticle($articleid)
    {
        $articleid = $this->db->mysqlconn->real_escape_string($articleid);
        
        $result = $this->db->objqueryc("SELECT * FROM Articles WHERE id = $articleid", function($message)
        {
            handleError($message);
        });
        if(sizeof($result) === 1)
        {
            echo "<article>";
            echo "<id>" . $result[0]->id . "</id>";
            echo "<title>" . htmlspecialchars($result[0]->title, ENT_XML1, 'UTF-8') . "</title>";
            echo "<text>" . htmlspecialchars($result[0]->text, ENT_XML1, 'UTF-8') . "</text>";
            
            $resuser = $this->m_context->objqueryc("SELECT * FROM USERS WHERE id = $value->userid;", function($message){
			handleError($message);});
            echo("<userid>" . $resuser[0]->id . "</userid>\n");
            echo("<username>" . htmlspecialchars($resuser[0]->username, ENT_XML1, 'UTF-8') . "</username>\n");
            echo("<uprename>" . $resuser[0]->prename . "</uprename>\n");
            echo("<ulastname>" . $resuser[0]->lastname . "</ulastname>\n");
            
            $restags = $this->m_context->objqueryc("SELECT Tags.* FROM Tags INNER JOIN ArticleTag ON Tags.id = ArticleTag.tagid WHERE ArticleTag.articleid = $result[0]->id;", function($message){
			handleError($message);});
            foreach ($rescats as $tag)
            {
                  echo("<tag>" . htmlspecialchars($tag->name, ENT_XML1, 'UTF-8') . "</tag>\n");
            }
            
            
            $rescats = $this->m_context->objqueryc("SELECT Categories.* FROM Categories INNER JOIN CategoryArticle ON CategoryArticle.categoryid = Categories.id WHERE CategoryArticle.articleid = $result[0]->id;", function($message){
			handleError($message);});
            foreach ($rescats as $cat)
            {
                  echo("<category>" . htmlspecialchars($cat->name, ENT_XML1, 'UTF-8') . "</category>\n");
            }
            
            
            echo "</article>";
        }
        else
       {
           handleError("Article not found.");
       }
    }
    
    public function filterArticles($filter)
    {
        
    }
    
    public function getArticles()
    {       
        $result = $this->db->objqueryc("SELECT * FROM Articles;", function($message)
        {
            handleError($message);
        });
        if(sizeof($result) >= 1)
        {
            echo "<articles>";
            foreach($result as $row)
            {
                echo "<article>";
                echo "<id>" . $row->id . "</id>";
                echo "<title>" . htmlspecialchars($row->title, ENT_XML1, 'UTF-8') . "</title>";
                echo "<text>" . htmlspecialchars($row->text, ENT_XML1, 'UTF-8') . "</text>";
                
                $resuser = $this->db->objqueryc("SELECT * FROM USERS WHERE id = $row->userid;", function($message){
			    handleError($message);});
                echo("<userid>" . $resuser[0]->id . "</userid>\n");
                echo("<username>" . htmlspecialchars($resuser[0]->username, ENT_XML1, 'UTF-8') . "</username>\n");
                echo("<uprename>" . $resuser[0]->prename . "</uprename>\n");
                echo("<ulastname>" . $resuser[0]->lastname . "</ulastname>\n");
            
                $restags = $this->db->objqueryc("SELECT Tags.* FROM Tags INNER JOIN ArticleTag ON Tags.id = ArticleTag.tagid WHERE ArticleTag.articleid = $row->id;", function($message){
			    handleError($message);});
                foreach ($restags as $tag)
                {
                      echo("<tag>" . htmlspecialchars($tag->name, ENT_XML1, 'UTF-8') . "</tag>\n");
                }
                     
                $rescats = $this->db->objqueryc("SELECT Categories.* FROM Categories INNER JOIN CategoryArticle ON CategoryArticle.categoryid = Categories.id WHERE CategoryArticle.articleid = $row->id;", function($message){
			    handleError($message);});
                foreach ($rescats as $cat)
                {
                      echo("<category>" . htmlspecialchars($cat->name, ENT_XML1, 'UTF-8') . "</category>\n");
                }
                echo "</article>";
            }
            echo "</articles>";
        }
        else
       {
           handleError("Article not found.");
       }
    }
    
    public function updateArticle($articleid, $title, $text, $user)
    {
        $articleid = $this->db->mysqlconn->real_escape_string($articleid);
        $title = $this->db->mysqlconn->real_escape_string($title);
        $text = $this->db->mysqlconn->real_escape_string($text);
        $user = $this->db->mysqlconn->real_escape_string($user);
        
        $resu = $this->db->objqueryc("SELECT users.id FROM users WHERE username = \"$user\";", function($message){
            handleError($message);
        });
     
        if(sizeof($resu) === 1)
        {        
            $resupart = $this->db->arrqueryc("CALL update_article($articleid,\"$title\",\"$text\"," . $resu[0]->id .");", function($message){
            handleError($message);    });
            if($resupate)
            {
                echo("Article was updated successfully");
            }
            else
            {
                handleError("Article could not be updated.");
            }
        }
        else 
        {
            handleError("Article could not be updated.");
        }
    }
    
    public function deleteArticle($articleid)
    {
        $articleid  = $this->db->mysqlconn->real_escape_string($articleid);
        
        if($this->db->arrqueryc("CALL remove_article($articleid);", function ($message){
            handleError($message);
        }))
        {
             echo("Article deleted successfully.");
        }
        else
        {
            handleError("Article could not be deleted.");
        }
    }
    
    public function addArticle($title, $text, $user, $tags, $categories)
    {
        $title = $this->db->mysqlconn->real_escape_string($title);
        $text = $this->db->mysqlconn->real_escape_string($text);
        $user = $this->db->mysqlconn->real_escape_string($user);
        $tags = $this->db->mysqlconn->real_escape_string($tags);
        $categories = $this->db->mysqlconn->real_escape_string($categories);
        
        $resu = $this->db->objqueryc("SELECT users.id FROM users WHERE username = \"$user\";", function($message){
            handleError($message);
        });
     
        if(sizeof($resu) === 1)
        {        
            $resupart = $this->db->arrqueryc("CALL add_article(\"$title\",\"$text\"," . $resu[0]->id .",\"$tags\",\"$categories\");", function($message){
            handleError($message);});
            if($resupart)
            {
                echo("Article was added successfully.");
            }
            else
            {
                handleError("Article could not be added.");
            }
        }
        else 
        {
            handleError("Article could not be added.");
        }
    }
    
    public function addSnippetToArticle($articleid, $snippetid)
    {
        $articleid = $this->db->mysqlconn->real_escape_string($articleid);
        $snippetid = $this->db->mysqlconn->real_escape_string($snippetid);
        
        $this->db->arrqueryc("CALL add_snippettoarticle($articleid, $snippetid);", function($message){
            handleError($message);
        });
    }
    
    public function removeSnippetFromArticle($articleid, $snippetid)
    {
        $articleid = $this->db->mysqlconn->real_escape_string($articleid);
        $snippetid = $this->db->mysqlconn->real_escape_string($snippetid);
        
        $this->db->arrqueryc("CALL remove_snippetfromarticle($articleid, $snippetid);", function($message){
            handleError($message);
        });
    }
    
    public function getSnippetsInArticle($articleid)
    {
         $articleid = $this->db->mysqlconn->real_escape_string($articleid);
         
         $result = $this->db->objqueryc(
             "SELECT Snippets.* FROM Snippets INNER JOIN SnippetArticle ON Snippets.id = SnippetArticle.snippetid INNER JOIN Articles on SnippetArticle.articleid = Articles.id WHERE Articles.id = \"$articleid\""
             , function($message){handleError($message);});
             
         if(sizeof($result > 0))
         {
             echo("<snippets>\n");
             foreach($result as $row)
             {
                echo("<snippet>\n");
                echo("<id>" . $row->id . "</id>\n");
                echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
                echo("<description>" . htmlspecialchars($row->description, ENT_XML1, 'UTF-8') . "</description>\n");
				echo("<code>" . htmlspecialchars($value->code, ENT_XML1, 'UTF-8') . "</code>");
                echo("<langid>" . $row->langid . "</langid>\n");
                echo("<ideid>" . $row->ideid . "</ideid>\n");
                echo("<userid>" . $row->userid . "</userid>\n");
                echo("<version>" . $row->version . "</version>\n");
                echo("<timestamp>" . $row->timestamp . "</timestamp>\n");
                echo("<closed>" . $row->closed . "</closed>\n");
                echo("</snippet>\n");
             }             
             echo("</snippets>");
         }
         else
         {
             echo("<snippets>\n</snippets>");
         }
    }
    
    public function getArticlesWithSnippet($snippetid)
    {
        $snippetid = $this->db->mysqlconn->real_escape_string($snippetid);
         
         $result = $this->db->objqueryc(
             "SELECT Articles.* FROM Articles INNER JOIN SnippetArticle ON Articles.id = SnippetArticle.articleid INNER JOIN Snippets on SnippetArticle.snippetid = Snippets.id WHERE Snippets.id = \"$snippetid\""
             , function($message){handleError($message);});
             
         if(sizeof($result > 0))
         {
             echo("<articles>\n");
             foreach($result as $row)
             {
                echo("<article>\n");
                echo("<id>" . $row->id . "</id>\n");
                echo("<title>" . htmlspecialchars($row->title, ENT_XML1, 'UTF-8') . "</title>\n");
                echo("<text>" . htmlspecialchars($row->text, ENT_XML1, 'UTF-8') . "</text>\n");
                echo("<userid>" . $row->userid . "</userid>\n");
                echo("</article>\n");
             }             
             echo("</articles>");
         }
         else
         {
             echo("<articles>\n</articles>");
         }
    }
    
    public function getArticlesByCategory($catid)
    {
        $catid = $this->db->mysqlconn->real_escape_string($catid);
         
         $result = $this->db->objqueryc(
             "SELECT Articles.* FROM Articles INNER JOIN CategoryArticle ON Articles.id = CategoryArticle.articleid INNER JOIN Categories on CategoryArticle.categoryid = Categories.id WHERE Categories.id = \"$catid\""
             , function($message){handleError($message);});
             
         if(sizeof($result > 0))
         {
             echo("<articles>\n");
             foreach($result as $row)
             {
                echo("<article>\n");
                echo("<id>" . $row->id . "</id>\n");
                echo("<title>" . htmlspecialchars($row->title, ENT_XML1, 'UTF-8') . "</title>\n");
                echo("<text>" . htmlspecialchars($row->text, ENT_XML1, 'UTF-8') . "</text>\n");
                echo("<userid>" . $row->userid . "</userid>\n");
                echo("</article>\n");
             }             
             echo("</articles>");
         }
         else
         {
             echo("<articles>\n</articles>");
         }
    }
    
    public function getArticlesByTag($tagid)
    {
        $tagid = $this->db->mysqlconn->real_escape_string($tagid);
         
         $result = $this->db->objqueryc(
             "SELECT Articles.* FROM Articles INNER JOIN ArticleTag ON Articles.id = ArticleTag.articleid INNER JOIN Tags on ArticleTag.tagid = Tags.id WHERE Tags.id = \"$tagid\""
             , function($message){handleError($message);});
             
         if(sizeof($result > 0))
         {
             echo("<articles>\n");
             foreach($result as $row)
             {
                echo("<article>\n");
                echo("<id>" . $row->id . "</id>\n");
                echo("<title>" . htmlspecialchars($row->title, ENT_XML1, 'UTF-8') . "</title>\n");
                echo("<text>" . htmlspecialchars($row->text, ENT_XML1, 'UTF-8') . "</text>\n");
                echo("<userid>" . $row->userid . "</userid>\n");
                echo("</article>\n");
             }             
             echo("</snippets>");
         }
         else
         {
             echo("<articles>\n</articles>");
         }
    }
	
	public function getArticlesJoinedAndFiltered($categories, $tags, $filter)
	 {
		 
	 }
	 
	 public function getArticleJoined($sarticleid)
	 {
		 
	 }
}
?>