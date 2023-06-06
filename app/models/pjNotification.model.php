<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjNotificationModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'notifications';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'user_id', 'type' => 'int', 'default' => ':NULL')
	);
	
	public $i18n = array('new_account_subject', 'new_account_message', 'active_account_subject', 'active_account_message');
	
	public static function factory($attr=array())
	{
		return new pjNotificationModel($attr);
	}
}
?>