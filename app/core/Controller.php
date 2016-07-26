<?php 
/**
* This ia a Base Contrller
*/
class Controller
{
	public function __construct()
	{
		
	}

	/**
	* This function CAll Database Class
	* @return Database Class
	*/
	public function DB()
	{
		require_once '../app/config/Database.php';
		return new Database();
	}

	/**
	* This function CAll Model Class
	* @param $model
	* @return Model Class
	*/
	public function model($model)
	{
		require_once '../app/models/' .$model. '.php';
		return new $model();
	}

	/**
	* This function CAll View Class
	* @param $view
	* @param $data
	* @return View Files
	*/
	public function view($view , $data=[])
	{
		require_once '../app/views/include/header.php';
		require_once '../app/views/' .$view. '.php';
		require_once '../app/views/include/footer.php';
	}
}