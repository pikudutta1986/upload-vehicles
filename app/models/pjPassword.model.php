<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPasswordModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'passwords';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'password', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'format', 'type' => 'enum', 'default' => 'ical'),
		array('name' => 'period', 'type' => 'enum', 'default' => '1')
	);
	
	public static function factory($attr=array())
	{
		return new pjPasswordModel($attr);
	}
}
?>