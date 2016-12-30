<?php
//  routing.php
class Route
{
    private $m_action;
    private $m_target;
    private $m_name;
    private $m_terminate;
    private $m_includepost;
	private $m_includerouter;
    
    function __construct($name, $target, $terminate, $includepost, $action)
    {
        $this->m_target = $target;
        $this->m_action = $action;
        $this->m_name = $name;
        $this->m_terminate = $terminate;
        $this->m_includepost = $includepost;
		$this->m_includerouter = false;
    }
    
    function __destruct()
    {
        unset($this->m_action);
        unset($this->m_target);
        unset($this->m_name);
        unset($this->m_terminate);
        unset($this->m_includepost);
		unset($this->m_includerouter);
    }
    
    //getters / setters
	public function getIncludeRouter()
	{
		return $this->m_includerouter;
	}
	
	public function setIncludeRouter($inc)
	{
		$this->m_includerouter = $inc;
	}
    
    public function getAction()
    {
        return $this->m_action;
    }
    
    public function getTarget()
    {
        return $this->m_target;
    }
    
    public function getName()
    {
        return $this->m_name;
    }
    
    public function isTerminating()
    {
        return $this->m_terminate;
    }
    
    public function includePost()
    {
        return $this->m_includepost;
    }
    
    public function setAction($action)
    {
        $this->m_action = $action;
    }
    
    public function setTarget($target)
    {
        $this->m_target = $target;
    }
    
    public function setName($name)
    {
        $this->m_name = $name;
    }
    
    public function setTerminate($terminate)
    {
        $this->m_terminate = $terminate;
    }
    
    public function setIncludePost($includepost)
    {
        $this->m_includepost = $includepost;
    }
    
    public function dispatch($params)
    {
       call_user_func_array($this->m_action, $params);       
    }
	
	public function printrt()
	{
		echo("<tr style=\"border-top: 2pt solid darkgray\">\n");
			echo("<td style=\"vertical-align:top\">" . $this->m_name . "</td>\n");
			echo("<td style=\"vertical-align:top\">" . $this->m_target . "</td>\n");
			
			$func = new ReflectionFunction($this->getAction());
			echo("<td style=\"vertical-align:top\">");
			$pc = 1;
			foreach($func->getParameters() as $arg)
			{
				echo($pc++ . ": " . $arg->getName() . "<br>\n");
			}
			echo("</td>\n");			
						
			echo("<td style=\"vertical-align:top\">" . ($this->m_includepost ? 'true' : 'false') . "</td>\n");
			echo("<td style=\"vertical-align:top\">" . ($this->m_terminate ? 'true' : 'false') . "</td>\n");
			echo("<td style=\"vertical-align:top\">" . ($this->m_includerouter ? 'true' : 'false') . "</td>\n");			
		echo("</tr>\n");
	}
}

class Router
{
    private $m_routes = array();
    private $m_404Route;
    private $m_basepath;
    
    function __construct($basepath)
    {
        $this->m_basepath = $basepath;
        
        //standard 404 route
        $this->m_404Route = new Route('404', 'routenotfound', true, false, function()
        {
            echo 'ERROR: The route you requested does not exist!';
        });
    }
    
    function __destruct()
    {
        unset($m_routes);
    }
    
    public function pushRoute($route)
    {
        array_push($this->m_routes, $route);
    }
    
    public function set404Route($route)
    {
        $this->m_404Route = $route;
    }
    
    public function popRoute()
    {
        return array_pop($m_routes);
    }
	
	public function printRoutes()
	{		
		echo("<h2>Router status:</h2>\n");
		echo("<h3>Basepath: " . $this->m_basepath . "</h3>\n");
		echo("<h3>Number of routes: " . sizeof($this->m_routes) . "</h3>");
		echo("<table style=\"border-collapse:collapse\">\n");
			echo("<tr>\n");
				echo("<th style=\"text-align:left\">Name</th>");
				echo("<th style=\"text-align:left\">Target</th>");
				echo("<th style=\"text-align:left\">Params</th>");
				echo("<th style=\"text-align:left\">Includes POST</th>");
				echo("<th style=\"text-align:left\">Is terminating</th>");
				echo("<th style=\"text-align:left\">Includes router</th>");
			echo("</tr>\n");
			$this->m_404Route->printrt();
			foreach($this->m_routes as $route)
			{
				$route->printrt();
			}
		echo("</table>\n");
	}
    
    public function route($uri)
    {
        $routefound = FALSE;
        foreach ($this->m_routes as $route)
        {
            $routeregex = '^' . (strlen($this->m_basepath) > 0 ? ('(' . trim($this->m_basepath, '/') . ')/') : '') . trim($route->getTarget(), '/') . '$';
            
            if(preg_match('#' . $routeregex . '#', trim($uri, '/'), $matches))
            { 
                array_shift($matches);                
                if(strlen($this->m_basepath) > 0)
                {
                    array_shift($matches);
                }                    
                $func = new ReflectionFunction($route->getAction());
                $targetparamnum = $func->getNumberOfRequiredParameters();
                
				$params = array();
				
				if($route->getIncludeRouter())
				{
					array_push($params, $this);
				}
				
				$params = array_merge($params, $matches);
                
				if($route->includePost())
                {
                    $params = array_merge($params, $_POST);
                }
                if(sizeof($params) != $targetparamnum)
                {
                    continue;
                }
                else
                {                
                    $routefound = TRUE;                            
                    $route->dispatch($params);
                    if($route->isTerminating())
                        break;  
                }     
            }            
        }
        
        if($routefound === FALSE)
        {
            $arr = array();
            $this->m_404Route->dispatch($arr);
        }
    }   
}
?>