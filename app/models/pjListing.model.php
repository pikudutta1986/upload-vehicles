<?php

if (!defined("ROOT_PATH"))

{

	header("HTTP/1.1 403 Forbidden");

	exit;

}

class pjListingModel extends pjAppModel

{

	protected $primaryKey = 'id';

	

	protected $table = 'listings';

	

	protected $schema = array(

		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'vin', 'type' => 'varchar', 'length' => 17, 'default' => ':NULL'),

		array('name' => 'year', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'make_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'model_id', 'type' => 'int', 'default' => ':NULL'),

		

		array('name' => 'owner_show', 'type' => 'enum', 'default' => 'T'),

		

		array('name' => 'car_type', 'type' => 'enum', 'default' => ':NULL'),

		

		array('name' => 'feature_fuel_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'feature_gearbox_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'feature_doors_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'feature_class_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'feature_seats_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'feature_type_id', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'feature_colors_id', 'type' => 'int', 'default' => ':NULL'),

		

		array('name' => 'listing_refid', 'type' => 'varchar', 'default' => ':NULL'),

		array('name' => 'listing_price', 'type' => 'decimal', 'default' => ':NULL'),

		array('name' => 'listing_month', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'listing_year', 'type' => 'year', 'default' => ':NULL'),

		array('name' => 'listing_mileage', 'type' => 'int', 'default' => ':NULL'),

		array('name' => 'listing_power', 'type' => 'int', 'default' => ':NULL'),

		

		array('name' => 'views', 'type' => 'int', 'default' => ':NULL'),

		

		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),

		array('name' => 'modified', 'type' => 'datetime', 'default' => ':NULL'),

		array('name' => 'expire', 'type' => 'date', 'default' => ':NULL'),

		

		array('name' => 'last_extend', 'type' => 'enum', 'default' => ':NULL'),

		array('name' => 'is_featured', 'type' => 'enum', 'default' => 'F'),

		

		array('name' => 'status', 'type' => 'enum', 'default' => ':NULL')

	);

	

	public $i18n = array('title', 'description', 'meta_title', 'meta_keywords', 'meta_description');

	

	public static function factory($attr=array())

	{

		return new pjListingModel($attr);

	}

}

?>