<?php        
    require_once('routing.php');
    require_once('snippetmanagement.php');
    require_once('mysqlwrapper.php');
    require_once('usermanagement.php');
    require_once('errorhandling.php');
    require_once('securitycheck.php');
    require_once('articlemanagement(beispiel from alten projekt).php');
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
        

    }
?>