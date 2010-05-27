<?php

class Tweet
{
	static $twitter;
	static $db;
	static $table;

	public $id;
	public $position;
	public $time;
	public $friend;
	public $status;

	function __construct($data = NULL)
	{
		if ($data) {
			if ($data->user) {
				$this->friend = $data->user->screen_name;
				$this->status = $data->text;
				$this->time = date('c', strtotime($data->created_at));
				$this->id = md5($this->status . $this->time);
			}
			else {
				$this->friend = $data->screen_name;
				$this->status = $data->status->text;
				$this->time = date('c', strtotime($data->status->created_at));
				$this->id = md5($this->status . $this->time);
			}
		}
	}

	static function fromSearch($data) {
		$tweets = array();
		foreach ($data->results as $r) {
			$t = new Tweet;
			$t->id = $r->id;
			$t->time = date('c', strtotime($r->created_at));
			$t->status = $r->text;
			$t->friend = $r->from_user;
			$tweets []= $t;
		}

		return $tweets;
	}

	function reply($text) {
		$update = array('status' => '@'.$this->friend.' '.$text);
		$update['in_reply_to_status_id'] = $this->id;
		self::$twitter->OAuthRequest('https://twitter.com/statuses/update.xml', $update, 'POST');
	}

	function save()
	{
		$req = self::$db->prepare("insert into :table (id, time, friend, status, isRead) values (:id, :time, :friend, :status, 'no')");
		$req->bindValue(':table',self::$table);
		$req->bindValue(':id',$this->id);
		$req->bindValue(':time',$this->time);
		$req->bindValue(':friend',$this->friend);
		$req->bindValue(':status',sqlite_escape_string($this->status));

		return $req->execute();
	}

	function mark_as_read()
	{
		$query = "update :table set isRead='yes' where id=:id;";
		$req = self::$db->prepare($query);
		$req->bindValue(':table',self::$table);
		$req->bindValue(':id',$this->id);

		return $req->execute();
	}

	static function mark_all_as_read()
	{
		$query = "update :table set isRead='yes';";
		$req = self::$db->prepare($query);
		$req->bindValue(':table',self::$table);
		$req->bindValue(':id',$this->id);

		return $req->execute();
	}

	static function sql_select($options)
	{
		$table = self::$table;
		return "select rowid, * from $table $options";
	}

	static function select($options)
	{
		$tweets = array();

		$query = self::sql_select($options);
		if (FALSE !== strpos($query, ';')) throw Exception("Yezzi, e7chem!");
		$result = self::$db->query($query);
		foreach ($result as $row) {
			$t = new Tweet;
			$t->id = $row['id'];
			$t->position = $row['rowid'];
			$t->time = $row['time'];
			$t->friend = $row['friend'];
			$t->status = $row['status'];
			array_push($tweets, $t);
		}

		return $tweets;
	}

	static function byPosition($pos)
	{
		list($tweet) = self::select("where rowid=$pos limit 1");
		return $tweet;
	}

	static function last()
	{
		list($tweet) = self::select("order by rowid desc limit 1");
		return $tweet;
	}
	static function last_read()
	{
		list($tweet) = self::select("where isRead = 'yes' order by rowid desc limit 1");
		return $tweet;
	}

	static function first_unread()
	{
		list($tweet) = self::select("where isRead <> 'yes' order by rowid limit 1");
		return $tweet;
	}

	static function last_by_time()
	{
		list($tweet) = self::select("order by time desc limit 1");
		return $tweet;
	}

	static function load($data, $expiration = NULL)
	{
		if (!$data) return array();
		$tweets = array();
		foreach ($data as $t) {
			$tweet = new Tweet($t);
			$tweet_time = strtotime($tweet->time);
			if ($tweet_time > strtotime($expiration)) {
				$tweets[$tweet_time] = $tweet;
			}
		}
		krsort($tweets);
		foreach($tweets as $tweet) {
			$tweet->save();
		}

		return $tweets;
	}

	static function load_friends($page = 1)
	{
		$data = json_decode(self::$twitter->OAuthRequest('https://twitter.com/statuses/friends.json', array('page' => $page), 'GET'));
		return Tweet::load($data);
	}

	static function load_replies()
	{
		$last_tweet = self::last_by_time();
		$data = json_decode(self::$twitter->OAuthRequest('https://twitter.com/statuses/mentions.json', array(), 'GET'));
		if (!$data) {
			return NULL;
		}
		return Tweet::load($data, $last_tweet->time);
	}

	static function load_user($tweep)
	{
		$data = json_decode(self::$twitter->OAuthRequest('https://twitter.com/statuses/user_timeline.json', array('screen_name' => $tweep), 'GET'));
		return Tweet::load($data);
	}

	static function user_identity()
	{
		return json_decode(self::$twitter->OAuthRequest('http://twitter.com/account/verify_credentials.json', NULL, 'GET'));
	}

	static function create_table()
	{
		$req = self::$db->prepare("create table if not exists :table (id primary key, time, friend, status, isRead)");
		$req->bindValue(':table',self::$table);

		return $req->execute();
	}
}
