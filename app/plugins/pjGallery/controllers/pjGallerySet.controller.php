<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjGallerySet extends pjGalleryAppController
{
	private $imageFiles = array('small_path', 'medium_path', 'large_path', 'source_path');
	
	public function pjActionGetGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjGallerySetModel = pjGallerySetModel::factory()
				->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjGallerySet' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left');
				
			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjGallerySetModel->escapeStr($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), $q);
				$pjGallerySetModel->where(sprintf("(t2.content LIKE '%1\$s')", "%$q%"));
			}
				
			if (isset($_GET['status']) && in_array($_GET['status'], array('T', 'F')))
			{
				$pjGallerySetModel->where('t1.status', $_GET['status']);
			}
				
			$column = 'name';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}
	
			$total = (int) $pjGallerySetModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}
			$data = $pjGallerySetModel
				->select(sprintf("t1.*, t2.content AS `name`, 
					(SELECT `small_path` FROM `%1\$s` WHERE `foreign_id` = t1.id AND `model` = 'pjGallerySet' ORDER BY `sort` ASC LIMIT 1) AS `thumb`,
					(SELECT COUNT(*) FROM `%1\$s` WHERE `foreign_id` = t1.id AND `model` = 'pjGallerySet') AS `cnt_photos`", 
					pjGalleryModel::factory()->getTable()))
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
	
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
	
		if(defined("PJ_IS_MULTI_GALLERIES") && PJ_IS_MULTI_GALLERIES == true)
		{
			if ($this->isGalleryReady())
			{
				$this->appendCss('pj-gallery.css', $this->getConst('PLUGIN_CSS_PATH'));
				$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('pjGallerySet.js', $this->getConst('PLUGIN_JS_PATH'));
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			} else {
				$this->set('status', 2);
			}
		}else{
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjGallerySet&action=pjActionUpdate&id=1");
		}
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
	
		if ($this->isGalleryReady() && $this->getConst('PLUGIN_ADMIN_MODE') == true)
		{
			if (isset($_POST['gallery_create']))
			{
				$id = pjGallerySetModel::factory($_POST)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					$err = 'PGS03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $id, 'pjGallerySet');
					}
					pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjGallerySet&action=pjActionUpdate&id=".$id."&err=$err");
				} else {
					$err = 'PGS04';
				}
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjGallerySet&action=pjActionIndex&err=$err");
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
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
	
				$this->set('is_flag_ready', $this->requestAction(array('controller' => 'pjLocale', 'action' => 'pjActionIsFlagReady'), array('return')));
	
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjGallerySet.js', $this->getConst('PLUGIN_JS_PATH'));
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
	
		if ($this->isGalleryReady())
		{
			if (isset($_POST['gallery_update']))
			{
				$pjGallerySetModel = pjGallerySetModel::factory();
				
				$gallery_set_arr = $pjGallerySetModel->find($_POST['id'])->getData();
				
				$pjGallerySetModel->reset()->where('id', $_POST['id'])->limit(1)->modifyAll($_POST);
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjGallerySet');
				}
				
				$medium_changed = false;
				$small_changed = false;
				if((int) $_POST['medium_width'] > 0 && (int) $_POST['medium_height'] > 0)
				{	
					if($gallery_set_arr['medium_width'] != $_POST['medium_width'] || $gallery_set_arr['medium_height'] != $_POST['medium_height'])
					{
						$medium_changed = true;
					}
				}
				if((int) $_POST['small_width'] > 0 && (int) $_POST['small_height'] > 0)
				{
					if($gallery_set_arr['small_width'] != $_POST['small_width'] || $gallery_set_arr['small_height'] != $_POST['small_height'])
					{
						$small_changed = true;
					}
				}
				if($medium_changed == true || $small_changed == true)
				{
					$gallery_arr = pjGalleryModel::factory()->where('foreign_id', $_POST['id'])->where('model', 'pjGallerySet')->findAll()->getData();
					
					$Image = new pjImage();
					foreach($gallery_arr as $item)
					{
						if($medium_changed == true)
						{
							$dst = PJ_INSTALL_PATH . $item['medium_path'];
							
							if(file_exists($dst))
							{
								unlink($dst);
							}
							$Image->loadImage($item['source_path']);
							$Image->resizeSmart($_POST['medium_width'], $_POST['medium_height']);
							$Image->saveImage($dst);
						}
						if($small_changed == true)
						{
							$dst = PJ_INSTALL_PATH . $item['small_path'];
							if(file_exists($dst))
							{
								unlink($dst);
							}
							$Image->loadImage($item['source_path']);
							$Image->resizeSmart($_POST['small_width'], $_POST['small_height']);
							$Image->saveImage($dst);
						}
					}
				}
				
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjGallerySet&action=pjActionUpdate&id=".$_POST['id']."&tab_id=".$_POST['tab_id']."&err=PGS01");
	
			} else {
				$arr = pjGallerySetModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjGallerySet&action=pjActionIndex&errPGS08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjGallerySet');
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
				$this->set('lp_arr', $locale_arr);
				$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
				$this->set('is_flag_ready', $this->requestAction(array('controller' => 'pjLocale', 'action' => 'pjActionIsFlagReady'), array('return')));
	
				$this->appendCss('pj-gallery.css', pjObject::getConstant('pjGallery', 'PLUGIN_CSS_PATH'));
				$this->appendJsFromPlugin('ajaxupload.js', 'ajaxupload', 'pjGallery');
				$this->appendJs('jquery.gallery.js', pjObject::getConstant('pjGallery', 'PLUGIN_JS_PATH'));
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjGallerySet.js', $this->getConst('PLUGIN_JS_PATH'));
				$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->getConst('PLUGIN_ADMIN_MODE') == true)
		{
			$forbidden = array(1);
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && !in_array((int) $_GET['id'], $forbidden))
			{
				if (pjGallerySetModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
				{
					pjMultiLangModel::factory()->where('model', 'pjGallerySet')->where('foreign_id', $_GET['id'])->eraseAll();
					
					$pjGalleryModel = pjGalleryModel::factory();
					$arr = $pjGalleryModel->where('foreign_id', $_GET['id'])->where('model', 'pjGallerySet')->findAll()->getData();
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
					
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Gallery has been deleted.'));
				}else{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteGalleryBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$forbidden = array(1);
			if (isset($_POST['record']) && !empty($_POST['record']) && $this->getConst('PLUGIN_ADMIN_MODE') == true)
			{
				pjGallerySetModel::factory()->whereNotIn('id', $forbidden)->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjGallerySet')->whereNotIn('id', $forbidden)->whereIn('foreign_id', $_POST['record'])->eraseAll();
				$pjGalleryModel = pjGalleryModel::factory();
				$arr = $pjGalleryModel->whereNotIn('foreign_id', $forbidden)->whereIn('foreign_id', $_POST['record'])->where('model', 'pjGallerySet')->findAll()->getData();
				if (count($arr) > 0)
				{
					$pjGalleryModel->eraseAll();
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
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Galleries has been deleted.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionSaveGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if ($_SERVER["REQUEST_METHOD"] !== 'POST')
			{
				$text = 'HTTP method not allowed.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => $text));
			}
			
			if (!(isset($_POST['column'], $_POST['value'], $_GET['id']) && !empty($_POST['column']) && !empty($_GET['id'])))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$pjGallerySetModel = pjGallerySetModel::factory();
			if (!in_array($_POST['column'], $pjGallerySetModel->getI18n()))
			{
				$data = array();
				$data[$_POST['column']] = $_POST['value'];
				$pjGallerySetModel->set('id', $_GET['id'])->modify($data);
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjGallerySet');
			}
			
			$text = 'Gallery has been saved.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}
	
	public function pjActionStatusGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if ($_SERVER["REQUEST_METHOD"] !== 'POST')
			{
				$text = 'HTTP method not allowed.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => $text));
			}
			
			if (!(isset($_POST['record']) && !empty($_POST['record'])))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			pjGallerySetModel::factory()->whereIn('id', $_POST['record'])
				->modifyAll(array('status' => ":IF(`status`='F','T','F')"));
			
			$text = 'Status of selected galleries has been changed.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}
}
?>