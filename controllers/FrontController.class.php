	<?php
	class FrontController {
		
		protected	$_data, // data to display in the view
					$_view ; // the view to display
		
		// We collect all the parameters related to the useser's request
		// In this step, we can verify and secure the request parameters
		public function getParams() {
			// POST
			$posts = array() ;
			foreach ($_POST as $key => $value) {
				$posts[$key] = htmlspecialchars($value);
			}
			// GETS
			$gets = array() ;
			foreach ($_GET as $key => $value ) {
				$gets[$key] = htmlspecialchars($value) ;
			}
			
			$params = array(
					'posts' => $posts,
					'gets' => $gets);
			return $params ;
		}
		
		// According to the user's request, we return the appropriate controller to handle the request
		// The rules we used to name our controller are : "Controller".class.php ( first letter is a capital letter )
		public function getController($params) {	
			// If the user asked a controller and/or an action
			if(!empty($params['gets']['c']) || !empty($params['gets']['ac'])) {
				// if the controller exists
				if (is_file('controllers/'.ucfirst($params['gets']['c']).'.class.php')) {
					$c = ucfirst($params['gets']['c']) ; // Controller to call
					$ac = $params['gets']['ac'] ; // Action to do
				} else { // we forward to an error controller
					$c = "Error" ;
					$ac = "notfound" ; // call the notfound action
				}
			}else { // else, it's home !
				$c = 'Home' ; // By default, the Home Controller
				$ac = 'display' ; // by default, the display action
			}
		
			require_once 'controllers/'.$c.'.class.php' ;
			return new $c($ac, $params);
		}
		
		// Handle the request and coordinate between the Front methods 
		public function dispatch() {
			$params = $this->getParams() ;
			$controller = $this->getController($params) ;
			
			
			$this->_data = $controller->getData() ;
			$this->_view = $controller->getView() ;
		}
		
		// call the view and display it
		public function display()  {
			require_once 'libs/Smarty.class.php' ;
	
			$data = $this->_data;
			
			$tpl = new Smarty() ;
			$tpl->assign(array(
					'data' => $data)) ; // the data to send to the view
			
			$tpl->display('views/'.$this->_view.'.html'); // display the view
		}
	}