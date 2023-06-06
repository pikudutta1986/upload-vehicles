<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController
{
	public $defaultUser = 'admin_user';
	
	public $requireLogin = true;
	
	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			if (!$this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview', 'pjActionExportFeed')))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
			}
		}
	}
	
	public function beforeRender()
	{
		
	}

	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
		{
			$pjUserModel = pjUserModel::factory();
			$pjListingModel = pjListingModel::factory();

			$cnt_cars = 0;	
			if ($this->isOwner())
			{
				$cnt_cars = $pjListingModel->where('t1.owner_id', $this->getUserId())->findCount()->getData();
			}else{
				$cnt_cars = $pjListingModel->findCount()->getData();
			}
			
			$pjListingModel->reset()
				->select(sprintf("t1.id, t1.views, t2.content AS title,
					(SELECT `medium_path` FROM `%s` WHERE `foreign_id` = `t1`.`id` ORDER BY `sort` ASC LIMIT 1) AS `pic`",
					pjGalleryModel::factory()->getTable()))
				->join('pjMultiLang', "t2.model='pjListing' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left')
				->limit(3)
				->orderBy('t1.views DESC');
				
			
			if ($this->isOwner())
			{
				$pjListingModel->where('t1.owner_id', $this->getUserId());
				
			}
			$listing_arr = $pjListingModel->findAll()->getData();
						
			$pjListingModel->reset()->where('t1.status', 'E');
			$pjListingModel->where('t1.expire =', date('Y-m-d'));	
			if ($this->isOwner())
			{
				$pjListingModel->where('t1.owner_id', $this->getUserId());
				
			}
			$cnt_expire_arr = $pjListingModel->findCount()->getData();
			
			$pjListingModel->reset()
				->select(sprintf("t1.id, t1.views, t1.listing_price, t1.listing_year, t2.content AS title, t3.content as make, t4.content as model, 
					(SELECT `medium_path` FROM `%s` WHERE `foreign_id` = `t1`.`id` ORDER BY `sort` ASC LIMIT 1) AS `pic`",
					pjGalleryModel::factory()->getTable()))
				->join('pjMultiLang', "t2.model='pjListing' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left')
				->join('pjMultiLang', "t3.model='pjMake' AND t3.foreign_id=t1.make_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left')
				->join('pjMultiLang', "t4.model='pjCarMod' AND t4.foreign_id=t1.model_id AND t4.field='name' AND t4.locale='".$this->getLocaleId()."'", 'left')
				->limit(3)
				->orderBy('t1.created DESC');
			if ($this->isOwner())
			{
				$pjListingModel->where('t1.owner_id', $this->getUserId());
				
			}
			$latest_listing_arr = $pjListingModel->findAll()->getData();
			
			$this->set('listing_arr', $listing_arr);
			$this->set('cnt_cars', $cnt_cars);
			$this->set('cnt_expire_arr', $cnt_expire_arr);
			$this->set('latest_listing_arr', $latest_listing_arr);
			
			if (!$this->isOwner())
			{
				$user_arr = $pjUserModel
					->select(sprintf("t1.id, t1.name, t1.email, t1.last_login,
						(SELECT COUNT(*) FROM `%s` WHERE `owner_id` = `t1`.`id` LIMIT 1) AS `cnt_listings`",
						$pjListingModel->getTable()))
					->where('t1.role_id', 3)
					->orderBy('created DESC')
					->limit(4)->findAll()->getData();
				$this->set('user_arr', $user_arr);
				$this->set('cnt_users', $pjUserModel->reset()->findCount()->getData());
			}
			
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionForgot()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['forgot_user']))
		{
			if (!isset($_POST['forgot_email']) || !pjValidation::pjActionNotEmpty($_POST['forgot_email']) || !pjValidation::pjActionEmail($_POST['forgot_email']))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			}
			$pjUserModel = pjUserModel::factory();
			$user = $pjUserModel
				->where('t1.email', $_POST['forgot_email'])
				->limit(1)
				->findAll()
				->getData();
				
			if (count($user) != 1)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			} else {
				$user = $user[0];
				
				$Email = new pjEmail();
				$Email
					->setTo($user['email'])
					->setFrom($user['email'])
					->setSubject(__('emailForgotSubject', true));
				
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass'])
					;
				}
				
				$body = str_replace(
					array('{Name}', '{Password}'),
					array($user['name'], $user['password']),
					__('emailForgotBody', true)
				);

				if ($Email->send($body))
				{
					$err = "AA11";
				} else {
					$err = "AA12";
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=$err");
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionMessages()
	{
		$this->setAjax(true);
		header("Content-Type: text/javascript; charset=utf-8");
	}
	
	public function pjActionLogin()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['login_user']))
		{
			if (!isset($_POST['login_email']) || !isset($_POST['login_password']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_email']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_password']) ||
				!pjValidation::pjActionEmail($_POST['login_email']))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=4");
			}
			$pjUserModel = pjUserModel::factory();

			$user = $pjUserModel
				->where('t1.email', $_POST['login_email'])
				->where(sprintf("t1.password = AES_ENCRYPT('%s', '%s')", pjObject::escapeString($_POST['login_password']), PJ_SALT))
				->limit(1)
				->findAll()
				->getData();

			if (count($user) != 1)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=1");
			} else {
				$user = $user[0];
				unset($user['password']);
															
				if (!in_array($user['role_id'], array(1,2,3)))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=2");
				}
				
				if ($user['status'] != 'T')
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=3");
				}
				
				$last_login = date("Y-m-d H:i:s");
    			$_SESSION[$this->defaultUser] = $user;
    			
    			$data = array();
    			$data['last_login'] = $last_login;
    			$pjUserModel->reset()->setAttributes(array('id' => $user['id']))->modify($data);

    			if ($this->isAdmin() || $this->isEditor() || $this->isOwner())
    			{
	    			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
    			}
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionLogout()
	{
		if ($this->isLoged())
        {
        	unset($_SESSION[$this->defaultUser]);
        }
       	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
	}
	
	public function pjActionProfile()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			if (isset($_POST['profile_update']))
			{
				$pjUserModel = pjUserModel::factory();
				$arr = $pjUserModel->find($this->getUserId())->getData();
				$data = array();
				$data['role_id'] = $arr['role_id'];
				$data['status'] = $arr['status'];
				$post = array_merge($_POST, $data);
				if (!$pjUserModel->validates($post))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA14");
				}
				$pjUserModel->set('id', $this->getUserId())->modify($post);
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA13");
			} else {
				$country_arr = pjCountryModel::factory()->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
					->where('status', 'T')->orderBy('name ASC')->findAll()->getData();
				
				$this->set('country_arr', $country_arr);
				$this->set('arr', pjUserModel::factory()->find($this->getUserId())->getData());
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('pjAdmin.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSetLocale()
	{
		if (isset($_GET['id']) && (int) $_GET['id'] > 0 )
		{
			$this->setLocaleId($_GET['id']);
		}
		pjUtil::redirect(sprintf("%sindex.php?controller=pjAdmin&action=pjActionIndex", PJ_INSTALL_URL));
	}
}
?>