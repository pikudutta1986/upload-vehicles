<?php
if (!defined("ROOT_PATH"))

{

	header("HTTP/1.1 403 Forbidden");

	exit;

}

class pjListings extends pjFront

{

	

	private $isoDatePattern = '/\d{4}-\d{2}-\d{2}/';

	

	public function pjActionIndex()

	{

		pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=".$_GET['controller']."&action=pjActionCars");

		if ($this->option_arr['o_seo_url'] == 'No')

		{

			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=".$_GET['controller']."&action=pjActionCars");

		}else{

			pjUtil::redirect($path . '/index.html');

		}

	}

	public function pjActionFeatured()

	{

		$this->set('arr', $this->getFeaturedCars($this->option_arr, $this->getLocaleId()));

	}

	public function getFeaturedCars($option_arr, $locale_id)

	{

		$pjListingModel = pjListingModel::factory();

		$pjListingModel->where('t1.is_featured', 'T');

		$pjListingModel->where("(t1.status = 'T' OR (t1.status = 'E' AND t1.expire >= CURDATE()))");

		

		$sorting = 't1.created DESC';

		$cnt = $pjListingModel->findCount()->getData();

		if($cnt > 6)

		{

			$sorting = 'RAND()';

		}

		$pjListingModel->limit(6, 0);

	

		$sub_query = "(	SELECT GROUP_CONCAT(TM.content SEPARATOR ', ') FROM " . pjListingExtraModel::factory()->getTable() . " AS TLE

							LEFT OUTER JOIN " . pjExtraModel::factory()->getTable() . " AS TE ON TE.id=TLE.extra_id AND TE.status='T'

							LEFT OUTER JOIN " . pjMultiLangModel::factory()->getTable() . " AS TM ON TM.foreign_id = TE.id AND TM.model = 'pjExtra' AND TM.locale = '".$this->getLocaleId()."' AND TM.field = 'name'

							WHERE TLE.listing_id=t1.id) as extras";

		$arr = $pjListingModel

			->select(sprintf("t1.id, t1.listing_refid, t3.content AS listing_title, t4.content AS listing_description, t1.listing_month, t1.listing_year, t1.listing_mileage, t1.expire, t1.status, t1.owner_id, t1.listing_price, t6.content as model, t8.content as make,

				(SELECT `small_path` FROM `%s` WHERE foreign_id = t1.id ORDER BY `sort` ASC LIMIT 1) AS `image`,

				(SELECT `medium_path` FROM `%s` WHERE foreign_id = t1.id ORDER BY `sort` ASC LIMIT 1) AS `medium_image`,

				$sub_query", pjGalleryModel::factory()->getTable(), pjGalleryModel::factory()->getTable()))

			->join('pjUser', 't2.id=t1.owner_id', 'left outer')

			->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left')

			->join('pjCarMod', "t5.id=t1.model_id", 'left')

			->join('pjMultiLang', "t6.model='pjCarMod' AND t6.foreign_id=t5.id AND t6.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMake', "t7.id=t1.make_id", 'left')

			->join('pjMultiLang', "t8.model='pjMake' AND t8.foreign_id=t7.id AND t8.locale='".$this->getLocaleId()."'", 'left')

		->orderBy($sorting)

		->findAll()

		->getData();

	

		return $arr;

	}

	

	public function pjActionCars()

	{

		$pjListingModel = pjListingModel::factory()

			->join('pjUser', 't2.id=t1.owner_id', 'left outer')

			->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left')

			->join('pjCarMod', "t5.id=t1.model_id", 'left')

			->join('pjMultiLang', "t6.model='pjCarMod' AND t6.foreign_id=t5.id AND t6.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMake', "t7.id=t1.make_id", 'left')

			->join('pjMultiLang', "t8.model='pjMake' AND t8.foreign_id=t7.id AND t8.locale='".$this->getLocaleId()."'", 'left');

			

		// $pjListingModel->where("t1.owner_id='". $_GET['id'] ."' AND (t1.status = 'T' OR (t1.status = 'E' AND t1.expire >= CURDATE()))");
		if(isset($_GET['id']) && $_GET['id'] != '') {
			$pjListingModel->where("t1.owner_id='". $_GET['id'] ."'");
		}
		

		

		$model_arr = array();

		

		if(isset($_GET['listing_search']))

		{

			if (isset($_GET['listing_refid']) && !empty($_GET['listing_refid']))

			{

				$q = pjObject::escapeString($_GET['listing_refid']);

				$pjListingModel->where('t1.listing_refid LIKE', "%$q%");

			}

			if (isset($_GET['make_id']) && !empty($_GET['make_id']))

			{

				$pjListingModel->where('t1.make_id', pjObject::escapeString($_GET['make_id']));

				

				$model_arr = pjCarModModel::factory()->select('t1.*, t2.content AS name')

						->join('pjMultiLang', "t2.model='pjCarMod' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'")

						->where('t1.status', 'T')

						->where('make_id', pjObject::escapeString($_GET['make_id']))

						->orderBy('name ASC')

						->findAll()

						->getData();

			}

			if (isset($_GET['model_id']) && !empty($_GET['model_id']))

			{

				$pjListingModel->where('t1.model_id', pjObject::escapeString($_GET['model_id']));

			}

			if (isset($_GET['year_from']) && $_GET['year_from'] != '' && isset($_GET['year_to']) && $_GET['year_to'] != '')

			{

				$pjListingModel->where('t1.listing_year >=', pjObject::escapeString($_GET['year_from']));

				$pjListingModel->where('t1.listing_year <=', pjObject::escapeString($_GET['year_to']));

			} else {

				if (isset($_GET['year_from']) && $_GET['year_from'] != '')

				{

					$pjListingModel->where('t1.listing_year >=', pjObject::escapeString($_GET['year_from']));

				} else if (isset($_GET['year_to']) && $_GET['year_to'] != '') {

					$pjListingModel->where('t1.listing_year <=', pjObject::escapeString($_GET['year_to']));

				}			

			}

			if (isset($_GET['mileage_from']) && $_GET['mileage_from'] != '' && isset($_GET['mileage_to']) && $_GET['mileage_to'] != '')

			{

				$pjListingModel->where('t1.listing_mileage >=', pjObject::escapeString($_GET['mileage_from']));

				$pjListingModel->where('t1.listing_mileage <=', pjObject::escapeString($_GET['mileage_to']));

			} else {

				if (isset($_GET['mileage_from']) && $_GET['mileage_from'] != '')

				{

					$pjListingModel->where('t1.listing_mileage >=', pjObject::escapeString($_GET['mileage_from']));

				} else if (isset($_GET['mileage_to']) && $_GET['mileage_to'] != '') {

					$pjListingModel->where('t1.listing_mileage <=', pjObject::escapeString($_GET['mileage_to']));

				}			

			}

			if (isset($_GET['power_from']) && $_GET['power_from'] != '' && isset($_GET['power_to']) && $_GET['power_to'] != '')

			{

				$pjListingModel->where('t1.listing_power >=', pjObject::escapeString($_GET['power_from']));

				$pjListingModel->where('t1.listing_power <=', pjObject::escapeString($_GET['power_to']));

			} else {

				if (isset($_GET['power_from']) && $_GET['power_from'] != '')

				{

					$pjListingModel->where('t1.listing_power >=', pjObject::escapeString($_GET['power_from']));

				} else if (isset($_GET['power_to']) && $_GET['power_to'] != '') {

					$pjListingModel->where('t1.listing_power <=', pjObject::escapeString($_GET['power_to']));

				}			

			}

			if (isset($_GET['price_from']) && (int) $_GET['price_from'] > 0 && isset($_GET['price_to']) && (int) $_GET['price_to'] > 0)

			{

				$pjListingModel->where('t1.listing_price >=', pjObject::escapeString($_GET['price_from']));

				$pjListingModel->where('t1.listing_price <=', pjObject::escapeString($_GET['price_to']));

			} else {

				if (isset($_GET['price_from']) && (int) $_GET['price_from'] > 0)

				{

					$pjListingModel->where('t1.listing_price >=', pjObject::escapeString($_GET['price_from']));

				} else if (isset($_GET['price_to']) && (int) $_GET['price_to'] > 0) {

					$pjListingModel->where('t1.listing_price <=', pjObject::escapeString($_GET['price_to']));

				}			

			}

			if (isset($_GET['feature_gearbox_id']) && !empty($_GET['feature_gearbox_id']))

			{

				$pjListingModel->where('t1.feature_gearbox_id', pjObject::escapeString($_GET['feature_gearbox_id']));

			}

			if (isset($_GET['feature_fuel_id']) && !empty($_GET['feature_fuel_id']))

			{

				$pjListingModel->where('t1.feature_fuel_id', pjObject::escapeString($_GET['feature_fuel_id']));

			}

			if (isset($_GET['feature_seats_id']) && !empty($_GET['feature_seats_id']))

			{

				$pjListingModel->where('t1.feature_seats_id', pjObject::escapeString($_GET['feature_seats_id']));

			}

			if (isset($_GET['feature_doors_id']) && !empty($_GET['feature_doors_id']))

			{

				$pjListingModel->where('t1.feature_doors_id', pjObject::escapeString($_GET['feature_doors_id']));

			}

			if (isset($_GET['feature_class_id']) && !empty($_GET['feature_class_id']))

			{

				$pjListingModel->where('t1.feature_class_id', pjObject::escapeString($_GET['feature_class_id']));

			}

			if (isset($_GET['feature_type_id']) && !empty($_GET['feature_type_id']))

			{

				$pjListingModel->where('t1.feature_type_id', pjObject::escapeString($_GET['feature_type_id']));

			}

			if (isset($_GET['feature_colors_id']) && !empty($_GET['feature_colors_id']))

			{

				$pjListingModel->where('t1.feature_colors_id', pjObject::escapeString($_GET['feature_colors_id']));

			}

			if (isset($_GET['car_type']) && !empty($_GET['car_type']))

			{

				if(strpos($_GET['car_type'], 'used') === false)

				{

					$pjListingModel->where("t1.car_type", "new");	

				}else if(strpos($_GET['car_type'], 'new') === false){

					$pjListingModel->where("t1.car_type", "used");

				}else{

					$pjListingModel->where("(t1.car_type = 'new' OR t1.car_type ='used')");

				}

			}

			

		}

		$orderby = 't1.is_featured DESC, t1.created DESC';

		if(isset($_GET['sortby']))

		{

			$sortby = pjObject::escapeString($_GET['sortby']);

			$direction = pjObject::escapeString($_GET['direction']);

			if(in_array($sortby, array('listing_price', 'listing_year', 'listing_mileage')) && in_array($direction, array('asc', 'desc')))

			{

				$orderby = $sortby . ' ' . $direction;

			}

		}

				

		$total = $pjListingModel->findCount()->getData();

		$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : $this->option_arr['o_items_per_page'];

		$pages = ceil($total / $rowCount);

		$page = isset($_GET['pjPage']) && (int) $_GET['pjPage'] > 0 ? intval($_GET['pjPage']) : 1;

		$offset = ((int) $page - 1) * $rowCount;

		if ($page > $pages)

		{

			$page = $pages;

		}

		

		$sub_query = "(	SELECT GROUP_CONCAT(TM.content SEPARATOR ', ') FROM " . pjListingExtraModel::factory()->getTable() . " AS TLE

							LEFT OUTER JOIN " . pjExtraModel::factory()->getTable() . " AS TE ON TE.id=TLE.extra_id AND TE.status='T'

							LEFT OUTER JOIN " . pjMultiLangModel::factory()->getTable() . " AS TM ON TM.foreign_id = TE.id AND TM.model = 'pjExtra' AND TM.locale = '".$this->getLocaleId()."' AND TM.field = 'name'

							WHERE TLE.listing_id=t1.id) as extras";

		

		$arr = $pjListingModel->select(sprintf("t1.id, t1.listing_refid, t3.content AS listing_title, t4.content AS listing_description, t1.listing_month, t1.listing_year, t1.listing_mileage, t1.expire, t1.status, t1.owner_id, t1.listing_price, t6.content as model, t8.content as make, 

				(SELECT `small_path` FROM `%s` WHERE foreign_id = t1.id ORDER BY `sort` ASC LIMIT 1) AS `image`, 

				(SELECT `medium_path` FROM `%s` WHERE foreign_id = t1.id ORDER BY `sort` ASC LIMIT 1) AS `medium_image`,

				$sub_query", pjGalleryModel::factory()->getTable(), pjGalleryModel::factory()->getTable()))

				->orderBy("$orderby")->limit($rowCount, $offset)->findAll()->getData();

		

		$make_arr = pjMakeModel::factory()

			->select('t1.*, t2.content AS name')

			->join('pjMultiLang', "t2.model='pjMake' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

			->where('status', 'T')

			->where("t1.id IN(SELECT TL.make_id FROM `".pjListingModel::factory()->getTable()."` AS `TL` WHERE (TL.status = 'T' OR (TL.status = 'E' AND TL.expire >= CURDATE())) )")

			->orderBy('name ASC')

			->findAll()->getData();

				

		$fuel_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')

					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

					->where('type', 'fuel')->where('status', 'T')->orderBy('name ASC')->findAll()->getData();

		$color_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')

					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

					->where('type', 'colors')->where('status', 'T')->orderBy('name ASC')->findAll()->getData();	



		$meta_arr = pjMultiLangModel::factory()->getMultiLang(1, 'pjOption');

		

		$this->set('meta_arr', array(

				'title' => isset($meta_arr[$this->getLocaleId()]['home_meta_title']) ? $meta_arr[$this->getLocaleId()]['home_meta_title'] : null,

				'keywords' => isset($meta_arr[$this->getLocaleId()]['home_meta_keywords']) ? $meta_arr[$this->getLocaleId()]['home_meta_keywords'] : null,

				'description' => isset($meta_arr[$this->getLocaleId()]['home_meta_description']) ? $meta_arr[$this->getLocaleId()]['home_meta_description'] : null

		));

		

		$this->set('arr', $arr);

		$this->set('make_arr', pjSanitize::clean($make_arr));

		$this->set('model_arr', pjSanitize::clean($model_arr));	

		$this->set('fuel_arr', pjSanitize::clean($fuel_arr));	

		$this->set('color_arr', pjSanitize::clean($color_arr));

		$this->set('paginator', array('pages' => $pages, 'total' => $total));

	}

	

	public function pjActionView()

	{

		$pjListingModel = pjListingModel::factory();

		

		$pjListingModel->join('pjUser', 't2.id=t1.owner_id', 'left outer')

			->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left')

			->join('pjCarMod', "t5.id=t1.model_id", 'left')

			->join('pjMultiLang', "t6.model='pjCarMod' AND t6.foreign_id=t5.id AND t6.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMake', "t7.id=t1.make_id", 'left')

			->join('pjMultiLang', "t8.model='pjMake' AND t8.foreign_id=t7.id AND t8.locale='".$this->getLocaleId()."'", 'left')

			->join('pjUser', "t9.id=t1.owner_id AND t9.status='T'", 'left')

			->join('pjMultiLang', "t10.model='pjListing' AND t10.foreign_id=t1.id AND t10.field='meta_title' AND t10.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMultiLang', "t11.model='pjListing' AND t11.foreign_id=t1.id AND t11.field='meta_keywords' AND t11.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMultiLang', "t12.model='pjListing' AND t12.foreign_id=t1.id AND t12.field='meta_description' AND t12.locale='".$this->getLocaleId()."'", 'left')

			->join('pjMultiLang', "t13.model='pjCountry' AND t13.foreign_id=t9.address_country AND t13.locale='".$this->getLocaleId()."'", 'left');

		

		$sub_query = "(	SELECT GROUP_CONCAT(TM.content SEPARATOR ', ') FROM " . pjListingExtraModel::factory()->getTable() . " AS TLE

							LEFT OUTER JOIN " . pjExtraModel::factory()->getTable() . " AS TE ON TE.id=TLE.extra_id AND TE.status='T'

							LEFT OUTER JOIN " . pjMultiLangModel::factory()->getTable() . " AS TM ON TM.foreign_id = TE.id AND TM.model = 'pjExtra' AND TM.locale = '".$this->getLocaleId()."' AND TM.field = 'name'

							WHERE TLE.listing_id=t1.id) as extras";

		$arr = $pjListingModel->select(sprintf("t1.*, t6.content as model, t8.content as make, 

												t9.email, t9.name, t9.contact_title, t9.contact_phone, 

												t9.contact_mobile, t9.contact_fax, t9.contact_url, t9.address_postcode, 

												t9.address_content, t9.address_city, t9.address_state,   

												t13.content as country_title,

												t3.content AS listing_title, t4.content as listing_description,

												t10.content AS meta_title, t11.content AS meta_keywords, t12.content AS meta_description,

				(SELECT `small_path` FROM `%s` WHERE foreign_id = t1.id ORDER BY `sort` ASC LIMIT 1) AS `image`, $sub_query", pjGalleryModel::factory()->getTable()))

				->where('t1.id', $_GET['id'])

				->where("(t1.status = 'T' OR (t1.status = 'E' AND t1.expire >= CURDATE()))")

				->limit(1)

				->findAll()

				->getData();

		

		if (count($arr) === 1)

		{

			$arr = $arr[0];

			

			$pjListingModel->reset()->setAttributes(array('id' => $arr['id']))->modify(array('views' => $arr['views'] + 1));

			

			$this->set('gallery_arr', pjGalleryModel::factory()->where('t1.foreign_id', $_GET['id'])->orderBy('t1.sort ASC')->findAll()->getData());

			

			$message_arr = array();

			$message = '';

			$subject = '';

			

			$notify_arr = pjNotificationModel::factory()->where('user_id', 1)->findAll()->getData();

			

			if(count($notify_arr) > 0)

			{

				$message_arr = $notify_arr[0];

			}

			if(!empty($message_arr))

			{

				$message_arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($message_arr['id'], 'pjNotification');

				if(!empty($message_arr['i18n'][$this->getLocaleId()]['predefined_message']))

				{

					$message = str_replace(	array('{RefID}'),

							array($arr['listing_refid']),

							$message_arr['i18n'][$this->getLocaleId()]['predefined_message']);

				}

				if(!empty($message_arr['i18n'][$this->getLocaleId()]['predefined_subject']))

				{

					$subject = $message_arr['i18n'][$this->getLocaleId()]['predefined_subject'];

				}

			}

			

			$contact_message = null;

			if(isset($_SESSION['contact_message']))

			{

				$contact_message = $_SESSION['contact_message'];

				unset($_SESSION['contact_message']);

			}

			$this->set('contact_message', $contact_message);

			

			$this->set('subject', $subject);

			$this->set('message', $message);

			

			$this->set('meta_arr', array(

					'title' => !empty($arr['meta_title']) ? $arr['meta_title'] : pjSanitize::html(stripslashes($arr['listing_title'])),

					'keywords' => $arr['meta_keywords'],

					'description' => !empty($arr['meta_description']) ? $arr['meta_description'] : pjUtil::truncateDescription(pjUtil::html2txt($arr['listing_description']), 160, ' ')

			));

			

			$this->set('arr', $arr);

		}

		

		$feature_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')

					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();

		

		$this->set('feature_arr', $feature_arr);

		

		$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);

		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();

		

		$this->appendCss('pjQuery.fancybox.css', PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_fancybox')), true);

	}

	

	public function pjActionContact()

	{

		if (isset($_POST['listing_contact']))

		{

			$pjListingModel = pjListingModel::factory();

			

			$pjListingModel

				->join('pjMultiLang', "t2.model='pjListing' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMultiLang', "t3.model='pjMake' AND t3.foreign_id=t1.make_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMultiLang', "t4.model='pjCarMod' AND t4.foreign_id=t1.model_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left')

				->join('pjUser', "t5.id=t1.owner_id AND t5.status='T'", 'left');

			$arr = $pjListingModel

				->select("t1.*, t2.content AS listing_title, t3.content as make, t4.content as model, t5.email")

				->find($_POST['listing_id'])->getData();

			

			$url = $_SERVER['SCRIPT_NAME'] . '?controller=pjListings&action=pjActionView&id=' . $arr['id'] . '#pjAcContactSection';

			if ($this->option_arr['o_seo_url'] == 'Yes')

			{

				$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

				$path = $path == '/' ? '' : $path;

				$url = $path .'/'. $this->friendlyURL($arr['listing_title']) . "-". $arr['id'] . ".html#pjAcContactSection";

			} 

			set_time_limit(0);

			

			$err = 9999;

			

			if (!isset($_POST['contact_name']))

			{

				$err = 9901;

			}

			if (!isset($_POST['contact_email']))

			{

				$err = 9902;

			}

			if (!isset($_POST['contact_message']))

			{

				$err = 9903;

			}

			if (isset($_POST['contact_name']) && !pjValidation::pjActionNotEmpty($_POST['contact_name']))

			{

				$err = 9904;

			}

			if (isset($_POST['contact_email']) && !pjValidation::pjActionNotEmpty($_POST['contact_email']))

			{

				$err = 9905;

			}

			if (isset($_POST['contact_message']) && !pjValidation::pjActionNotEmpty($_POST['contact_message']))

			{

				$err = 9906;

			}

			if (isset($_POST['contact_email']) && !pjValidation::pjActionEmail($_POST['contact_email']))

			{

				$err = 9907;

			}

			if (isset($_POST['captcha']) && !pjValidation::pjActionNotEmpty($_POST['captcha']))

			{

				$err = 9908;

			}

			if (!isset($_SESSION[$this->defaultCaptcha]))

			{

				$err = 9909;

			}

			if (isset($_POST['captcha']) && isset($_SESSION[$this->defaultCaptcha]) && strtoupper($_POST['captcha']) != $_SESSION[$this->defaultCaptcha])

			{

				$err = 9909;

			}

			if(isset($_SESSION[$this->defaultCaptcha]))

			{

				$_SESSION[$this->defaultCaptcha] = NULL;

				unset($_SESSION[$this->defaultCaptcha]);

			}

			if ($err == 9999)

			{

				$admin_arr = pjUserModel::factory()->where('role_id', 1)->findAll()->getDataPair(null, 'email');

				

				$owner_email = $arr['email'];

				

				$subject = '';

					

				$notify_arr = pjNotificationModel::factory()->where('user_id', 1)->findAll()->getData();

					

				if(count($notify_arr) > 0)

				{

					$message_arr = $notify_arr[0];

				}

				if(!empty($message_arr))

				{

					$message_arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($message_arr['id'], 'pjNotification');

					if(!empty($message_arr['i18n'][$this->getLocaleId()]['predefined_subject']))

					{

						$subject = $message_arr['i18n'][$this->getLocaleId()]['predefined_subject'];

						$subject = str_replace(	array('{RefID}'),array($arr['listing_refid']),$subject);

					}

				}

				

				$subject = !empty($subject) ? $subject : __('front_contact_subject', true);

				$message = __('front_car_interested_in', true) . "\r\n";

				$message .= __('front_label_title', true) . ': ' . stripslashes($arr['listing_title']) . "\r\n";

				$message .= __('front_label_make', true) . ': ' . $arr['make'] . "\r\n";

				$message .= __('front_label_model', true) . ': ' . $arr['model'] . "\r\n";

				$message .= __('front_label_year', true) . ': ' . $arr['listing_year'] . "\r\n\r\n";

				$message .= __('front_label_name', true) . ': ' . $_POST['contact_name'] . "\r\n";

				$message .= __('front_label_email', true) . ': ' . $_POST['contact_email'] . "\r\n\r\n";

				$message .= __('front_label_question', true) . ": \r\n";

				$message .= $_POST['contact_message'] . "\r\n";

				

				$pjEmail = new pjEmail();

				if ($this->option_arr['o_send_email'] == 'smtp' && 

					$this->option_arr['o_smtp_host'] != '' && 

					$this->option_arr['o_smtp_port'] != '' &&

					$this->option_arr['o_smtp_user'] != '' &&

					$this->option_arr['o_smtp_pass'] != '')

				{

					$pjEmail

						->setTransport('smtp')

						->setSmtpHost($this->option_arr['o_smtp_host'])

						->setSmtpPort($this->option_arr['o_smtp_port'])

						->setSmtpUser($this->option_arr['o_smtp_user'])

						->setSmtpPass($this->option_arr['o_smtp_pass'])

					;

				}

				$pjEmail

						->setContentType('text/plain')

						->setSubject($subject)

						->setFrom($this->getFromEmail());

				foreach($admin_arr as $admin_email)

				{

					$pjEmail->setTo($admin_email)

							->send($message);

				}

				if(!in_array($owner_email, $admin_arr))

				{	

					$pjEmail->setTo($owner_email)

							->send($message);

				}

			}

			

			$_SESSION['contact_message'] = $err;

			pjUtil::redirect($url);

		}

	}

	

	public function pjActionLogin()

	{

		if ($this->option_arr['o_allow_adding_car'] == 'Yes')

		{

			$meta_arr = pjMultiLangModel::factory()->getMultiLang(1, 'pjOption');

			

			$this->set('meta_arr', array(

					'title' => isset($meta_arr[$this->getLocaleId()]['login_meta_title']) ? $meta_arr[$this->getLocaleId()]['login_meta_title'] : null,

					'keywords' => isset($meta_arr[$this->getLocaleId()]['login_meta_keywords']) ? $meta_arr[$this->getLocaleId()]['login_meta_keywords'] : null,

					'description' => isset($meta_arr[$this->getLocaleId()]['login_meta_description']) ? $meta_arr[$this->getLocaleId()]['login_meta_description'] : null

			));

		}else{

			if ($this->option_arr['o_seo_url'] == 'No')

			{

				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=".$_GET['controller']."&action=pjActionIndex");

			}else{

				$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

				$path = $path == '/' ? '' : $path;

				pjUtil::redirect($path . '/index.html');

			}

		}

	}

	

	public function pjActionRegister()

	{

        $path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        $path = $path == '/' ? '' : $path;

		if ($this->option_arr['o_allow_adding_car'] == 'Yes')

		{

			if (isset($_POST['listing_register']))

			{

				set_time_limit(0);





				

				if (!isset($_POST['register_email']))

				{

					$err = 9901;

				}

				if (!isset($_POST['register_password']))

				{

					$err = 9902;

				}

				if (!isset($_POST['register_password_repeat']))

				{

					$err = 9903;

				}

				if (!isset($_POST['name']))

				{

					$err = 9904;

				}

				if (!isset($_POST['captcha']))

				{

					$err = 9905;

				}

				if (isset($_POST['register_email']) && !pjValidation::pjActionNotEmpty($_POST['register_email']))

				{

					$err = 9906;

				}

				if (isset($_POST['register_password']) && !pjValidation::pjActionNotEmpty($_POST['register_password']))

				{

					$err = 9907;

				}

				if (isset($_POST['register_password_repeat']) && !pjValidation::pjActionNotEmpty($_POST['register_password_repeat']))

				{

					$err = 9908;

				}

				if (isset($_POST['name']) && !pjValidation::pjActionNotEmpty($_POST['name']))

				{

					$err = 9909;

				}

				if (isset($_POST['captcha']) && !pjValidation::pjActionNotEmpty($_POST['captcha']))

				{

					$err = 9910;

				}

				if (!isset($_SESSION[$this->defaultCaptcha]))

				{

					$err = 9911;

				}

				if (isset($_POST['captcha']) && isset($_SESSION[$this->defaultCaptcha]) && strtoupper($_POST['captcha']) != $_SESSION[$this->defaultCaptcha])

				{

					$err = 9911;

				}

				if (isset($_POST['register_email']) && !pjValidation::pjActionEmail($_POST['register_email']))

				{

					$err = 9912;

				}

				if (isset($_POST['register_password']) && isset($_POST['register_password_repeat']) && $_POST['register_password'] != $_POST['register_password_repeat'])

				{

					$err = 9913;

				}

				if (isset($err))

				{

					if (isset($_SESSION[$this->defaultCaptcha]))

					{

						$_SESSION[$this->defaultCaptcha] = NULL;

						unset($_SESSION[$this->defaultCaptcha]);

					}

					if ($this->option_arr['o_seo_url'] == 'No')

					{

						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjListings&action=pjActionRegister&err=$err");

					}else{

						pjUtil::redirect($path . '/register.html?err=' . $err);

					}

				}

				

				if (isset($_SESSION[$this->defaultCaptcha]))

				{

					$_SESSION[$this->defaultCaptcha] = NULL;

					unset($_SESSION[$this->defaultCaptcha]);

				}

				

				$pjUserModel = pjUserModel::factory();

				$arr = $pjUserModel->where('t1.email', $_POST['register_email'])->findAll()->getData();

	

				if (!empty($arr) && count($arr) > 0)

				{

					if ($this->option_arr['o_seo_url'] == 'No')

					{

						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjListings&action=pjActionRegister&err=9997");

					}else{

						pjUtil::redirect($path . '/register.html?err=9997');

					}

				} else {

					$data['password'] = $_POST['register_password'];

					$data['email'] = $_POST['register_email'];

					$data['role_id'] = 3;

					$data['status'] = $this->option_arr['o_user_account_confirmed'] == 'Yes' ? 'T' : 'F';

					$data['is_active'] = 'T';

					$data['ip'] = $_SERVER['REMOTE_ADDR'];

					$data = array_merge($_POST, $data);

					

					$id = $pjUserModel->reset()->setAttributes($data)->insert()->getInsertId();

					if ($id !== false && (int) $id > 0)

					{

						$email_data = array();				

								

						$email_default = pjNotificationModel::factory()->where('user_id', 1)->findAll()->getData();				

						if(count($email_default) > 0)

						{

							$email_data = $email_default[0];

						}

						if(!empty($email_data))

						{				

							$admin_arr = $pjUserModel->reset()->where('role_id', 1)->findAll()->getData();

							

							$email_data['i18n'] = pjMultiLangModel::factory()->getMultiLang($email_data['id'], 'pjNotification');

							if(!empty($email_data['i18n'][$this->getLocaleId()]['new_account_subject']) && !empty($email_data['i18n'][$this->getLocaleId()]['new_account_message']))

							{				

								$subject = $email_data['i18n'][$this->getLocaleId()]['new_account_subject'];

								$message = str_replace(	array('{Email}', '{Name}'), 

														array($_POST['register_email'], $_POST['name']), 

														$email_data['i18n'][$this->getLocaleId()]['new_account_message']);

								

								$pjEmail = new pjEmail();

								if ($this->option_arr['o_send_email'] == 'smtp' && 

									$this->option_arr['o_smtp_host'] != '' && 

									$this->option_arr['o_smtp_port'] != '' &&

									$this->option_arr['o_smtp_user'] != '' &&

									$this->option_arr['o_smtp_pass'] != '')

								{

									$pjEmail

										->setTransport('smtp')

										->setSmtpHost($this->option_arr['o_smtp_host'])

										->setSmtpPort($this->option_arr['o_smtp_port'])

										->setSmtpUser($this->option_arr['o_smtp_user'])

										->setSmtpPass($this->option_arr['o_smtp_pass'])

									;

								}

								

								$pjEmail

									->setFrom($this->getFromEmail())

									->setSubject($subject);

								

								$pjEmail

									->setTo($data['email'])

									->send($message);

								foreach($admin_arr as $admin)

								{

									$pjEmail

										->setTo($admin['email'])

										->send($message);

								}

							}

						}

						if ($this->option_arr['o_seo_url'] == 'No')

						{

							if ($this->option_arr['o_user_account_confirmed'] == 'Yes')

							{

								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjListings&action=pjActionRegister&err=9999");

							} else {

								pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjListings&action=pjActionRegister&err=9998");

							}

						}else{

							if ($this->option_arr['o_user_account_confirmed'] == 'Yes')

							{

								pjUtil::redirect($path . '/register.html?err=9999');

							} else {

								pjUtil::redirect($path . '/register.html?err=9998');

							}

						}

						exit;

					}

				}

			}else{

				$meta_arr = pjMultiLangModel::factory()->getMultiLang(1, 'pjOption');

					

				$this->set('meta_arr', array(

						'title' => isset($meta_arr[$this->getLocaleId()]['register_meta_title']) ? $meta_arr[$this->getLocaleId()]['register_meta_title'] : null,

						'keywords' => isset($meta_arr[$this->getLocaleId()]['register_meta_keywords']) ? $meta_arr[$this->getLocaleId()]['register_meta_keywords'] : null,

						'description' => isset($meta_arr[$this->getLocaleId()]['register_meta_description']) ? $meta_arr[$this->getLocaleId()]['register_meta_description'] : null

				));

			}

			

		}else{

			if ($this->option_arr['o_seo_url'] == 'No')

			{

				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=".$_GET['controller']."&action=pjActionIndex");

			}else{

				pjUtil::redirect($path . '/index.html');

			}

		}

	}

	

	public function pjActionSearch()

	{

		$make_arr = pjMakeModel::factory()

			->select('t1.*, t2.content AS name')

			->join('pjMultiLang', "t2.model='pjMake' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

			->where('status', 'T')

			->where("t1.id IN(SELECT TL.make_id FROM `".pjListingModel::factory()->getTable()."` AS `TL` WHERE (TL.status = 'T' OR (TL.status = 'E' AND TL.expire >= CURDATE())) )")

			->orderBy('name ASC')

			->findAll()

			->getData();

		$feature_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')

					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();



		$meta_arr = pjMultiLangModel::factory()->getMultiLang(1, 'pjOption');

			

		$this->set('meta_arr', array(

				'title' => isset($meta_arr[$this->getLocaleId()]['search_meta_title']) ? $meta_arr[$this->getLocaleId()]['search_meta_title'] : null,

				'keywords' => isset($meta_arr[$this->getLocaleId()]['search_meta_keywords']) ? $meta_arr[$this->getLocaleId()]['search_meta_keywords'] : null,

				'description' => isset($meta_arr[$this->getLocaleId()]['search_meta_description']) ? $meta_arr[$this->getLocaleId()]['search_meta_description'] : null

		));

		

		$this->set('make_arr', pjSanitize::clean($make_arr));

		$this->set('feature_arr', $feature_arr);

	}

	

	public function pjActionLoadModels()

	{

		$this->setAjax(true);

		if ($this->isXHR())

		{

			$model_arr = pjCarModModel::factory()

				->select('t1.*, t2.content AS name')

				->join('pjMultiLang', "t2.model='pjCarMod' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'")

				->where('t1.status', 'T')

				->where('make_id', $_GET['make_id'])

				->where("t1.id IN(SELECT TL.model_id FROM `".pjListingModel::factory()->getTable()."` AS `TL` WHERE (TL.status = 'T' OR (TL.status = 'E' AND TL.expire >= CURDATE())) )")

				->orderBy('name ASC')

				->findAll()

				->getData();

			$this->set('model_arr', $model_arr);

		}

	}

	

	public function pjActionAddCompare()

	{

		$this->setAjax(true);

		if ($this->isXHR())

		{

			$id = $_GET['id'];

			$id_arr = array();

			if(isset($_SESSION[$this->compareList]))

			{

				$id_arr = $_SESSION[$this->compareList];

				if(!in_array($id, $id_arr)){

					$id_arr[] = $id;

				}

			}else{

				$id_arr[] = $id;

			}

			$_SESSION[$this->compareList] = $id_arr;

			echo count($_SESSION[$this->compareList]);

			

			exit;

		}

	}

	

	public function pjActionRemoveCompare()

	{

		$this->setAjax(true);

		if ($this->isXHR())

		{

			$id_arr = array();		

			if(isset($_SESSION[$this->compareList]))

			{

				$id_arr = $_SESSION[$this->compareList];

				$id_arr = array_diff($id_arr, array($_GET['id']));

				$id_arr = array_values($id_arr);

				$_SESSION[$this->compareList] = $id_arr;

			}			

			echo count($_SESSION[$this->compareList]);

			

			exit;

		}

	}

	

	public function pjActionClearCompare()

	{

		if(isset($_SESSION[$this->compareList]))

		{

			$_SESSION[$this->compareList] = NULL;

			unset($_SESSION[$this->compareList]);

		}

		if ($this->option_arr['o_seo_url'] == 'No')

		{

			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=".$_GET['controller']."&action=pjActionCars");

		}else{

			$path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

			$path = $path == '/' ? '' : $path;

			pjUtil::redirect($path . '/index.html');

		}

	}

	

	public function pjActionCompare()

	{

		$pjListingModel = pjListingModel::factory();

		

		$arr = array();

		

		if(!empty($_SESSION[$this->compareList]))

		{

			$id_str = join(',', $_SESSION[$this->compareList]);

			

			$pjListingModel->join('pjUser', 't2.id=t1.owner_id', 'left outer')

				->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left')

				->join('pjCarMod', "t5.id=t1.model_id", 'left')

				->join('pjMultiLang', "t6.model='pjCarMod' AND t6.foreign_id=t5.id AND t6.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMake', "t7.id=t1.make_id", 'left')

				->join('pjMultiLang', "t8.model='pjMake' AND t8.foreign_id=t7.id AND t8.locale='".$this->getLocaleId()."'", 'left')

				->join('pjUser', "t9.id=t1.owner_id AND t9.status='T'", 'left')

				->join('pjMultiLang', "t10.model='pjListing' AND t10.foreign_id=t1.id AND t10.field='meta_title' AND t10.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMultiLang', "t11.model='pjListing' AND t11.foreign_id=t1.id AND t11.field='meta_keywords' AND t11.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMultiLang', "t12.model='pjListing' AND t12.foreign_id=t1.id AND t12.field='meta_description' AND t12.locale='".$this->getLocaleId()."'", 'left')

				->join('pjMultiLang', "t13.model='pjCountry' AND t13.foreign_id=t9.address_country AND t13.locale='".$this->getLocaleId()."'", 'left');

			

			$sub_query = "(	SELECT GROUP_CONCAT(TM.content SEPARATOR ', ') FROM " . pjListingExtraModel::factory()->getTable() . " AS TLE

								LEFT OUTER JOIN " . pjExtraModel::factory()->getTable() . " AS TE ON TE.id=TLE.extra_id AND TE.status='T'

								LEFT OUTER JOIN " . pjMultiLangModel::factory()->getTable() . " AS TM ON TM.foreign_id = TE.id AND TM.model = 'pjExtra' AND TM.locale = '".$this->getLocaleId()."' AND TM.field = 'name'

								WHERE TLE.listing_id=t1.id) as extras";

			$arr = $pjListingModel->select(sprintf("t1.*, t6.content as model, t8.content as make,

													t9.email, t9.name, t9.contact_title, t9.contact_phone, 

													t9.contact_mobile, t9.contact_fax, t9.contact_url, t9.address_postcode, 

													t9.address_content, t9.address_city, t9.address_state, 

													t13.content as country_title,

													t3.content AS listing_title, t4.content as listing_description,

													t10.content AS meta_title, t11.content AS meta_keywords, t12.content AS meta_description, $sub_query"))

					->where("t1.id IN($id_str)")

					->where("(t1.status = 'T' OR (t1.status = 'E' AND t1.expire >= CURDATE()))")

					->findAll()

					->getData();

		}	

		$feature_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')

					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')

					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();

					

		$this->set('arr', $arr);

		$this->set('feature_arr', $feature_arr);

		

		$meta_arr = pjMultiLangModel::factory()->getMultiLang(1, 'pjOption');

			

		$this->set('meta_arr', array(

				'title' => isset($meta_arr[$this->getLocaleId()]['compare_meta_title']) ? $meta_arr[$this->getLocaleId()]['compare_meta_title'] : null,

				'keywords' => isset($meta_arr[$this->getLocaleId()]['compare_meta_keywords']) ? $meta_arr[$this->getLocaleId()]['compare_meta_keywords'] : null,

				'description' => isset($meta_arr[$this->getLocaleId()]['compare_meta_description']) ? $meta_arr[$this->getLocaleId()]['compare_meta_description'] : null

		));

	}

	

	public function pjActionConfirmPayment()

	{

		$this->setAjax(true);

		

		if (pjObject::getPlugin('pjPaypal') === NULL)

		{

			$this->log('Paypal plugin not installed');

			exit;

		}

		

		$pjPaymentModel = pjPaymentModel::factory();

		$pjListingModel = pjListingModel::factory();

		

		$listing_arr = $pjListingModel->find($_POST['custom'])->getData();

		$payment_arr = $pjPaymentModel->where('t1.listing_id', $_POST['custom'])->orderBy('t1.date_to DESC')->limit(1)->findAll()->getData();

		$period_arr = pjPeriodModel::factory()->findAll()->getData();

		$date_from = date("Y-m-d");

		if (count($payment_arr) === 1)

		{

			$date_from = $payment_arr[0]['date_to'];

		}

		

		$period = $price = NULL;

		foreach ($period_arr as $_period)

		{

			if ((float) $_period['price'] == (float) $_POST['mc_gross'])

			{

				$period = (int) $_period['days'];

				$price = (float) $_period['price'];

				break;

			}

		}

		list($year, $month, $day) = explode("-", $date_from);

		$date_to = date("Y-m-d", mktime(0, 0, 0, $month, $day + $period, $year));

		

		$params = array(

			'txn_id' => @$booking_arr['txn_id'],

			'paypal_address' => $this->option_arr['o_paypal_address'],

			'deposit' => $price,

			'currency' => $this->option_arr['o_currency'],

			'key' => md5($this->option_arr['private_key'] . PJ_SALT)

		);



		$response = $this->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));

		if ($response !== FALSE && isset($response['status']) && $response['status'] === 'OK')

		{

			$this->log('pjPaypal > pjActionConfirm > status == OK');

			$pjPaymentModel

				->reset()

				->setAttributes(array(

					'listing_id' => $listing_arr['id'],

					'date_from' => $date_from,

					'date_to' => $date_to,

					'txn_id' => $response['transaction_id'],

					'price' => $price

				))

				->insert();

			$current = time();

			if (!empty($listing_arr['expire']) && $listing_arr['expire'] != '0000-00-00')

			{

				$current = strtotime($listing_arr['expire']);

			}

			$pjListingModel->reset()

				->set('id', $listing_arr['id'])

				->modify(array(

					'last_extend' => 'paid',

					'expire' => date("Y-m-d", $current + $period * 86400)

				));

			$this->log('Payment confirmed');

		} elseif (!$response) {

			$this->log('Authorization failed');

		} else {

			$this->log('Payment not confirmed');

		}

		exit;

	}

}

?>