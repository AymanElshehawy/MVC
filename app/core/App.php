<?php
/**
*  This is important class to redirct the URL
*  The main function of URL after public/Controller/Function/Params
*/
class App
{
	//This is a default
	protected $controller = 'home';
	protected $method     = 'index';
	protected $params     = [];

	/**
	* this function call parseUrl()
	*/
	public function __construct()
	{
		//Here Call parseUrl() To divide URL in array 
		$url = $this->parseUrl();
		//check if this file in controller folder is exist
		if(file_exists('../app/controllers/' .$url[0].'.php'))
		{
			//put in controller var
			$this->controller = $url[0];
			//delete from url array
			unset($url[0]);
		}

		//Here include this controller
		require_once '../app/controllers/'.$this->controller.'.php';
		//Here Call Class
		$this->controller = new $this->controller;
		//Here if there is a function
		if(isset($url[1]))
		{
			//check if this function exist in the controller
			if(method_exists($this->controller, $url[1]))
			{
				//put in method var
				$this->method = $url[1];
				//delete from url array
				unset($url[1]);
			}
			//check if there is values in url array and put it params var
			$this->params = $url ? array_values($url) : [];
			//Here call a function that in this controller class
			//Go to Controller Folder and function that in 
			call_user_func_array([$this->controller, $this->method], $this->params);
		}
	}

	/**
	* This function divide the URL
	* @return $url array
	*/
	public function parseUrl()
	{
		//Get the URL
		if(isset($_GET['url']))
		{
			//Rigth trim to delete / and filter the varaible if URL then return an array of URL
			return explode('/', filter_var(rtrim($_GET['url'],'/'), FILTER_SANITIZE_URL));
		}
	}
}