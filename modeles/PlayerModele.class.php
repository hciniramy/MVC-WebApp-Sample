<?php
class PlayerModele  {
	
	// We should 
	public function getPlayer($name) {
		$json = file_get_contents('data/players.json');
		$players = json_decode($json, TRUE);
		
		if($players)
			return $players[$name] ;
		else
			return false ;
	}
}