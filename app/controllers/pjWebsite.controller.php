<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjWebsite extends pjListings
{
	protected $locales = array();
	
	public function __construct()
	{
		$this->setLayout('pjActionWebsite');
	}
	
	public function afterFilter()
	{
		$theme = $this->option_arr['o_theme'];
		if(isset($_SESSION[$this->defaultTheme]))
		{
			$theme = $_SESSION[$this->defaultTheme];
		}
		
		$this->appendCss('bootstrap.min.css', PJ_THIRD_PARTY_PATH . 'bootstrap/css/');
		$this->appendCss('flexslider.css',PJ_TEMPLATE_PATH . PJ_TEMPLATE_WEBSITE_PATH . 'css/');
		$this->appendCss('jquery-ui-1.9.2.custom.min.css', PJ_LIBS_PATH . 'pjQ/css/');
		$this->appendCss('style.css', PJ_TEMPLATE_PATH . PJ_TEMPLATE_WEBSITE_PATH . 'css/');
		$this->appendCss($theme . '.css', PJ_TEMPLATE_PATH . PJ_TEMPLATE_WEBSITE_PATH . 'css/');
	}
		
    public function pjActionAboutUs()
    {
    	$this->set('meta_arr', array(
    		'title' => __('ws_about_meta_title', true),
    		'keywords' => __('ws_about_meta_keywords', true),
    		'description' => __('ws_about_meta_description', true),
    	));
    	
    	$this->appendCss('index.php?controller=pjNewsletter&action=pjActionLoadCss', PJ_INSTALL_URL, true);
    }
    
    public function pjActionCars()
    {
    	parent::pjActionCars();
    	
    	$this->set('meta_arr', array(
    		'title' => __('ws_cars_meta_title', true),
    		'keywords' => __('ws_cars_meta_keywords', true),
    		'description' => __('ws_cars_meta_description', true),
    	));
    }

    public function pjActionCompare()
    {
    	parent::pjActionCompare();
    	
    	$this->set('meta_arr', array(
    		'title' => __('ws_compare_meta_title', true),
    		'keywords' => __('ws_compare_meta_keywords', true),
    		'description' => __('ws_compare_meta_description', true),
    	));
    }
    
    public function pjActionContactUs()
    {
    	$this->set('meta_arr', array(
    		'title' => __('ws_contact_meta_title', true),
    		'keywords' => __('ws_contact_meta_keywords', true),
    		'description' => __('ws_contact_meta_description', true),
    	));
    	
    	$this->appendCss('index.php?controller=pjForm&action=pjActionLoadCss&fid=1', PJ_INSTALL_URL, true);
    	$this->appendJs('index.php?controller=pjForm&action=pjActionLoadJs&fid=1', PJ_INSTALL_URL, true);
    }
    
    public function pjActionIndex()
    {
    	$this->set('meta_arr', array(
    		'title' => __('ws_home_meta_title', true),
    		'keywords' => __('ws_home_meta_keywords', true),
    		'description' => __('ws_home_meta_description', true),
    	));
    
    	$make_arr = pjMakeModel::factory()
	    	->select('t1.*, t2.content AS name')
	    	->join('pjMultiLang', "t2.model='pjMake' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left')
	    	->where('status', 'T')
	    	->where("t1.id IN(SELECT TL.make_id FROM `".pjListingModel::factory()->getTable()."` AS `TL` WHERE (TL.status = 'T' OR (TL.status = 'E' AND TL.expire >= CURDATE())) )")
	    	->orderBy('name ASC')
	    	->findAll()
	    	->getData();
    
    	$this->set('make_arr', $make_arr);
    	$this->set('featured_arr', $this->getFeaturedCars($this->option_arr, $this->getLocaleId()));
    
    	$this->appendCss('index.php?controller=pjNewsletter&action=pjActionLoadCss', PJ_INSTALL_URL, true);
    }
    
    public function pjActionLogin()
    {
    	parent::pjActionLogin();
    	
    	$this->set('meta_arr', array(
    		'title' => __('ws_login_meta_title', true),
    		'keywords' => __('ws_login_meta_keywords', true),
    		'description' => __('ws_login_meta_description', true),
    	));
    }
    
    public function pjActionRegister()
    {
    	parent::pjActionRegister();
    	
    	$this->set('meta_arr', array(
    		'title' => __('ws_register_meta_title', true),
    		'keywords' => __('ws_register_meta_keywords', true),
    		'description' => __('ws_register_meta_description', true),
    	));
    }
    
    public function pjActionSearch()
    {
    	parent::pjActionSearch();
    	 
    	$this->set('meta_arr', array(
    			'title' => __('ws_search_meta_title', true),
    			'keywords' => __('ws_search_meta_keywords', true),
    			'description' => __('ws_search_meta_description', true),
    	));
    }
    
    public function pjActionTerms()
    {
    	$this->set('meta_arr', array(
    		'title' => __('ws_terms_meta_title', true),
    		'keywords' => __('ws_terms_meta_keywords', true),
    		'description' => __('ws_terms_meta_description', true),
    	));
    }
    
    public function pjActionView()
    {
    	parent::pjActionView();
    	
    	$meta_arr = $this->get('meta_arr');
    	if (!isset($meta_arr['title']) || empty($meta_arr['title']))
    	{
    		$meta_arr['title'] = __('ws_details_meta_title', true);
    	}
    	if (!isset($meta_arr['keywords']) || empty($meta_arr['keywords']))
    	{
    		$meta_arr['keywords'] = __('ws_details_meta_keywords', true);
    	}
    	if (!isset($meta_arr['description']) || empty($meta_arr['description']))
    	{
    		$meta_arr['description'] = __('ws_details_meta_description', true);
    	}
    	$this->set('meta_arr', $meta_arr);
    }
}
?>