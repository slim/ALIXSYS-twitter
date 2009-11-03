<?php

class Tweep
{
	public $id;
	public $name;
	public $friends;
	public $followers;


	function __construct($id)
	{
		$this->id = $id;
	}

	static function byName($name)
	{
		$tweep = new Tweep($name);
		$tweep->name = $name;
		return $tweep;
	}

	function load_friends()
	{
		$data = json_decode(file_get_contents('https://twitter.com/friends/ids.json?'.http_build_query(array('id' => $this->id))));
		foreach ($data as $twid) {
			$friend = new Tweep($twid);
			$this->add_friend($friend);
		}
		return $this;
	}

	function add_friend($tweep)
	{
		$this->friends []= $tweep;
		return $this;
	}

	function suggest_friends($number = 10)
	{
		$foafs = array();
		$tweeps = array();
		$friend_ids = array();
		foreach ($this->friends as $friend) {
			$friend_ids []= $friend->id;
		}
		foreach ($this->friends as $friend) {
			foreach ($friend->friends as $foaf) {
				if ($foafs[$foaf->id] > 0) {
					$foafs[$foaf->id]++;
				}
				else {
					$foafs[$foaf->id] = 1;
				}
			}
		}
		natsort($foafs);
		$continue = end($foafs);
		while($continue > 1 && $number > 0 && $continue !== FALSE) {
			if (!in_array(key($foafs), $friend_ids)) { 
				$tweeps []= new Tweep(key($foafs));
			}
			$continue = prev($foafs);
			$number--;
		}
		return $tweeps;
	}
}
