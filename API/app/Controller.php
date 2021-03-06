<?php

class Controller {

	protected $token = 'KDR8u9vuRH8i6hx8V4e6';
	protected $request;
	protected $auth = array('/question/add', '/deal/add', '/badge/add', '/user/login');

	function __construct() {

		if(!empty($_SESSION['alert'])){
			unset($_SESSION['alert']);
		}

		// automatic load model
		if(!empty($this->uses)){
			foreach($this->uses as $use){
				$this->loadModel($use);
			}
		}

		// $this->request = $this->get_header();
		$this->request = $_SERVER;

		if(!isset($this->request['HTTP_AUTH_TOKEN']) ||
			$this->request['HTTP_AUTH_TOKEN'] != $this->token){

			$url = $this->request['REQUEST_URI'];

			if(!in_array($url, $this->auth)){
				$this->send_message(array(
					'code' => '401',
					'name' => 'authentification fail',
					'message' => 'wrong token'));
				die();
			}else{
				if(!$this->sessionCheck('user') && $url != '/user/login'){
					$_SESSION['redirect']['from'] = $url;
					$this->redirect('/user/login');
				}
			}


		}
	}

	private function init($f3, $params = null){
		if(!empty($params['ok'])){
			$this->loadModel('Model');
			//$this->Model->generate_categories();
			// $this->Model->generate_questions();
			$this->Model->generate_responses();
		}
	}

	/**
	*	Redirect to page
	*	(but useless with framework features)
	*	@param string url
	**/
	public function redirect($url){
		header('Location:' . $url);
	}

	/**
	*	Retourne le tableau contenant les Headers de la requete
	*	@return Request Header
	**/
	protected function get_header(){
		if (!function_exists('apache_request_headers')) {
			function apache_request_headers() {
				foreach($_SERVER as $key=>$value) {
					if (substr($key,0,5)=="HTTP_") {
						$key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
						$out[$key]=$value;
					}else{
						$out[$key]=$value;
					}
				}
				return $out;
			}
		}else{
			return apache_request_headers();
		}
	}

	/**
	*	Load and instanciate a model
	*	@param string $name
	**/
	protected function loadModel($name){
		require_once 'models/' . $name. '.php';
		$this->$name = new $name();
	}

	/**
	*	Send JSON message
	*	@param array $message
	*	@return json
	**/
	protected function send_message($message = array()){
		header('Access-Control-Allow-Origin: *');
		header('Acces-Control-Allow-Headers: Auth-Token');
		header('Access-Control-Allow-Methods: *');
		header('Content-Type: application/json');

		echo json_encode($message['message'] = $message);
	}

	/**
	*	Print alert message
	*	@param string $type
	*	@param string $message
	**/
	protected function alert($type, $message){
		$_SESSION['alert']['type'] = 'alert ' . $type;
		$_SESSION['alert']['message'] = $message;
	}

	/**
	*	Set data in session
	*	@param string $key
	*	@param string $value
	**/
	protected function sessionSet($key, $value){
		$_SESSION[$key] = $value;
	}

	/**
	*	Check data in session with key
	*	@param string $key
	*	@return boolean
	**/
	protected function sessionCheck($key){
		return (!empty($_SESSION[$key])) ? true : false;
	}

	/**
	*	Reindex tableau d'upload
	*	@param $file_post Tableau de type $_FILES
	**/
	protected function reindexUpload($file_post) {

		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);

		for ($i=0; $i<$file_count; $i++) {
			foreach ($file_keys as $key) {
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}

		return $file_ary;
	}
}

?>