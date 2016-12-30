<?php        
    require_once('routing.php');
    require_once('snippetmanagement.php');
    require_once('mysqlwrapper.php');
    require_once('usermanagement.php');
    require_once('errorhandling.php');
    require_once('securitycheck.php');
    require_once('articlemanagement.php');
    require_once('describingdata.php');
    function setupRoutes($router)
    {
        $db = new DBContext(CONN_SERVER, CONN_DB, CONN_USER, CONN_PWD);
        
        if(!$db->connect())
        {
            handleError("ERROR: MySqlConnection failed!");
        }
        
        $db->setTransactHandling(true, true);
        
                
        $rt404 = new Route('404', 'routenotfound', true, false, function()
        {
            handleError("Route not found.");
        });
        $router->set404Route($rt404);
		
		$rtshowroutes = new Route("ShowRoutes", "routes/list", true, false, function($router){
			$router->printRoutes();
		});
		
		$rtshowroutes->setIncludeRouter(true);		
		$router->pushRoute($rtshowroutes);
        
        //test stuff
        $rttestpage = new Route('TestPage', 'test(.*)', true, false, function($pageid){
            readfile("../client/test$pageid.html");
        });
        
        $router->pushRoute($rttestpage);
        
        //define routes here and push them into router
        //Route() params:
        //1:    Name of the route
        //2:    Route regex. Expressions in parentheses represent uri parameters and will be passed to 4.
        //3:    Defines if the router should terminate after execution of this route
        //4:    Defines if the router should pass the $_POST parameters to the specified fundtion. The Post parameters will be split up into single params => the param count of your function must match the uri params + post params!
        //5:    A function wich matches the params given in the regex parameter (+ post params). Otherwise routes are ignored.
        
        //snippetmanagement
        $rtsnippet1 = new  Route('getSnippet', 'snippets/get', true, true, function ($user, $passw, $snippetid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
            {
                $manager = new SnippetManager($db);
                $manager->getSnippetData($snippetid);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
        
        // $rtsnippet2 = new Route('filterSnippets', 'snippets/filter', true, true, function($user, $passw, $filter){
        //     $db = createConnection();
        //     if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
        //     {
        //         $manager = new SnippetManager();
        //     $manager->GetSnippetOverLanguage($language);
        //     }
        //     else {
        //         handleError("Access denied.");
        //     }
            
        // });
        
        $rtsnippet3 = new Route('getAllSnippets', 'snippets/all', true, true, function($user, $passw){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
            {
                $manager = new SnippetManager($db);
            $manager->getSnippets();
            }
            else {
                handleError("Access denied.");
            }
            
        });
        
        $rtsnippet4 = new Route('updateSnippet', 'snippets/update', true, true, function($user, $passw, $snippetid, $name, $description, $code, $langid, $ideid, $version){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw,  "UPDATE_SNIPPET"))
            {
                $manager = new SnippetManager($db);
                $manager->updateSnippet($snippetid, $name, $description, $code, $langid, $ideid, $user, $version);
            }
            else {
                handeError("Access denied.");
            }
        });
        
        $rtsnippet5 = new Route('closeSnippet', 'snippets/close', true, true, function($user, $passw, $snippetid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "CLOSE_OPEN_SNIPPET"))
            {
                 $manager = new SnippetManager($db);
            $manager->closeSnippet($snippetid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $rtsnippet6 = new Route('reopenSnippet', 'snippets/reopen', true, true, function($user, $passw, $snippetid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "CLOSE_OPEN_SNIPPET"))
            {
                 $manager = new SnippetManager($db);
            $manager->reopenSnippet($snippetid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $rtsnippet7 = new Route('deleteSnippet', 'snippets/delete', true, true, function($user, $passw, $snippetid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "REMOVE_SNIPPET"))
            {
                $manager = new SnippetManager($db);
            $manager->deleteSnippet($snippetid);
            }
            else {
                handleError("Access denied.");
            }
            
        });
        
        $rtsnippet8 = new Route('addSnippet', 'snippets/add', true, true, function($user, $passw, $name, $description, $code, $langid, $ideid, $version, $tags, $categories){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "ADD_SNIPPET"))
            {
                 $manager = new SnippetManager($db);
                    $manager->addSnippet($user, $name, $description, $code, $langid, $ideid, $version, $tags, $categories);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $rtsnippet9 = new Route('rollbackSnippet', 'snippets/rollback', true, true, function($user, $passw, $snippetid, $versionid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "CHANGE_SNIPPET_CURRENTVERSION"))
            {
                 $manager = new SnippetManager();
            $manager->rollbackSnippet($snippetid, $versionid);
            }
            else {
               handleError("Access denied.");
            }
           
        });
		
		$rtsnippet10 = new  Route('getSnippetByTag', 'snippets/bytag', true, true, function ($user, $passw, $tagid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
            {
                $manager = new SnippetManager($db);
                $manager->getSnippetsByTag($tagid);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
		
		$rtsnippet11 = new  Route('getSnippetByCategory', 'snippets/bycategory', true, true, function ($user, $passw, $catid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
            {
                $manager = new SnippetManager($db);
                $manager->getSnippetsByCategory($catid);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
		
		$rtsnippet12 = new Route('getSnippetsJoined', 'snippets/filteredjoined', true, true, function($user, $passw, $categories, $tags, $filter)
		{
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
            {
                $manager = new SnippetManager($db);
                $manager->getSnippetsJoinedAndFiltered($categories, $tags, $filter);
            }
            else {
                handleError("Access denied.");
            }
		});
		
		$rtsnippet13 = new Route('getSnippetJoined', 'snippets/byidjoined', true, true, function($user, $passw, $snippetid){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_SNIPPETS"))
            {
                $manager = new SnippetManager($db);
                $manager->getSnippetsJoinedAndFiltered($snippetid);
            }
            else {
                handleError("Access denied.");
            }
		});
		
		               
        
        $router->pushRoute($rtsnippet1);
        //$router->pushRoute($rtsnippet2);
        $router->pushRoute($rtsnippet3);
        $router->pushRoute($rtsnippet4);
        $router->pushRoute($rtsnippet5);
        $router->pushRoute($rtsnippet6);
        $router->pushRoute($rtsnippet7);
        $router->pushRoute($rtsnippet8);
		$router->pushRoute($rtsnippet9);
		$router->pushRoute($rtsnippet10);
		$router->pushRoute($rtsnippet11);
        
        //articlemanagement
        $rtarticle1 = new Route('getArticle', 'articles/get', true, true, function($user, $passw, $articleid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
            {
                 $manager = new ArticleManager($db);
            $manager->getArticle($articleid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        // $rtarticle2 = new Route('filterArticles', 'articles/filter', true, true, function($user, $passw, $filter) {
        //     $db = createConnection();
        //     if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
        //     {
        //          $manager = new ArticleManager($db);
        //     $manager->filterArticles($filter);
        //     }
        //     else {
        //         handleError("Access denied.");
        //     }
           
        // });
        
        $rtarticle3 = new Route('getAllArticles', 'articles/all', true, true, function($user, $passw) {
             $db = createConnection();
             if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
            {
                $manager = new ArticleManager($db);
             	$manager->getArticles();
            }
            else {
                handleError("Access denied.");
            }
             
        });
        
        $rtarticle4 = new Route('updateArticle', 'articles/update', true, true, function($user, $passw, $articleid,  $title, $text) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "UPDATE_ARTICLE"))
            {
                 $manager = new ArticleManager($db);
             	$manager->updateArticle($articleid, $title, $text, $user);
            }
            else {
                handleError("Access denied.");
            }
            
        });
        
        $rtarticle5 = new Route('deleteArticle', 'articles/delete', true, true, function($user, $passw, $articleid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "REMOVE_ARTICLE"))
            {
                 $manager = new ArticleManager($db);
             $manager->deleteArticle($articleid);
            }
            else {
                handleError("Access denied.");
            }
            
        });
        
        $rtarticle6 = new Route('addArticle', 'articles/add', true, true, function($user, $passw, $title, $text, $tags, $categories) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "ADD_ARTICLE"))
            {
                 $manager = new ArticleManager($db);
             	$manager->addArticle($title, $text, $user, $tags, $categories);
            }
            else {
                handleError("Access denied.");
            }
            
        });
		
		$rtarticle7 = new Route('getArticlesByTag', 'articles/bytag', true, true, function($user, $passw, $tagid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
            {
                 $manager = new ArticleManager($db);
            $manager->getArticlesByTag($tagid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
		
		$rtarticle8 = new Route('getArticlesByCategory', 'articles/bycategory', true, true, function($user, $passw, $catid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
            {
                $manager = new ArticleManager($db);
            	$manager->getArticle($catid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
		
		$rtarticle9 = new Route('getArticlesJoined', 'articles/filteredjoined', true, true, function($user, $passw, $categories, $tags, $filter){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
            {
                $manager = new ArticleManager($db);
            	$manager->getArticlesJoinedAndFiltered($categories, $tags, $filter);
            }
            else {
                handleError("Access denied.");
            }
		});
		
		$rtarticle10 = new Route('getArticleJoined', 'articles/byidjoined', true, true, function($user, $passw, $articleid){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_ARTICLES"))
            {
                $manager = new ArticleManager($db);
            	$manager->getArticleJoined($articleid);
            }
            else {
                handleError("Access denied.");
            }
		});
        
        $router->pushRoute($rtarticle1);
        //$router->pushRoute($rtarticle2);
        $router->pushRoute($rtarticle3);
        $router->pushRoute($rtarticle4);
        $router->pushRoute($rtarticle5);
        $router->pushRoute($rtarticle6);
		$router->pushRoute($rtarticle7);
		$router->pushRoute($rtarticle8);
        
        //usermanagement
        
        
        $rtuser1 = new Route('addUser', 'users/add', true, true, function($user, $passw, $newusername, $newprename, $newlastname, $newpassword) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "ADD_USER"))
            {
                 $manager = new UserManager($db);
				 $manager->addUser($newusername, $newprename, $newlastname, $newpassword);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser2 = new Route('listUsers', 'users/list', true, true, function($user, $passw) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_USERS"))
            {
                 $manager = new UserManager($db);
				 $manager->listUsers();
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser3 = new Route('changePasswd', 'users/changepassword', true, true, function($user, $passw, $userid, $newpasswordhash) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "CHANGE_PASSWORD"))
            {
                 $manager = new UserManager($db);
				 $manager->changePassword($userid, $newpasswordhash);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser4 = new Route('updateUser', 'users/update', true, true, function($user, $passw, $userid, $newprename, $newlastname) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "UPDATE_USER"))
            {
                 $manager = new UserManager($db);
				 $manager->updateUser($userid, $newprename, $newlastname);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser5 = new Route('addGroup', 'groups/add', true, true, function($user, $passw, $newgroupname) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "ADD_GROUP"))
            {
                $manager = new UserManager($db);
				$manager->addGroup($newgroupname);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
        
        $rtuser6 = new Route('updateGroup', 'groups/update', true, true, function($user, $passw, $groupid, $newgroupname) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "UPDATE_GROUP"))
            {
                 $manager = new UserManager($db);
				 $manager->updateGroup($groupid, $newgroupname);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser7 = new Route('deleteGroup', 'groups/delete', true, true, function($user, $passw, $groupid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "REMOVE_GROUP"))
            {
                $manager = new UserManager($db);
				$manager->deleteGroup($groupid);
            }
            else {
                handleError("Access denied.");
            }           
        });
        
        $rtuser8 = new Route('listGroups', 'groups/list', true, true, function($user, $passw) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_GROUPS"))
            {
                 $manager = new UserManager($db);
				 $manager->listGroups();
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser9 = new Route('listRights', 'rights/list', true, true, function($user, $passw) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_RIGHTS"))
            {
                $manager = new UserManager($db);
            	$manager->listRights();
            }
            else {
                handleError("Access denied.");
            }
            
        });
        
        // $rtuser10 = new Route('addRight', 'rights/add', true, true, function($user, $passw, $name) {
        //     $db = createConnection();
        //     if(checkUserAndRight($db, $user, $passw, "ADD_RIGHT"))
        //     {
        //          $manager = new UserManager($db);
        //     }
        //     else {
        //         handleError("Access denied.");
        //     }
           
            
        // });
        
        // $rtuser11 = new Route('updateRight', 'rights/update', true, true, function($user, $passw, $rightid, $newname) {
        //     $db = createConnection();
        //     if(checkUserAndRight($db, $user, $passw, "UPDATE_RIGHT"))
        //     {
        //          $manager = new UserManager($db);
        //     }
        //     else {
        //         handleError("Access denied.");
        //     }
           
            
        // });
        
        // $rtuser12 = new Route('deleteRight', 'rights/delete', true, true, function($user, $passw, $rightid) {
        //     $db = createConnection();
        //     if(checkUserAndRight($db, $user, $passw, "REMOVE_RIGHT"))
        //     {
        //         $manager = new UserManager($db);
        //     }
        //     else {
        //         handleError("Access denied.");
        //     }
            
            
        // });
        
        $rtuser13 = new Route('grantRight', 'rights/grant', true, true, function($user, $passw, $groupid, $rightid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "GRANT_GROUPRIGHT"))
            {
                $manager = new UserManager($db);
				$manager->grantRight($groupid, $rightid);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
        
        $rtuser14 = new Route('revokeRight', 'rights/revoke', true, true, function($user, $passw, $groupid, $rightid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "REVOKE_GROUPRIGHT"))
            {
                 $manager = new UserManager($db);
				 $manager->revokeRight($groupid, $rightid);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
        
        $rtuser15 = new Route('listGroupUsers', 'groups/listusers', true, true, function($user, $passw, $groupid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_GROUPMEMBERS"))
            {
                 $manager = new UserManager($db);
				 $manager->listGroupUsers($groupid);
            }
            else {
               handleError("Access denied.");
            }
           
            
        });
        
        $rtuser16 = new Route('listGroupRights', 'groups/listrights', true, true, function($user, $passw, $groupid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_GROUPRIGHTS") && checkUserAndRight($db, $user, $passw, "VIEW_RIGHTS"))
            {
                $manager = new UserManager($db);
				$manager->listGroupRights($groupid);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
        
        $rtuser17 = new Route('listUserGroups', 'users/listgroups', true, true, function($user, $passw, $userid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_GROUPS"))
            {
                $manager = new UserManager($db);
				$manager->listUserGroups($userid);
            }
            else {
                handleError("Access denied.");
            }
            
            
        });
        
        $rtuser18 = new Route('filterUsers', 'users/filter', true, true, function($user, $passw, $filter) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_USERS"))
            {
                $manager = new UserManager($db);
				$manager->filterUsers($filter);
            }
            else {
               handleError("Access denied.");
            }
            
            
        });
		
		$rtuser19 = new Route('getUser', 'users/get', true, true, function($user, $passw, $userid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_USERS"))
            {
                 $manager = new UserManager($db);
				 $manager->getUser($userid);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
		
		$rtuser20 = new Route('addUserToGroup', 'users/addtogroup', true, true, function($user, $passw, $userid, $groupid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_USERS"))
            {
                 $manager = new UserManager($db);
				 $manager->addUserToGroup($userid, $groupid);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
		
		$rtuser21 = new Route('removeUserFromGroup', 'users/removefromgroup', true, true, function($user, $passw, $userid, $groupid) {
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passw, "VIEW_USERS"))
            {
                 $manager = new UserManager($db);
				 $manager->removeUserFromGroup($userid, $groupid);
            }
            else {
                handleError("Access denied.");
            }
           
            
        });
		
		$rtuser22 = new Route("validateUser", 'users/validate', true, true, function($user, $passw){
			$db = createConnection();
			$manager = new UserManager($db);
			$manager->validateUser($user, $passw);
		});
        
        $router->pushRoute($rtuser1);
        $router->pushRoute($rtuser2);
        $router->pushRoute($rtuser3);
        $router->pushRoute($rtuser4);
        $router->pushRoute($rtuser5);
        $router->pushRoute($rtuser6);
        $router->pushRoute($rtuser7);
        $router->pushRoute($rtuser8);
        $router->pushRoute($rtuser9);
        // $router->pushRoute($rtuser10);
        // $router->pushRoute($rtuser11);
        // $router->pushRoute($rtuser12);
        $router->pushRoute($rtuser13);
        $router->pushRoute($rtuser14);
        $router->pushRoute($rtuser15);
        $router->pushRoute($rtuser16);
        $router->pushRoute($rtuser17);
		$router->pushRoute($rtuser18);
		$router->pushRoute($rtuser19);
		$router->pushRoute($rtuser20);
		$router->pushRoute($rtuser21);
		$router->pushRoute($rtuser22);
        
        // tags
        
        $rttag1 = new Route("addTag", "tags/add", true, true, function($user, $passwd, $tagname){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "ADD_TAG"))
            {
                 $manager = new DescDataManager($db);
                 $manager->addTag($tagname);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $rttag2 = new Route("updateTag", "tags/update", true, true, function($user, $passwd, $tagid, $tagname){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_TAG"))
            {
                $manager = new DescDataManager($db);
                $manager->updateTag($tagid, $tagname);
            }
            else {
               handleError("Access denied.");
            }
            
        });   
        
        $rttag3 = new Route("removeTag", "tags/remove", true, true, function($user, $passwd, $tagid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "REMOVE_TAG"))
            {
                  $manager = new DescDataManager($db);
                  $manager -> removeTag($tagid);
            }
            else {
                handleError("Access denied.");
            }
          
        });   
        
        $rttag4 = new Route("viewTags", "tags/all", true, true, function($user, $passwd){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_TAGS"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> allTag();
            }
            else {
                handleError("Access denied.");
            }
           
        });   
        
        $rttag5 = new Route("getTag", "tags/get", true, true, function($user, $passwd, $tagid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_TAGS"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> getTagData($tagid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $rttag6 = new Route("addTagToSnippet", "tags/addtosnippet", true, true, function($user, $passwd, $snippetid, $tagid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_SNIPPET"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> addTagToSnippet($snippetid, $tagid);
            }
            else {
               handleError("Access denied.");
            }
           
        });  
        
        $rttag7 = new Route("removeTagFromSnippet", "tags/removefromsnippet", true, true, function($user, $passwd, $snippetid,  $tagid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_SNIPPET"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> removeTagFromSnippet($snippetid, $tagid);
            }
            
            
            else {
                handleError("Access denied.");
            }
           
        });  
        
        $rttag8 = new Route("addTagToArticle", "tags/addtoarticle", true, true, function($user, $passwd, $articleid, $tagid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_ARTICLE"))
            {
                $manager = new DescDataManager($db);
                $manager -> addTagToArticle($articleid, $tagid);
            }
            else {
               handleError("Access denied.");
            }
            
        });  
        
        $rttag9 = new Route("removeTagFromArticle", "tags/removefromarticle", true, true, function($user, $passwd, $articleid, $tagid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_ARTICLE"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> removeTagFromArticle($articleid, $tagid);
            }
            else {
               handleError("Access denied.");
            }
           
        });
		
		$rttag10 = new Route("getArticleTags", "tags/byarticle", true, true, function($user, $passwd, $articleid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_TAGS"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> getArticleTags($articleid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
		
		$rttag11 = new Route("getSnippetTags", "tags/bysnippet", true, true, function($user, $passwd, $snippetid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_TAGS"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> getSnippetTags($snippetid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        
        $router->pushRoute($rttag1);
        $router->pushRoute($rttag2);
        $router->pushRoute($rttag3);
        $router->pushRoute($rttag4);
        $router->pushRoute($rttag5);
        $router->pushRoute($rttag6);
        $router->pushRoute($rttag7);
        $router->pushRoute($rttag8);
        $router->pushRoute($rttag9);
		$router->pushRoute($rttag10);
		$router->pushRoute($rttag11);
        
        
        // catgories
        
        $rtcat1 = new Route("addCategory", "categories/add", true, true, function($user, $passwd, $name, $description){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "ADD_CATEGORY"))
            {
                $manager = new DescDataManager($db);
				$manager->addCategory($name, $description);
            }
            else {
                handleError("Access denied.");
            }
            
        });
        
        $rtcat2 = new Route("updateCategory", "categories/update", true, true, function($user, $passwd, $categoryid, $name, $description){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_CATEGORY"))
            {
                $manager = new DescDataManager($db);
				$manager->updateCategory($categoryid, $name, $description);
            }
            else {
                handleError("Access denied.");
            }
            
        });   
        
        $rtcat3 = new Route("removeCategory", "categories/remove", true, true, function($user, $passwd, $categoryid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "REMOVE_CATEGORY"))
            {
                 $manager = new DescDataManager($db);
				 $manager->removeCategory($categoryid);
            }
            else {
                handleError("Access denied.");
            }
           
        });   
        
        $rtcat4 = new Route("viewCategories", "categories/all", true, true, function($user, $passwd){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_CATEGORIES"))
            {
                $manager = new DescDataManager($db);
				$manager->getCategories();
            }
            else {
                handleError("Access denied.");
            }
            
        });   
        
        $rtcat5 = new Route("getCategory", "categories/get", true, true, function($user, $passwd, $categoryid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_CATEGORIES"))
            {
                 $manager = new DescDataManager($db);
				 $manager->getCategory($categoryid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $rtcat6 = new Route("addCategoryToSnippet", "categories/addtosnippet", true, true, function($user, $passwd, $snippetid, $categoryid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_SNIPPET"))
            {
                 $manager = new DescDataManager($db);
				 $manager->addCategoryToSnippet($snippetid, $categoryid);
            }
            else {
                handleError("Access denied.");
            }
           
        });  
        
        $rtcat7 = new Route("removeCategoryFromSnippet", "categories/removefromsnippet", true, true, function($user, $passwd, $snippetid, $categoryid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_SNIPPET"))
            {
                  $manager = new DescDataManager($db);
				  $manager->removeCategoryFromSnippet($snippetid, $categoryid);
            }
            else {
                handleError("Access denied.");
            }
          
        });  
        
        $rtcat8 = new Route("addCategoryToArticle", "categories/addtoarticle", true, true, function($user, $passwd, $articleid, $categoryid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDAZTE_ARTICLE"))
            {
                 $manager = new DescDataManager($db);
				 $manager->addCategoryToArticle($articleid, $categoryid);
            }
            else {
                handleError("Access denied.");
            }
           
        });  
        
        $rtcat9 = new Route("removeCategoryFromArticle", "categories/removefromarticle", true, true, function($user, $passwd, $articleid, $categoryid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_ARTICLE"))
            {
                 $manager = new DescDataManager($db);
				 $manager->removeCategoryFromArticle($articleid, $categoryid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
		
		$rtcat10 = new Route("getArticleCategories", "categories/byarticle", true, true, function($user, $passwd, $articleid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_CATEGORIES"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> getArticleCategories($articleid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
		
		$rtcat11 = new Route("getSnippetCategories", "categories/bysnippet", true, true, function($user, $passwd, $snippetid){
            $db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_CATEGORIES"))
            {
                 $manager = new DescDataManager($db);
                 $manager -> getSnippetCategories($snippetid);
            }
            else {
                handleError("Access denied.");
            }
           
        });
        
        $router->pushRoute($rtcat1);
        $router->pushRoute($rtcat2);
        $router->pushRoute($rtcat3);
        $router->pushRoute($rtcat4);
        $router->pushRoute($rtcat5);
        $router->pushRoute($rtcat6);
        $router->pushRoute($rtcat7);
        $router->pushRoute($rtcat8);
        $router->pushRoute($rtcat9);
		$router->pushRoute($rtcat10);
		$router->pushRoute($rtcat11);
		
		//ides, laguages
		
		$idert1 = new Route("viewIDEs", "ides/all", true, true, function($user, $passwd){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_IDES"))
            {
                 $manager = new DescDataManager($db);
                 $manager->getIDEs();
            }
            else {
                handleError("Access denied.");
            }
		});  
		
		$idert2 = new Route("getIDE", "ides/get", true, true, function($user, $passwd, $ideid){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_IDES"))
            {
                 $manager = new DescDataManager($db);
                 $manager->getIDE($ideid);
            }
            else {
                handleError("Access denied.");
            }
		});
		
		$idert3 = new Route("addIDE", "ides/add", true, true, function($user, $passwd, $name, $description){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "ADD_IDE"))
            {
                 $manager = new DescDataManager($db);
                 $manager->addIDE($name, $description);
            }
            else {
                handleError("Access denied.");
            }
		});  
		
		$idert4 = new Route("deleteIDE", "ides/delete", true, true, function($user, $passwd, $ideid){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "REMOVE_IDES"))
            {
                 $manager = new DescDataManager($db);
                 $manager->deleteIDE($ideid);
            }
            else {
                handleError("Access denied.");
            }
		});  
		
		$idert5 = new Route("updateIDE", "ides/update", true, true, function($user, $passwd, $ideid, $name, $description){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_IDE"))
            {
                 $manager = new DescDataManager($db);
                 $manager->updateIDE($ideid, $name, $description);
            }
            else {
                handleError("Access denied.");
            }
		});    
		
		$router->pushRoute($idert1);
		$router->pushRoute($idert2);
		$router->pushRoute($idert3);
		$router->pushRoute($idert4);
		$router->pushRoute($idert5);
		
		
		//languages
		$langrt1 = new Route("viewLanguages", "languages/all", true, true, function($user, $passwd){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_LANGUAGES"))
            {
                 $manager = new DescDataManager($db);
                 $manager->getLanguages();
            }
            else {
                handleError("Access denied.");
            }
		}); 
		
		$langrt2 = new Route("getLanguage", "languages/get", true, true, function($user, $passwd, $langid){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "VIEW_LANGUAGES"))
            {
                 $manager = new DescDataManager($db);
                 $manager->getLanguage($langid);
            }
            else {
                handleError("Access denied.");
            }
		}); 
		
		$langrt3 = new Route("addLanguage", "languages/add", true, true, function($user, $passwd, $name, $description){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "ADD_LANGUAGE"))
            {
                 $manager = new DescDataManager($db);
                 $manager->addLanguage($name, $description);
            }
            else {
                handleError("Access denied.");
            }
		}); 
		
		$langrt4 = new Route("deleteLanguage", "languages/delete", true, true, function($user, $passwd, $langid){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "REMOVE_LANGUAGE"))
            {
                 $manager = new DescDataManager($db);
                 $manager->deleteLanguage($langid);
            }
            else {
                handleError("Access denied.");
            }
		}); 
		
		$langrt5 = new Route("updateLanguage", "languages/update", true, true, function($user, $passwd, $langid, $name, $description){
			$db = createConnection();
            if(checkUserAndRight($db, $user, $passwd, "UPDATE_LANGUAGE"))
            {
                 $manager = new DescDataManager($db);
                 $manager->updateLanguage($langid, $name, $description);
            }
            else {
                handleError("Access denied.");
            }
		}); 
		
		$router->pushRoute($langrt1);  
		$router->pushRoute($langrt2);
		$router->pushRoute($langrt3);     
		$router->pushRoute($langrt4);     
		$router->pushRoute($langrt5);                                 
    }
?>