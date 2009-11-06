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
		$tweep->name = $name;
		$data = json_decode(@file_get_contents('https://mkais:ghazala2009@twitter.com/users/show.json?'.http_build_query(array('screen_name' => $name))));
		$tweep = new Tweep($data->id);
		return $tweep;
	}

	function load()
	{
		$data = json_decode(@file_get_contents('https://mkais:ghazala2009@twitter.com/users/show.json?'.http_build_query(array('user_id' => $this->id))));
		$this->profile_image_url = $data->profile_image_url;
		$this->name = $data->screen_name;
		$this->full_name = $data->name;
		return $this;
	}
	function load_friends()
	{
		$data = json_decode(@file_get_contents('https://mkais:ghazala2009@twitter.com/friends/ids.json?'.http_build_query(array('id' => $this->id))));
		if ($data)
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

	function suggest_friends($number = 10, $connections = 2)
	{
		$foafs = array();
		$tweeps = array();
		$friend_ids = array();
		foreach ($this->friends as $friend) {
			$friend_ids []= $friend->id;
		}
		foreach ($this->friends as $friend) {
			if ($friend->friends)
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
		while($continue >= $connections && $number > 0 && $continue !== FALSE) {
			if (key($foafs) != $this->id && !in_array(key($foafs), $friend_ids)) { 
				$tweep = new Tweep(key($foafs));
				$tweeps []= $tweep;
				$tweep->common_friends = current($foafs);
				$tweep->load();
					$number--;
			}
			$continue = prev($foafs);
		}
		return $tweeps;
	}
}
