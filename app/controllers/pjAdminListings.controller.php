<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminListings extends pjAdmin
{
	private $imageFiles = array('small_path', 'medium_path', 'large_path', 'source_path');
	
	public function pjActionCheckRefId()
	{
		$this->setAjax(true);
		
		if ($this->isXHR() && isset($_GET['listing_refid']))
		{
			$pjListingModel = pjListingModel::factory();
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjListingModel->where('t1.id !=', $_GET['id']);
			}
			echo $pjListingModel->where('t1.listing_refid', $_GET['listing_refid'])->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			$make_arr = pjMakeModel::factory()->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjMake' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
				->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
			$this->set('make_arr', pjSanitize::clean($make_arr));
			
			$feature_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
						
			$this->set('feature_arr', $feature_arr);
					
			$user_arr = pjUserModel::factory()->where('role_id', 2)->orderBy('t1.name ASC')->findAll()->getData();
			$this->set('user_arr', pjSanitize::clean($user_arr));
			
			$this->appendJs('chosen.jquery.min.js', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
					
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminListings.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionGetListing()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{			
			$pjListingModel = pjListingModel::factory()
				->join('pjUser', 't2.id=t1.owner_id', 'left outer')
				->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t5.model='pjMake' AND t5.foreign_id=t1.make_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left outer')
				->join('pjMultiLang', "t6.model='pjCarMod' AND t6.foreign_id=t1.model_id AND t6.field='name' AND t6.locale='".$this->getLocaleId()."'", 'left outer');
			
			if (isset($_GET['status']) && !empty($_GET['status']))
			{
				if($_GET['status'] == 'T')
				{
					$pjListingModel->where("(t1.status = 'T' OR (t1.status = 'E' AND t1.expire >= CURDATE()))");
				}else{
					$pjListingModel->where("(t1.status = 'F' OR (t1.status = 'E' AND t1.expire < CURDATE()))");
				}
			}
			
			if ($this->isOwner())
			{
				$pjListingModel->where('t1.owner_id', $this->getUserId());
			} else {
				if (isset($_GET['user_id']) && (int) $_GET['user_id'] > 0)
				{
					$pjListingModel->where('t1.owner_id', $_GET['user_id']);
				}
			}
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$pjListingModel->where(sprintf("(t1.listing_refid LIKE '%%%1\$s%%' OR t3.content LIKE '%%%1\$s%%' OR t4.content LIKE '%%%1\$s%%' OR t2.name LIKE '%%%1\$s%%')", pjObject::escapeString($_GET['q'])));
			}
			
			if (isset($_GET['listing_refid']) && !empty($_GET['listing_refid']))
			{
				$q = pjObject::escapeString($_GET['listing_refid']);
				$pjListingModel->where('t1.listing_refid LIKE', "%$q%");
			}
			if (isset($_GET['make_id']) && !empty($_GET['make_id']))
			{
				$pjListingModel->where('t1.make_id', $_GET['make_id']);
			}
			if (isset($_GET['model_id']) && !empty($_GET['model_id']))
			{
				$pjListingModel->where('t1.model_id', $_GET['model_id']);
			}
			if (isset($_GET['year_from']) && $_GET['year_from'] != '' && isset($_GET['year_to']) && $_GET['year_to'] != '')
			{
				$pjListingModel->where('t1.listing_year >=', $_GET['year_from']);
				$pjListingModel->where('t1.listing_year <=', $_GET['year_to']);
			} else {
				if (isset($_GET['year_from']) && $_GET['year_from'] != '')
				{
					$pjListingModel->where('t1.listing_year >=', $_GET['year_from']);
				} else if (isset($_GET['year_to']) && $_GET['year_to'] != '') {
					$pjListingModel->where('t1.listing_year <=', $_GET['year_to']);
				}			
			}
			if (isset($_GET['mileage_from']) && $_GET['mileage_from'] != '' && isset($_GET['mileage_to']) && $_GET['mileage_to'] != '')
			{
				$pjListingModel->where('t1.listing_mileage >=', $_GET['mileage_from']);
				$pjListingModel->where('t1.listing_mileage <=', $_GET['mileage_to']);
			} else {
				if (isset($_GET['mileage_from']) && $_GET['mileage_from'] != '')
				{
					$pjListingModel->where('t1.listing_mileage >=', $_GET['mileage_from']);
				} else if (isset($_GET['mileage_to']) && $_GET['mileage_to'] != '') {
					$pjListingModel->where('t1.listing_mileage <=', $_GET['mileage_to']);
				}			
			}
			if (isset($_GET['power_from']) && $_GET['power_from'] != '' && isset($_GET['power_to']) && $_GET['power_to'] != '')
			{
				$pjListingModel->where('t1.listing_power >=', $_GET['power_from']);
				$pjListingModel->where('t1.listing_power <=', $_GET['power_to']);
			} else {
				if (isset($_GET['power_from']) && $_GET['power_from'] != '')
				{
					$pjListingModel->where('t1.listing_power >=', $_GET['power_from']);
				} else if (isset($_GET['power_to']) && $_GET['power_to'] != '') {
					$pjListingModel->where('t1.listing_power <=', $_GET['power_to']);
				}			
			}
			if (isset($_GET['feature_gearbox_id']) && !empty($_GET['feature_gearbox_id']))
			{
				$pjListingModel->where('t1.feature_gearbox_id', $_GET['feature_gearbox_id']);
			}
			if (isset($_GET['feature_fuel_id']) && !empty($_GET['feature_fuel_id']))
			{
				$pjListingModel->where('t1.feature_fuel_id', $_GET['feature_fuel_id']);
			}
			if (isset($_GET['feature_seats_id']) && !empty($_GET['feature_seats_id']))
			{
				$pjListingModel->where('t1.feature_seats_id', $_GET['feature_seats_id']);
			}
			if (isset($_GET['feature_doors_id']) && !empty($_GET['feature_doors_id']))
			{
				$pjListingModel->where('t1.feature_doors_id', $_GET['feature_doors_id']);
			}
			if (isset($_GET['feature_class_id']) && !empty($_GET['feature_class_id']))
			{
				$pjListingModel->where('t1.feature_class_id', $_GET['feature_class_id']);
			}
			if (isset($_GET['feature_type_id']) && !empty($_GET['feature_type_id']))
			{
				$pjListingModel->where('t1.feature_type_id', $_GET['feature_type_id']);
			}
			if (isset($_GET['feature_colors_id']) && !empty($_GET['feature_colors_id']))
			{
				$pjListingModel->where('t1.feature_colors_id', $_GET['feature_colors_id']);
			}
			
			$column = 'id';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}
			
			$total = $pjListingModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjListingModel->select(sprintf('t1.id, t1.listing_refid, t1.listing_price, t1.listing_year, t1.expire, t1.status, t1.owner_id, t2.name AS owner_name, t5.content as make, t6.content as model,
				(SELECT `small_path` FROM `%s` WHERE foreign_id = t1.id ORDER BY `sort` ASC LIMIT 1) AS `image`', pjGalleryModel::factory()->getTable()))
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
			foreach($data as $k => $v){
				if($v['status'] == 'T')
				{
					$v['expire'] = __('lblNever', true);
					
				}else if($v['status'] == 'F'){
					$v['expire'] = '';
				}else{
					if($v['expire'] < date('Y-m-d'))
					{
						$v['expire'] = '<span style="color:red;">' . pjUtil::formatDate($v['expire'], 'Y-m-d', $this->option_arr['o_date_format']) . '</span>';
					}else{
						$v['expire'] = pjUtil::formatDate($v['expire'], 'Y-m-d', $this->option_arr['o_date_format']);
					}
				}
				$detail_arr = array();
				$detail_arr[] = pjSanitize::html($v['make']);
				$detail_arr[] = pjSanitize::html($v['model']);
				if(!empty($v['listing_year']))
				{
					$detail_arr[] = pjSanitize::html($v['listing_year']);
				}
				if(!empty($v['listing_price']))
				{
					$detail_arr[] = pjUtil::formatCurrencySign($v['listing_price'], $this->option_arr['o_currency']);
				}
				$v['details'] = join("<br/>", $detail_arr);
				$data[$k] = $v;
			}
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			if (isset($_POST['listing_create']))
			{
				if ($this->isOwner())
				{
					if ($this->option_arr['o_allow_adding_car'] == 'No')
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL11");
					}
				}
				
				$data = array();
				if (isset($_POST['expire']) && $_POST['status'] == 'E')
				{
					$data['expire'] = pjUtil::formatDate($_POST['expire'], $this->option_arr['o_date_format']);
				}else{
					unset($_POST['expire']);
				}
				$data['last_extend'] = 'free';
				if ($this->isOwner())
				{
					$data['owner_id'] = $this->getUserId();
					$data['status'] = 'E';
					$data['expire'] = date("Y-m-d", strtotime("-1 day"));
				}
				$data = array_merge($_POST, $data);
				$pjListingModel = pjListingModel::factory();
				if (!$pjListingModel->validates($data))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionCreate&err=AL09");
				}
				
				if ($pjListingModel->where('t1.listing_refid', $data['listing_refid'])->findCount()->getData() > 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionCreate&err=AL10");
				}
				
				$id = $pjListingModel->reset()->setAttributes($data)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AL03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjListing', 'data');
					}
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionUpdate&id=" . $id . "&err=$err");
				} else {
					$err = 'AL04';
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionCreate&err=$err");
				}
			}
			
			if ($this->isOwner())
			{
				$this->set('period_arr', pjPeriodModel::factory()->orderBy('t1.days ASC')->findAll()->getData());
			}

			$make_arr = pjMakeModel::factory()->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjMake' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'")
				->where('t1.status', 'T')->orderBy('name ASC')->findAll()->getData();
			$this->set('make_arr', pjSanitize::clean($make_arr));

			$user_arr = pjUserModel::factory()->orderBy('t1.name ASC')->findAll()->getData();
			$this->set('user_arr', pjSanitize::clean($user_arr));
				
			$this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
				
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminListings.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
			
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			if (isset($_POST['listing_update']))
			{
				$arr = pjListingModel::factory()->find($_POST['id'])->getData();
				if (empty($arr))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL08");
				}
				
				$err = NULL;
				$data = array();
				
				if ($this->isOwner())
				{
					if ($this->option_arr['o_allow_adding_car'] == 'No')
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL11");
					}
					
					if ($arr['owner_id'] != $this->getUserId())
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL11");
					}

					unset($_POST['owner_id']);
					$data['owner_id'] = $arr['owner_id'];
					$data['expire'] = $arr['expire'];
					$data['status'] = $arr['status'];
				}
				
				$data['modified'] = date("Y-m-d H:i:s");
			
				if (!$this->isOwner())
				{
					if($_POST['status'] == 'E')
					{
						$data['expire'] = pjUtil::formatDate($_POST['expire'], $this->option_arr['o_date_format']);
					}else{
						$data['expire'] = ":NULL";
					}
				}
				
				$pjListingModel = pjListingModel::factory();
				$post = array_merge($_POST, $data);

				if (!$pjListingModel->validates($post))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionUpdate&id=" . $_POST['id'] . "&locale=" . $_POST['locale'] . "&tab_id=" . $_POST['tab_id'] . "&err=AL09");
				}
				
				if ($pjListingModel->where('t1.id !=', $_POST['id'])->where('t1.listing_refid', $post['listing_refid'])->findCount()->getData() > 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionUpdate&id=" . $_POST['id'] . "&locale=" . $_POST['locale'] . "&tab_id=" . $_POST['tab_id'] . "&err=AL10");
				}
				
				$pjListingModel->set('id', $_POST['id'])->modify($post);

				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjListing', 'data');
				}
				
				pjListingExtraModel::factory()->where('listing_id', $_POST['id'])->eraseAll();
				
				if (isset($_POST['extra']) && is_array($_POST['extra']) && count($_POST['extra']) > 0)
				{
					$pjListingExtraModel = pjListingExtraModel::factory();
					$pjListingExtraModel->begin();
					foreach ($_POST['extra'] as $extra_id)
					{
						$pjListingExtraModel->setAttributes(array(
							'listing_id' => $_POST['id'],
							'extra_id' => $extra_id
						))->insert();
					}
					$pjListingExtraModel->commit();
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionUpdate&id=" . $_POST['id'] . "&locale=" . $_POST['locale'] . "&tab_id=" . $_POST['tab_id'] . "&err=AL01");
				
			} else {
				$arr = pjListingModel::factory()->find($_GET['id'])->getData();
				
				if (count($arr) === 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL08");
				}
				if ($this->isOwner() && $arr['owner_id'] != $this->getUserId())
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL11");
				}
				
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjListing');
				
				$make_arr = pjMakeModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjMake' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
					
				$model_arr = pjCarModModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCarMod' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->where('make_id', $arr['make_id'])->orderBy('name ASC')->findAll()->getData();
					
				$feature_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
				
				$extra_arr = pjExtraModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjExtra' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
				
				$country_arr = pjCountryModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
								
				$user_arr = pjUserModel::factory()->orderBy('t1.name ASC')->findAll()->getData();
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; 
				}
				
				$this->set('arr', $arr);
				$this->set('make_arr', pjSanitize::clean($make_arr));
				$this->set('model_arr', pjSanitize::clean($model_arr));
				$this->set('feature_arr', pjSanitize::clean($feature_arr));
				$this->set('gallery_arr', pjGalleryModel::factory()->where('foreign_id', $arr['id'])->findAll()->getData());
				$this->set('extra_arr', pjSanitize::clean($extra_arr));
				$this->set('listing_extra_arr', pjListingExtraModel::factory()->where('t1.listing_id', $arr['id'])->findAll()->getDataPair(NULL, 'extra_id'));
				$this->set('country_arr', pjSanitize::clean($country_arr));
				$this->set('user_arr', pjSanitize::clean($user_arr));
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
											
				# jQuery Fancybox
				$this->appendJs('jquery.fancybox.pack.js', PJ_THIRD_PARTY_PATH . 'fancybox/js/');
				$this->appendCss('jquery.fancybox.css', PJ_THIRD_PARTY_PATH . 'fancybox/css/');
				
				# TinyMCE
				$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
				
				# Gallery plugin
				$this->appendCss('pj-gallery.css', pjObject::getConstant('pjGallery', 'PLUGIN_CSS_PATH'));
				$this->appendJsFromPlugin('ajaxupload.js', 'ajaxupload', 'pjGallery');
				$this->appendJs('jquery.gallery.js', pjObject::getConstant('pjGallery', 'PLUGIN_JS_PATH'));
				
				$this->appendJs('chosen.jquery.min.js', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminListings.js');
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionExtend()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			if (isset($_POST['extend']))
			{
				$pjListingModel = pjListingModel::factory();
			
				$arr = $pjListingModel->find($_POST['id'])->getData();
				$period_arr = pjPeriodModel::factory()->find($_POST['period_id'])->getData();

				if (count($arr) > 0 && count($period_arr) > 0)
				{
					$current = time();
					if ($arr['last_extend'] == 'paid' && !empty($arr['expire']) && $arr['expire'] != '0000-00-00')
					{
						$current = strtotime($arr['expire']);
					}
					
					$pjListingModel->modify(array(
						'last_extend' => 'free',
						'expire' => date("Y-m-d", $current + (int) $period_arr['days'] * 86400)
					));
				}
			}
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL12");
			
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPayment()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			$arr = pjListingModel::factory()
				->select('t1.*, t2.content AS listing_title')
				->join('pjMultiLang', "t2.model='pjListing' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left')
				->find($_GET['id'])->getData();
				
			if (count($arr) === 0)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL08");
			} elseif ($this->isOwner() && $arr['owner_id'] != $this->getUserId()) {
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminListings&action=pjActionIndex&err=AL11");
			}
			$this->set('arr', $arr);
			$this->set('period_arr', pjPeriodModel::factory()->orderBy('t1.days ASC')->findAll()->getData());
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteListing()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if (pjListingModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjListing')->where('foreign_id', $_GET['id'])->eraseAll();
				
				pjListingExtraModel::factory()->where('listing_id', $_GET['id'])->eraseAll();
								
				$pjGalleryModel = pjGalleryModel::factory();
				$arr = $pjGalleryModel->where('foreign_id', $_GET['id'])->findAll()->getData();
				if (count($arr) > 0)
				{
					foreach ($arr as $item)
					{
						foreach ($this->imageFiles as $file)
						{
							@clearstatcache();
							if (!empty($item[$file]) && is_file($item[$file]))
							{
								@unlink($item[$file]);
							}
						}
					}
					$pjGalleryModel->eraseAll();
				}
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteListingBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjListingModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjListing')->whereIn('foreign_id', $_POST['record'])->eraseAll();
				
				pjListingExtraModel::factory()->whereIn('listing_id', $_POST['record'])->eraseAll();
				
				$arr = pjGalleryModel::factory()->whereIn('foreign_id', $_POST['record'])->findAll()->getData();
				if (count($arr) > 0)
				{
					pjGalleryModel::factory()->whereIn('foreign_id', $_POST['record'])->eraseAll();
					foreach ($arr as $item)
					{
						foreach ($this->imageFiles as $file)
						{
							@clearstatcache();
							if (!empty($item[$file]) && is_file($item[$file]))
							{
								@unlink($item[$file]);
							}
						}
					}
				}
			}
		}
		exit;
	}
	
	public function pjActionExpireListing()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjListingModel::factory()->whereIn('id', $_POST['record'])->modifyAll(array('expire' => ':DATE_ADD(`expire`, INTERVAL 30 DAY)'));
			} elseif (isset($_GET['id']) && (int) $_GET['id'] > 0) {
				pjListingModel::factory()->where('id', $_GET['id'])->limit(1)->modifyAll(array('expire' => ':DATE_ADD(`expire`, INTERVAL 30 DAY)'));
			}
		}
		exit;
	}
	
	public function pjActionSaveListing()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjListingModel = pjListingModel::factory();
			if (!in_array($_POST['column'], $pjListingModel->i18n))
			{
				if (in_array($_POST['column'], array('expire')))
				{
					$_POST['value'] = pjUtil::formatDate($_POST['value'], $this->option_arr['o_date_format']);
				}
				$value = $_POST['value'];
				
				$data = array();
				$data[$_POST['column']] = $value;
				if($_POST['column'] == 'status' && $_POST['value'] == 'E')
				{
					$arr = $pjListingModel->find($_GET['id'])->getData();
					if(empty($arr['expire']) || $arr['expire'] <= date("Y-m-d"))
					{
						$data['expire'] = date("Y-m-d", strtotime("-1 day"));
					}
				}
				$pjListingModel->reset()->where('id', $_GET['id'])->limit(1)->modifyAll($data);
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjListing', 'data');
			}
		}
		exit;
	}
	
	public function pjActionStatusListing()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0 && isset($_GET['status']) && in_array($_GET['status'], array('T', 'F', 'E')))
			{
				pjListingModel::factory()->whereIn('id', $_POST['record'])->modifyAll(array('status' => $_GET['status']));
			}
		}
		exit;
	}
	
	public function pjActionGetModels()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$model_arr = pjCarModModel::factory()->select('t1.*, t2.content AS name')
				->join('pjMultiLang', "t2.model='pjCarMod' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'")
				->where('t1.status', 'T')->where('make_id', $_GET['id'])->orderBy('name ASC')->findAll()->getData();
			$this->set('model_arr', pjSanitize::clean($model_arr));
		}
	}
	
	public function pjActionGetLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_GET['locale']) && (int) $_GET['locale'] > 0)
			{
				pjAppController::setFields($_GET['locale']);
				
				$feature_arr = pjFeatureModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjFeature' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".pjObject::escapeString($_GET['locale'])."'", 'inner')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
					
				$this->set('feature_arr', $feature_arr);
			}
		}
	}
	
	public function pjActionExport()
	{
		$this->checkLogin();
	
		if ($this->isAdmin())
		{
			if(isset($_POST['cars_export']))
			{
				$pjListingModel = pjListingModel::factory()
					->select("t1.*, t2.name as owner, t3.content as title, t4.content as description, t5.content as car_model, t6.content as car_make")
					->join('pjUser', "t2.id=t1.owner_id", 'left')
					->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left')
					->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left')
					->join('pjMultiLang', "t5.model='pjCarMod' AND t5.foreign_id=t1.model_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left')
					->join('pjMultiLang', "t6.model='pjMake' AND t6.foreign_id=t1.make_id ANd t6.field='name' AND t6.locale='".$this->getLocaleId()."'", 'left');
					
				$column = 't1.created';
				$direction = 'ASC';
				$where_str = pjUtil::getMadeWhere($_POST['made_period'], $this->option_arr['o_week_start']);
				if($where_str != '')
				{
					$pjListingModel->where($where_str);
				}
				if($this->isOwner())
				{
					$pjListingModel->where("t1.owner_id", $this->getUserId());
				}
				$arr= $pjListingModel
					->orderBy("$column $direction")
					->findAll()
					->getData();
				if($_POST['type'] == 'file')
				{
					$this->setLayout('pjActionEmpty');
	
					if($_POST['format'] == 'csv')
					{
						$csv = new pjCSV();
						$csv
							->setHeader(true)
							->setName("Export-".time().".csv")
							->process($arr)
							->download();
					}
					if($_POST['format'] == 'xml')
					{
						$xml = new pjXML();
						$xml
							->setEncoding('UTF-8')
							->setName("Export-".time().".xml")
							->process($arr)
							->download();
					}
					exit;
				}else{
					$pjPasswordModel = pjPasswordModel::factory();
					$password = md5($_POST['password'].PJ_SALT);
					$arr = $pjPasswordModel
						->where("t1.password", $password)
						->where("t1.format", $_POST['format'])
						->where("t1.period", $_POST['made_period'])
						->limit(1)
						->findAll()
						->getData();
					if (count($arr) != 1)
					{
						$pjPasswordModel->setAttributes(array('password' => $password, 'format' => $_POST['format'], 'period' => $_POST['made_period']))->insert();
					}
					$this->set('password', $password);
				}
			}
	
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminListings.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionExportFeed()
	{
		$this->setLayout('pjActionEmpty');
		$access = true;
		if(isset($_GET['p']))
		{
			$pjPasswordModel = pjPasswordModel::factory();
			$arr = $pjPasswordModel
				->where('t1.password', $_GET['p'])
				->where("t1.format", $_GET['format'])
				->where("t1.period", $_GET['period'])
				->limit(1)
				->findAll()
				->getData();
			if (count($arr) != 1)
			{
				$access = false;
			}
		}
		if($access == true)
		{
			$arr = $this->pjGetFeedData($_GET);
			if(!empty($arr))
			{
				if($_GET['format'] == 'xml')
				{
					$xml = new pjXML();
					echo $xml
						->setEncoding('UTF-8')
						->process($arr)
						->getData();
	
				}
				if($_GET['format'] == 'csv')
				{
					$csv = new pjCSV();
					echo $csv
						->setHeader(true)
						->process($arr)
						->getData();
	
				}
			}
		}else{
			__('lblNoAccessToFeed');
		}
		exit;
	}
	public function pjGetFeedData($get)
	{
		$arr = array();
		$status = true;
		$period = '';
		if(isset($get['period']))
		{
			if(!ctype_digit($get['period']))
			{
				$status = false;
			}else{
				$period = $get['period'];
			}
		}else{
			$status = false;
		}

		if($status == true && $period != '')
		{
			$pjListingModel = pjListingModel::factory()
				->select("t1.*, t2.name as owner, t3.content as title, t4.content as description, t5.content as car_model, t6.content as car_make")
				->join('pjUser', "t2.id=t1.owner_id", 'left')
				->join('pjMultiLang', "t3.model='pjListing' AND t3.foreign_id=t1.id AND t3.field='title' AND t3.locale='".$this->getLocaleId()."'", 'left')
				->join('pjMultiLang', "t4.model='pjListing' AND t4.foreign_id=t1.id AND t4.field='description' AND t4.locale='".$this->getLocaleId()."'", 'left')
				->join('pjMultiLang', "t5.model='pjCarMod' AND t5.foreign_id=t1.model_id AND t5.field='name' AND t5.locale='".$this->getLocaleId()."'", 'left')
				->join('pjMultiLang', "t6.model='pjMake' AND t6.foreign_id=t1.make_id ANd t6.field='name' AND t6.locale='".$this->getLocaleId()."'", 'left');
				
	
			$column = 't1.created';
			$direction = 'DESC';
			$where_str = pjUtil::getMadeWhere($period, $this->option_arr['o_week_start']);
			if($where_str != '')
			{
				$pjListingModel->where($where_str);
			}
			$arr= $pjListingModel
			->orderBy("$column $direction")
			->findAll()
			->getData();
		}
		return $arr;
	}
	
	public function pjActionGetPassword()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjPasswordModel = pjPasswordModel::factory();
	
			$column = 'id';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}
	
			$total = $pjPasswordModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
	
			$data = $pjPasswordModel->select("t1.*")->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
	
			$export_formats = __('export_formats', true, false);
			$made_arr = __('made_arr', true, false);
			foreach($data as $k => $v)
			{
				$v['params'] = '&format=' . $v['format'] . '&period=' . $v['period'] . '&p=' . $v['password'];
				
				$v['period'] = $made_arr[$v['period']];
				$v['format'] = $export_formats[$v['format']];
				$data[$k] = $v;
			}
	
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	public function pjActionDeletePassword()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if (pjPasswordModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				$response['code'] = 200;
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeletePasswordBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjPasswordModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
}
?>