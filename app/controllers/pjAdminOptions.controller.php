<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminOptions extends pjAdmin
{
	public function pjActionDeletePeriod()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array('code' => 100);
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$response['code'] = 101;
				if (pjPeriodModel::factory()->setAttributes(array('id' => $_POST['id']))->erase()->getAffectedRows() == 1)
				{
					$response['code'] = 200;
				}
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
		
	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin() || $this->isEditor())
		{
			$arr = pjOptionModel::factory()
				->where('t1.foreign_id', $this->getForeignId())
				->orderBy('t1.order ASC')
				->findAll()
				->getData();
			
			$this->set('arr', $arr);
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			if (isset($_POST['options_update']))
			{
				$OptionModel = new pjOptionModel();
			
				foreach ($_POST as $key => $value)
				{
					if (preg_match('/value-(string|text|int|float|enum|bool|color)-(.*)/', $key) === 1)
					{
						list(, $type, $k) = explode("-", $key);
						if (!empty($k))
						{
							$OptionModel
								->reset()
								->where('foreign_id', $this->getForeignId())
								->where('`key`', $k)
								->limit(1)
								->modifyAll(array('value' => $value));
						}
					}
				}
				if (isset($_POST['price']) && count($_POST['price']) > 0 && isset($_POST['days']) && count($_POST['days']) > 0)
				{
					$pjPeriodModel = pjPeriodModel::factory();
					foreach ($_POST['price'] as $key => $value)
					{
						if (strpos($key, 'new_') === 0)
						{
							$pjPeriodModel->reset()->setAttributes(array('days' => $_POST['days'][$key], 'price' => $_POST['price'][$key]))->insert();
						} else {
							$pjPeriodModel->reset()->setAttributes(array('id' => $key))->modify(array('days' => $_POST['days'][$key], 'price' => $_POST['price'][$key]));
						}
					}
				}
				
				$err = NULL;
				
				if(isset($_POST['email_create']))
				{
					$data = array();
					$data['user_id'] = $this->getUserId();
					$id = pjNotificationModel::factory()->setAttributes($data)->insert()->getInsertId();
					if ($id !== false && (int) $id > 0)
					{
						if (isset($_POST['i18n']))
						{
							pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjNotification');
						}
					}
					$err = 'AO04';
				}
				if(isset($_POST['email_update']))
				{
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjNotification');
					}
					$err = 'AO05';
				}
				if(isset($_POST['seo_update']))
				{
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], 1, 'pjOption', 'data');
					}
				}
				if (isset($_POST['next_action']))
				{
					switch ($_POST['next_action'])
					{
						case 'pjActionIndex':
							$err = 'AO01';
							break;
						case 'pjActionSubmissions':
							$err = 'AO02';
							break;
						case 'pjActionInstall':
							$err = 'AO03';
							if(isset($_POST['seo_update']))
							{
								$err = 'AO03&tab_id=' . $_POST['tab_id'] . '&seo_tab_id=' . $_POST['seo_tab_id'];
							}
							break;
						case 'pjActionEmails':
							break;
					}
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=" . @$_POST['next_action'] . "&err=$err");
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSubmissions()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$OptionModel = new pjOptionModel();
			$this->set('arr', $OptionModel->where('t1.foreign_id', $this->getForeignId())->orderBy('t1.order ASC')->findAll()->getData());
			$this->set('o_arr', $OptionModel->getPairs($this->getForeignId()));
			$this->set('period_arr', pjPeriodModel::factory()->orderBy('t1.days ASC')->findAll()->getData());
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionEmails()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$user_id = $this->getUserId();
			$arr = array();
			
			$notify_arr = pjNotificationModel::factory()->where('t1.user_id', $user_id)->findAll()->getData();
			if(count($notify_arr) > 0)
			{
				$arr = $notify_arr[0];
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjNotification');
			}
			
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->where('t2.file IS NOT NULL')
				->orderBy('t1.sort ASC')->findAll()->getData();
			
			$lp_arr = array();
			foreach ($locale_arr as $item)
			{
				$lp_arr[$item['id']."_"] = $item['file']; 
			}
			$this->set('lp_arr', $locale_arr);
			$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
			
			$this->set('arr', $arr);
			
			$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionInstall()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->set('o_arr', $this->models['Option']
				->where('t1.foreign_id', $this->getForeignId())
				->orderBy('t1.key ASC')
				->findAll()
				->getData()
			);
			
			$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->where('t2.file IS NOT NULL')
				->orderBy('t1.sort ASC')->findAll()->getData();
			
			$lp_arr = array();
			foreach ($locale_arr as $item)
			{
				$lp_arr[$item['id']."_"] = $item['file'];
			}
			$this->set('lp_arr', $locale_arr);
			$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
			
			$arr = array();
			$arr['id'] = 1;
			$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang(1, 'pjOption');
			$this->set('arr', $arr);
			
			$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPreview()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdateTheme()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjOptionModel::factory()
				->where('foreign_id', $this->getForeignId())
				->where('`key`', 'o_theme')
				->limit(1)
				->modifyAll(array('value' => 'theme1|theme2|theme3|theme4|theme5|theme6|theme7|theme8|theme9|theme10::theme' . $_GET['theme']));
	
		}
	}
}
?>