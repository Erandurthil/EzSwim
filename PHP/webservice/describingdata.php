<?php
class DescDataManager //tags categories and stuff
{
    private $m_context;
    function __construct($dbcontext)
    {
       $this->m_context = $dbcontext;
    }
    
    
    public function addTag($name){
        $name = $this->m_context->mysqlconn->real_escape_string($name);
        
        $resintag = $this->m_context->arrqueryc("INSERT INTO tags(name) VALUES(\"$name\");", function($message){
        handleError($message);    });
        
        if($resintag){echo("Insert of Tag(name):$name sucessfull!");}
        else{handleError("Tag could not be inserted)");}       
        
    }
    
       
    public function updateTag($id,$name){
        $name = $this->m_context->mysqlconn->real_escape_string($name);
        $id = $this->m_context->mysqlconn->real_escape_string($id);
       
        $resuptag = $this->m_context->arrqueryc("UPDATE tags SET tags.name = \"$name\" WHERE tags.id = $id;", function($message){
        handleError($message);    });
        
        if($resuptag){echo("Update of Tag(name):$name sucessfukk!");}
        else{handleError("Tag could not be updated)");}       
        
    }
    
    
    public function removeTag($id){
        $id = $this->m_context->mysqlconn->real_escape_string($id);
       
        $resremtag = $this->m_context->arrqueryc("DELETE FROM tags WHERE tags.id = $id;", function($message){
        handleError($message);    });
        
        if($resremtag){echo("Delete of Tag(name):$id sucessfull!");}
        else{handleError("Tag could not be deleted)");}       
        
    }
    
        public function allTag(){
        
       
        $resalltag = $this->m_context->objqueryc("SELECT * FROM tags;", function($message){
        handleError($message);    });
        
         if(sizeof($resalltag) >= 1)
    {
        echo("<tags>\n");
        foreach ($resalltag as $value) {
                  
        echo("<tag>\n");
        echo("<id>" . $value->id . "</id>\n");
        echo("<name>" . $value->name . "</name>\n");
        echo("</tag>\n");
    }
        echo("</tags>");
    }
    else 
    {
        handleError("Tag not found");
    }    
        
    }
    
      public function getTagData($id){
        
        $id = $this->m_context->mysqlconn->real_escape_string($id);
        $resgettag = $this->m_context->objqueryc("SELECT * FROM tags WHERE tags.id = $id;", function($message){
        handleError($message);    });
        
         if(sizeof($resgettag) === 1)
    {         
		echo("<data>");
        echo("<Tag>");
        echo("<id>" . $resgettag[0]->id . "</id>");
        echo("<name>" . $resgettag[0]->name . "</name>");
        echo("</Tag>");
		echo("</data>");
    }
    
    else 
    {
        handleError("Tag not found");
    }    
      }
	  
	 
	  
      
     public function addTagToSnippet($snippetid, $tagid){
        
       $snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
	   $tagid = $this->m_context->mysqlconn->real_escape_string($tagid);
        $resaddtagtosnip = $this->m_context->arrqueryc("CALL add_tagtosnippet($snippetid, $tagid);", function($message){
        handleError($message);    });
        
         if($resaddtagtosnip){
             echo("Tag(id):$tagid added to Snippet(id):$snippetid!");
         }
        else{handleError("Tag could not be added to snippet)");}       
    
   
        
    }
    
    public function removeTagFromSnippet($snippetid, $tagid){
        
       $snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
	   $tagid = $this->m_context->mysqlconn->real_escape_string($tagid);
        $resremtagtosnip = $this->m_context->arrqueryc("CALL remove_tagfromsnippet($snippetid, $tagid);", function($message){
        handleError($message);    });
        
         if($resremtagtosnip){
             echo("Tag(id):$tagid removed from Snippet(id):$snippetid!");
         }
        else{handleError("Tag could not be removed from snippet)");}       
    
   
        
    }
    
    public function addTagToArticle($articleid, $tagid){
        
       $articleid = $this->m_context->mysqlconn->real_escape_string($articleid);
	   $tagid = $this->m_context->mysqlconn->real_escape_string($tagid);
        $resaddtagtoart = $this->m_context->arrqueryc("CALL add_tagtoarticle($articleid, $tagid);", function($message){
        handleError($message);    });
        
         if($resaddtagtoart){
             echo("Tag(id):$tagid added to Article(id):$articleid!");
         }
        else{handleError("Tag could not be added to article)");}       
    
   
        
    }
    
     public function removeTagFromArticle($articleid, $tagid){
        
       $articleid = $this->m_context->mysqlconn->real_escape_string($articleid);
	   $tagid = $this->m_context->mysqlconn->real_escape_string($tagid);
        $resremtagfromart = $this->m_context->arrqueryc("CALL remove_tagfromarticle($articleid, $tagid);", function($message){
        handleError($message);    });
        
         if($resremtagfromart){
             echo("Tag(id):$tagid removed from Article(id):$articleid!");
         }
        else{handleError("Tag could not be removed from article)");}       
    }
	
	public function getArticleTags($articleid)
	{
		$articleid = $this->m_context->mysqlconn->real_escape_string($articleid);
		$result = $this->m_context->objqueryc("SELECT Tags.* FROM Tags INNER JOIN ArticleTag ON Tags.id = ArticleTag.tagid WHERE ArticleTag.articleid = $articleid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<tags>\n");
			foreach($result as $row)
			{
				echo("<tag>\n");
					echo("<id>" . $row->id . "</id>\n");
					echo("<name>" . $row->name . "</name>\n");
				echo("</tag>\n");
			}
			echo("</tags>");
		}
		else {
			echo "<tags></tags>";
		}
	}
	
	public function getArticleCategories($articleid)
	{
		$articleid = $this->m_context->mysqlconn->real_escape_string($articleid);
		$result = $this->m_context->objqueryc("SELECT Categories.* FROM Categories INNER JOIN CategoryArticle ON CategoryArticle.categoryid = Categories.id WHERE CategoryArticle.articleid = $articleid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<categories>\n");
			foreach($result as $row)
			{
				echo("<category>\n");
					echo("<id>" . $row->id . "</id>\n");
					echo("<name>" . $row->name . "</name>\n");
				echo("</category>\n");
			}
			echo("</categories>");
		}
		else {
			echo "<categories></categories>";
		}
	}
	
	public function getSnippetTags($snippetid)
	{
		$snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
		$result = $this->m_context->objqueryc("SELECT Tags.* FROM Tags INNER JOIN SnippetTag ON Tags.id = SnippetTag.tagid WHERE SnippetTag.snippetid = $snippetid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<tags>\n");
			foreach($result as $row)
			{
				echo("<tag>\n");
					echo("<id>" . $row->id . "</id>\n");
					echo("<name>" . $row->name . "</name>\n");
				echo("</tag>\n");
			}
			echo("</tags>");
		}
		else {
			echo "<tags></tags>";
		}
	}
	
	public function getSnippetCategories($snippetid)
	{
		$snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
		$result = $this->m_context->objqueryc("SELECT Categories.* FROM Categories INNER JOIN CategorySnippet ON Categories.id = CategorySnippet.categoryid WHERE CategorySnippet.snippetid = $snippetid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<categories>\n");
			foreach($result as $row)
			{
				echo("<category>\n");
					echo("<id>" . $row->id . "</id>\n");
					echo("<name>" . $row->name . "</name>\n");
				echo("</category>\n");
			}
			echo("</categories>");
		}
		else {
			echo "<categories></categories>";
		}
	}
	
	//languages and ides
	
	public function getIDE($ideid)
	{
		$ideid = $this->m_context->mysqlconn->real_escape_string($ideid);
		$result = $this->m_context->objqueryc("SELECT * FROM IDEs WHERE id = $ideid;", function($message) {
			handleError($message);
		});
		
		if(sizeof($result) == 1)
		{
			echo("<data>");
			echo("<ide>\n");
			echo("<id>" . $result[0]->id . "</id>\n");
			echo("<name>" . htmlspecialchars($result[0]->name, ENT_XML1, 'UTF-8') . "</name>\n");
			echo("<description>" . htmlspecialchars($result[0]->description, ENT_XML1, 'UTF-8') . "</description>\n");
			echo("</ide>\n");
			echo("</data>");
		}
		else
		{
			handleError("IDE not found.");
		}
	}
	
	public function getIDEs()
	{
		$result = $this->m_context->objqueryc("SELECT * FROM IDEs;", function($message) {
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<ides>\n");
			foreach($result as $row)
			{
				echo("<ide>\n");
				echo("<id>" . $row->id . "</id>\n");
				echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
				echo("<description>" . htmlspecialchars($row->description, ENT_XML1, 'UTF-8') . "</description>\n");
				echo("</ide>\n");
			}	
			echo("</ides>");		
		}
		else
		{
			echo("<ides></ides>");
		}
	}
	
	public function addIDE($name, $description)
	{
		$name = $this->m_context->mysqlconn->real_escape_string($name);
		$description = $this->m_context->mysqlconn->real_escape_string($description);
		
		$this->m_context->arrqueryc("INSERT INTO IDEs(name, description) VALUES(\"$name\", \"$description\");", function($message){
			handleError($message);
		});
	}
	
	public function deleteIDE($ideid)
	{
		$ideid = $this->m_context->mysqlconn->real_escape_string($ideid);
		$this->m_context->arrqueryc("DELETE FROM IDEs WHERE id = $ideid;", function($message){
			handleError($message);
		});
	}
	
	public function updateIDE($ideid, $name, $description)
	{
		$ideid = $this->m_context->mysqlconn->real_escape_string($ideid);
		$name = $this->m_context->mysqlconn->real_escape_string($name);
		$description = $this->m_context->mysqlconn->real_escape_string($description);
		$this->m_context->arrqueryc("UPDATE IDEs SET name = \"$name\", description = \"$description\" WHERE id = $ideid;", function($message){
			handleError($message);
		});
	}
	
	public function getLanguage($langid)
	{
		$langid = $this->m_context->mysqlconn->real_escape_string($langid);
		$result = $this->m_context->objqueryc("SELECT * FROM Language WHERE id = $langid;", function($message) {
			handleError($message);
		});
		
		if(sizeof($result) == 1)
		{
			echo("<data>");
			echo("<language>\n");
			echo("<id>" . $result[0]->id . "</id>\n");
			echo("<name>" . htmlspecialchars($result[0]->name, ENT_XML1, 'UTF-8') . "</name>\n");
			echo("<description>" . htmlspecialchars($result[0]->description, ENT_XML1, 'UTF-8') . "</description>\n");
			echo("</language>\n");
			echo("</data>");
		}
		else
		{
			handleError("Language not found.");
		}
	}	
	
	public function getLanguages()
	{
		$result = $this->m_context->objqueryc("SELECT * FROM Language;", function($message) {
			handleError($message);
		});
		
		if(sizeof($result) > 0)
		{
			echo("<languages>\n");
			foreach($result as $row)
			{
				echo("<language>\n");
				echo("<id>" . $row->id . "</id>\n");
				echo("<name>" . htmlspecialchars($row->name, ENT_XML1, 'UTF-8') . "</name>\n");
				echo("<description>" . htmlspecialchars($row->description, ENT_XML1, 'UTF-8') . "</description>\n");
				echo("</language>\n");
			}		
			echo("</languages>");
		}
		else
		{
			echo("<languages></languages>");
		}
	}
	
	public function addLanguage($name, $description)
	{
		$name = $this->m_context->mysqlconn->real_escape_string($name);
		$description = $this->m_context->mysqlconn->real_escape_string($description);
		$this->m_context->arrqueryc("INSERT INTO Language(name, description) VALUES(\"$name\", \"$description\");", function($message){
			handleError($message);
		});
	}
	
	public function deleteLanguage($langid)
	{
		$langid = $this->m_context->mysqlconn->real_escape_string($langid);
		$this->m_context->arrqueryc("DELETE FROM Language WHERE id = $langid;", function($message){
			handleError($message);
		});
	}
	
	public function updateLanguage($langid, $name, $description)
	{
		$langid = $this->m_context->mysqlconn->real_escape_string($langid);
		$name = $this->m_context->mysqlconn->real_escape_string($name);
		$description = $this->m_context->mysqlconn->real_escape_string($description);
		$this->m_context->arrqueryc("UPDATE Language SET name = \"$name\", description = \"$description\" WHERE id = $langid;", function($message){
			handleError($message);
		});
	}
	
	public function addCategory($name, $description)
	{
		$name = $this->m_context->mysqlconn->real_escape_string($name);
		$description = $this->m_context->mysqlconn->real_escape_string($description);
        
        $result = $this->m_context->arrqueryc("INSERT INTO categories(name, description) VALUES(\"$name\", \"$description\");", function($message){
        handleError($message);    });
        
        if($result){echo("Insert of Category:$name sucessfull!");}
        else{handleError("Tag could not be inserted)");}       
	}
	
	public function updateCategory($catid, $name, $description)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid);
		$name = $this->m_context->mysqlconn->real_escape_string($name);
		$description = $this->m_context->mysqlconn->real_escape_string($description);
        
        $result = $this->m_context->arrqueryc("UPDATE Categories SET name = \"$name\", description = \"$description\" WHERE id = $catid;", function($message){
        handleError($message);    });
	}
	
	public function removeCategory($catid)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid);
		
		$result = $this->m_context->arrqueryc("DELETE FROM Categories WHERE id = $catid;", function($message){
			handleError($message);
		});
	}
	
	public function getCategories()
	{
		$result = $this->m_context->objqueryc("SELECT * FROM Categories;", function($message){
			handleError($message);
		});
		
		echo("<categories>\n");
		foreach($result as $row)
		{
			echo("<category>\n");
			echo("<id>" . $row->id . "</id>\n");
			echo("<name>" . $row->name . "</name>\n");
			echo("<description>" . $row->description . "</description>\n");
			echo("</category>\n");
		}
		echo("</categories>");
	}
	
	public function getCategory($catid)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid); 
		$result = $this->m_context->objqueryc("SELECT * FROM Categories WHERE id = $catid;", function($message){
			handleError($message);
		});
		
		if(sizeof($result) == 1)
		{
			echo("<data>");
			echo("<category>\n");
			echo("<id>" . $result[0]->id . "</id>\n");
			echo("<name>" . $result[0]->name . "</name>\n");
			echo("<description>" . $result[0]->description . "</description>\n");
			echo("</category>\n");
			echo("</data>");
		}
		else {
			handleError("Category not found.");
		}
		
	}
	
	public function addCategoryToSnippet($snippetid, $catid)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid); 
		$snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid);
		
		$this->m_context->arrqueryc("CALL add_categorytosnippet($snippetid, $catid);", function($message){
			handleError($message);
		});
	}
	
	public function removeCategoryFromSnippet($snippetid, $catid)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid); 
		$snippetid = $this->m_context->mysqlconn->real_escape_string($snippetid); 
		
		$this->m_context->arrqueryc("CALL remove_categoryfromsnippet($snippetid, $catid);", function($message){
			handleError($message);
		});
	}
	
	public function addCategoryToArticle($articleid, $catid)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid); 
		$articleid = $this->m_context->mysqlconn->real_escape_string($articleid); 
		
		$this->m_context->arrqueryc("CALL add_categorytoarticle($articleid, $catid);", function($message){
			handleError($message);
		});
	}
	
	public function removeCategoryFromArticle($articleid, $catid)
	{
		$catid = $this->m_context->mysqlconn->real_escape_string($catid); 
		$articleid = $this->m_context->mysqlconn->real_escape_string($articleid); 
		
		$this->m_context->arrqueryc("CALL remove_categoryfromarticle($articleid, $catid);", function($message){
			handleError($message);
		});
	}
	
     
}
?>