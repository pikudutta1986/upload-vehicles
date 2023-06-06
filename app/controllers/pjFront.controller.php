<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFront extends pjAppController
{
	public $defaultCaptcha = 'StivaSoftCaptcha';
	
	public $defaultTheme = 'front_theme_id';
	
	public $compareList = 'CRLCompareList';
	
	public function __construct()
	{
		$this->setLayout('pjActionFront');
	}
	
	public function afterFilter()
	{
		$theme = $this->option_arr['o_theme'];
		if(isset($_SESSION[$this->defaultTheme]))
		{
			$theme = $_SESSION[$this->defaultTheme];
		}
		$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		$this->appendCss('pj.bootstrap.min.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
		$this->appendCss('jquery-ui.css', PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_jquery_slider')), true);
		$this->appendCss('style.css', PJ_TEMPLATE_PATH . 'default/css/');
		$this->appendCss($theme . '.css', PJ_TEMPLATE_PATH . 'default/css/');
		
	}
	
	public function beforeFilter()
	{
		$locale_arr = pjLocaleModel::factory()
			->select('t1.*, t2.file, t2.title')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC')
			->findAll()
			->getDataPair('id');
		
		$this->set('locale_arr', $locale_arr);
		
		$OptionModel = pjOptionModel::factory();
		$this->option_arr = $OptionModel->getPairs($this->getForeignId());
		$this->set('option_arr', $this->option_arr);
		$this->setTime();
		
		if (!isset($_SESSION[$this->defaultLocale]))
		{
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1)
			{
				$this->setLocaleId($locale_arr[0]['id']);
			}
		}
		if(isset($_GET['theme']))
		{
			$theme = pjObject::escapeString($_GET['theme']);
			if(!empty($theme))
			{
				$_SESSION[$this->defaultTheme] = $theme;
			}
		}
		$this->loadSetFields(true);
	}
	
	public function beforeRender()
	{
		if (isset($_GET['iframe']))
		{
			$this->setLayout('pjActionIframe');
		}
	}
	
	public function pjActionSetLocale()
	{
		$this->setLocaleId(@$_GET['locale']);
		$this->loadSetFields(true);
		pjUtil::redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function pjActionCaptcha()
	{
		$this->setAjax(true);
		
		$Captcha = new pjCaptcha(PJ_INSTALL_PATH.'app/web/obj/Anorexia.ttf', $this->defaultCaptcha, 6);
		$Captcha->setImage(PJ_INSTALL_PATH.'app/web/img/button.png')->init(isset($_GET['rand']) ? $_GET['rand'] : null);
	}

	public function pjActionCheckCaptcha()
	{
		$this->setAjax(true);
				
		if (!isset($_GET['captcha']) || empty($_GET['captcha']) || strtoupper($_GET['captcha']) != $_SESSION[$this->defaultCaptcha]){
			echo 'false';
		}else{
			echo 'true';
		}
		
	}
}
?>