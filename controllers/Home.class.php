<?php
class Home {
	protected	$_ac, // action to perform
				$_view, // view to call
				$_params = array() ; // the parameters of the request
	
	/* All controllers should have 
	 * those methods
	 */
	
	public function __construct($ac, array $params) {
		$this->_ac = $ac ;
		$this->_params = $params ;
	}
	
	public function getView() {
		return $this->_view ;
	}
	
	public function getData() {
		// if the action exists
		if(method_exists($this, $this->_ac))
			$action = $this->_ac ;
		else // else , display action is the default (you can change it, and create a whole error system )
			$action = "display" ;
		
		return $this->$action() ; // every method should specify the view and return the data
	}
	
	/* Specific method for the Home Controller 
	 * Every controller should have its own methods
	 * like the above
	 */
	
	// default method , we will display a list of players ! we won't need a modele to get specific data from a file / database
	public function display() {
		$this->_view = "home" ; // we should create that view !
		$data = array(
				'pagetitle' => "List Of Players",
				'players' => array(
						'player1' => 'Player 1',
						'player2' => 'Player 2')) ; // you can return anything you want/need 
		
		return $data ;
	}
	
	// the web apps we're creating will parse a json file (data) containing information about a player
	// and calculate his power according to a simple addition formula
	// the user name is passed in the url via a get request
	public function calculate() {
		
		// if the name of the player exists and is a file
		if(!empty($this->_params['gets']['player'])) {
			
			// We call the Modele of the Player
			require_once 'modeles/PlayerModele.class.php';
			$playerMod = new PlayerModele() ;
			$player = $playerMod->getPlayer($this->_params['gets']['player']) ;
			
			if($player) { // the player exists
				$power = $player['strength'] + $player['speed'] ;
				$data['message'] = "The power of <strong>".$player['name']."</strong> is <em>".$power."</em>" ;
				$data['playername'] = $player['name'] ;
				
				// the view we should create
				$this->_view = "calculate";
			} else { // we forward to display
				$data['message'] = "The request is wrong ! Please double check your request" ;
				$this->display() ; // we forward to display method
			}
			
		} else { // the name or the request is wrong, we return simple message of error
			$data['message'] = "The request is wrong ! Please double check your request" ;
			$this->display() ; // we forward to display method
		}
		
		return $data;
	}
}