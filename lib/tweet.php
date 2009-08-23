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
			$this->friend = $data->screen_name;
			$this->status = $data->status->text;
			$this->time = date('c', strtotime($data->status->created_at));
			$this->id = md5($this->status . $this->time);
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
		list($tweet) = self::select("where isRead <> 'yes' order by rowid");
		return $tweet;
	}

	static function load_friends($page = 1)
	{
		$data = json_decode(self::$twitter->OAuthRequest('https://twitter.com/statuses/friends.json', array('page' => $page), 'GET'));
		$tweets = array();
		foreach ($data as $t) {
			$tweet_time = strtotime($t->status->created_at);
			$tweets[$tweet_time] = new Tweet($t);
		}
		krsort($tweets);
		foreach($tweets as $tweet) {
			$tweet->save();
		}

		return $tweets;
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
