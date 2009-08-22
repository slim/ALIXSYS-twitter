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

	function __construct($data)
	{
		$this->friend = $data->screen_name;
		$this->status = $data->status->text;
		$this->id = $data->id . $data->status->id;
		$this->time = date('c', strtotime($data->status->created_at));
	}

	function save()
	{
		$table = self::$table;
		$id = $this->id;
		$time = $this->time;
		$friend = $this->friend;
		$status = sqlite_escape_string($this->status);

		return self::$db->query("insert into $table (id, time, friend, status, isRead) values ('$id', '$time', '$friend', '$status', 'yes');");
	}

	static function load_friends()
	{
		$data = json_decode(self::$twitter->OAuthRequest('https://twitter.com/statuses/friends.json', NULL, 'GET'));
		$expire_time = time() - (24*60*60);
		$tweets = array();
		foreach ($data as $t) {
			$tweet_time = strtotime($t->status->created_at);
			if ($tweet_time > $expire_time) {
				$tweets[$tweet_time] = new Tweet($t);
			}
		}
		ksort($tweets);

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
