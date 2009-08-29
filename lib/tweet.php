<?php

class Tweet
{
	static $twitter;
	static $db;
	static $table;

	public $id;
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

	function save()
	{
		$table = self::$table;
		$id = $this->id;
		$time = $this->time;
		$friend = $this->friend;
		$status = sqlite_escape_string($this->status);

		return self::$db->query("insert into $table (id, time, friend, status, isRead) values ('$id', '$time', '$friend', '$status', 'no');");
	}

	function mark_as_read()
	{
		$table = self::$table;
		$id = $this->id;
		$query = "update $table set isRead='yes' where id='$id';";
		return self::$db->query($query);
	}

	static function mark_all_as_read()
	{
		$table = self::$table;
		$query = "update $table set isRead='yes';";
		return self::$db->query($query);
	}

	static function select($options)
	{
		$tweets = array();
		$table = self::$table;

		$result = self::$db->query("select * from $table $options;");
		foreach ($result as $row) {
			$t = new Tweet;
			$t->id = $row['id'];
			$t->time = $row['time'];
			$t->friend = $row['friend'];
			$t->status = $row['status'];
			array_push($tweets, $t);
		}

		return $tweets;
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
		$table = self::$table;
		return self::$db->query("create table if not exists $table (id primary key, time, friend, status, isRead);");
	}
}
