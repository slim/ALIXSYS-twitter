<?php

class Tweet
{
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
		$this->time = $data->status->created_at;
	}

	function save()
	{
		$table = self::$table;
		$id = $this->id;
		$time = $this->time;
		$friend = $this->friend;
		$status = sqlite_escape_string($this->status);

		return self::$db->query("insert into $table (id, time, friend, status) values ('$id', '$time', '$friend', '$status');");
	}

	static function create_table()
	{
		$table = self::$table;
		return self::$db->query("create table if not exists $table (id primary key, ord, time, friend, status, isRead);");
	}
}
