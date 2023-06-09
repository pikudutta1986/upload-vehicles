<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminUsers extends pjAdmin
{
	public function pjActionCheckEmail()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!isset($_GET['email']) || empty($_GET['email']))
			{
				echo 'false';
				exit;
			}
			$pjUserModel = pjUserModel::factory()->where('t1.email', $_GET['email']);
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjUserModel->where('t1.id !=', $_GET['id']);
			}
			echo $pjUserModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCloneUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				$MultiLangModel = new pjMultiLangModel();

				$data = pjUserModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
				foreach ($data as $item)
				{
					$item_id = $item['id'];
					unset($item['id']);
					unset($item['email']);

					$id = pjUserModel::factory($item)->insert()->getInsertId();
					if ($id !== false && (int) $id > 0)
					{
						$_data = pjMultiLangModel::factory()->getMultiLang($item_id, 'pjUser');
						$MultiLangModel->saveMultiLang($_data, $id, 'pjUser');
					}
				}
			}
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			if (isset($_POST['user_create']))
			{
				$data = array();
				$data['is_active'] = 'T';
				$data['ip'] = $_SERVER['REMOTE_ADDR'];
				$id = pjUserModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'AU03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjUser');
					}
				} else {
					$err = 'AU04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminUsers&action=pjActionIndex&err=$err");
			} else {
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; 
				}
				
				$country_arr = pjCountryModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
				
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				$this->set('country_arr', pjSanitize::clean($country_arr));
				if ($this->isAdmin())
				{
					$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				}else{
					$this->set('role_arr', pjRoleModel::factory()->where('id', 3)->orderBy('t1.id ASC')->findAll()->getData());
				}
		
				$this->appendJs('chosen.jquery.min.js', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminUsers.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			if ($_GET['id'] != $this->getUserId() && (int) $_GET['id'] !== 1)
			{
				if (pjUserModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
				{
					pjMultiLangModel::factory()->where('model', 'pjUser')->where('foreign_id', $_GET['id'])->eraseAll();
					$response['code'] = 200;
				} else {
					$response['code'] = 100;
				}
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionDeleteUserBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjUserModel::factory()
					->where('id !=', $this->getUserId())
					->where('id != 1')
					->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjUser')->whereIn('foreign_id', $_POST['record'])->eraseAll();
			}
		}
		exit;
	}
	
	public function pjActionExportUser()
	{
		$this->checkLogin();
		
		if (isset($_POST['record']) && is_array($_POST['record']))
		{
			$arr = pjUserModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Users-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}
	
	public function pjActionGetUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjUserModel = pjUserModel::factory();
			
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = pjObject::escapeString($_GET['q']);
				$pjUserModel->where("(t1.email LIKE '%$q%' OR t1.name LIKE '%$q%')");
			}

			if (isset($_GET['status']) && !empty($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjUserModel->where('t1.status', $_GET['status']);
			}
				
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjUserModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjUserModel->select('t1.id, t1.email, t1.name, DATE(t1.created) AS created, t1.status, t1.is_active, t1.role_id, t2.role')
				->join('pjRole', 't2.id=t1.role_id', 'left')
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();

			$data = pjSanitize::clean($data);
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminUsers.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSetActive()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			$pjUserModel = pjUserModel::factory();
			
			$arr = $pjUserModel->find($_POST['id'])->getData();
			
			if (count($arr) > 0)
			{
				switch ($arr['is_active'])
				{
					case 'T':
						$sql_status = 'F';
						break;
					case 'F':
						$sql_status = 'T';
						break;
					default:
						return;
				}
				$pjUserModel->reset()->setAttributes(array('id' => $_POST['id']))->modify(array('is_active' => $sql_status));
			}
		}
		exit;
	}
	
	public function pjActionSaveUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjUserModel = pjUserModel::factory();
			if (!in_array($_POST['column'], $pjUserModel->getI18n()))
			{
				$pass = true;
				if ((int) $_GET['id'] === 1 && in_array($_POST['column'], array('role_id', 'status', 'is_active')))
				{
					$pass = false;
				}
				if ($pass)
				{
					$user = $pjUserModel->find($_GET['id'])->getData();
					$pjUserModel->reset()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
					if($_POST['column'] == 'status' && $_POST['value'] == 'T' && $user['role_id'] == 3 && $user['status'] == 'F')
					{
						$email_data['i18n'] = pjMultiLangModel::factory()->getMultiLang(1, 'pjNotification');
							
						if(!empty($email_data['i18n'][$this->getLocaleId()]['active_account_subject']) && !empty($email_data['i18n'][$this->getLocaleId()]['active_account_message']))
						{
							$subject = $email_data['i18n'][$this->getLocaleId()]['active_account_subject'];
							$message = str_replace(	array('{Email}', '{Password}', '{Name}'),
									array($user['email'], $user['password'], $user['name']),
									$email_data['i18n'][$this->getLocaleId()]['active_account_message']);
						
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
								->setSender($this->option_arr['o_smtp_user'])
								;
							}
							$pjEmail
								->setFrom($this->getFromEmail())
								->setSubject($subject)
								->setTo($user['email'])
								->send($message);
						}
					}
				}
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjUser');
			}
		}
		exit;
	}
	
	public function pjActionStatusUser()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (isset($_POST['record']) && count($_POST['record']) > 0)
			{
				pjUserModel::factory()->whereIn('id', $_POST['record'])->modifyAll(array(
					'status' => ":IF(`status`='F','T','F')"
				));
			}
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor())
		{
				
			if (isset($_POST['user_update']))
			{
				$pjUserModel = pjUserModel::factory();
				
				$arr = $pjUserModel->find($_POST['id'])->getData();
				
				$pjUserModel->reset()->where('id', $_POST['id'])->limit(1)->modifyAll($_POST);
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjUser');
				}
				
				if($arr['role_id'] == 3 && $_POST['role_id'] == 3 && $arr['status'] == 'F' && $_POST['status'] == 'T')
				{
					$arr = $pjUserModel->reset()->find($_POST['id'])->getData();
					$email_data['i18n'] = pjMultiLangModel::factory()->getMultiLang(1, 'pjNotification');
					
					if(!empty($email_data['i18n'][$this->getLocaleId()]['active_account_subject']) && !empty($email_data['i18n'][$this->getLocaleId()]['active_account_message']))
					{
						$subject = $email_data['i18n'][$this->getLocaleId()]['active_account_subject'];
						$message = str_replace(	array('{Email}', '{Password}', '{Name}'),
								array($arr['email'], $arr['password'], $arr['name']),
								$email_data['i18n'][$this->getLocaleId()]['active_account_message']);
						
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
								->setSender($this->option_arr['o_smtp_user'])
							;
						}
						$pjEmail
							->setFrom($this->getFromEmail())
							->setSubject($subject)
							->setTo($arr['email'])
							->send($message);
					}
				}
				
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminUsers&action=pjActionIndex&err=AU01");
				
			} else {
				$arr = pjUserModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminUsers&action=pjActionIndex&err=AU08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjUser');
				$this->set('arr', $arr);
				
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; 
				}
				
				$country_arr = pjCountryModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
				
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				
				if ($this->isAdmin())
				{
					$this->set('role_arr', pjRoleModel::factory()->orderBy('t1.id ASC')->findAll()->getData());
				}else{
					$this->set('role_arr', pjRoleModel::factory()->where('id', 3)->orderBy('t1.id ASC')->findAll()->getData());
				}
				$this->set('country_arr', $country_arr);
				
				$this->appendJs('chosen.jquery.min.js', PJ_THIRD_PARTY_PATH . 'chosen/');
				$this->appendCss('chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
								
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminUsers.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
}
?>