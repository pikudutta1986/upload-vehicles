<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjGallery extends pjGalleryAppController
{
	private $imageSizes = array(
		'small' => array(90, 68),
		'medium' => array(215, 161)
	);
	
	private $imageFiles = array('small_path', 'medium_path', 'large_path', 'source_path');
	
	private $imageCrop = true;
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		
		$gallery_set_id = null;
		if(defined("PJ_IS_MULTI_GALLERIES") && PJ_IS_MULTI_GALLERIES == true)
		{
			if(isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
			{
				$gallery_set_id = $_GET['foreign_id'];
			}
		}
		$isGallerySet = TRUE;
		if (isset($_GET['model']) && $_GET['model'] !== 'pjGallerySet')
		{
			$isGallerySet = FALSE;
		}
		if($gallery_set_id != null && $isGallerySet)
		{
			$gallery_set_arr = pjGallerySetModel::factory()->find($gallery_set_id)->getData();
			$this->imageSizes['small'] = array($gallery_set_arr['small_width'], $gallery_set_arr['small_height']);
			$this->imageSizes['medium'] = array($gallery_set_arr['medium_width'], $gallery_set_arr['medium_height']);
		}else{
			if (defined('PJ_TEMPLATE_PATH') && defined('PJ_TEMPLATE_WEBSITE_PATH'))
			{
				$config_file = PJ_TEMPLATE_PATH . PJ_TEMPLATE_WEBSITE_PATH . 'elements/config.inc.php';
				if (is_file($config_file))
				{
					$config = include $config_file;
					if (!defined('PJ_GALLERY_SMALL') && isset($config['sizes']['small']['width'], $config['sizes']['small']['height']))
					{
						define('PJ_GALLERY_SMALL', sprintf('%u,%u', $config['sizes']['small']['width'], $config['sizes']['small']['height']));
					}
					if (!defined('PJ_GALLERY_MEDIUM') && isset($config['sizes']['medium']['width'], $config['sizes']['medium']['height']))
					{
						define('PJ_GALLERY_MEDIUM', sprintf('%u,%u', $config['sizes']['medium']['width'], $config['sizes']['medium']['height']));
					}
				}
			}
			if (defined("PJ_GALLERY_SMALL") && strpos(PJ_GALLERY_SMALL, ",") !== FALSE)
			{
				$this->imageSizes['small'] = explode(",", preg_replace('/\s+/', '', PJ_GALLERY_SMALL));
			}
			if(isset($_SESSION['PJ_GALLERY_MEDIUM']['width']) && isset($_SESSION['PJ_GALLERY_MEDIUM']['height']))
			{
				$this->imageSizes['medium'] = array($_SESSION['PJ_GALLERY_MEDIUM']['width'], $_SESSION['PJ_GALLERY_MEDIUM']['height']);
			}else{
				if (defined("PJ_GALLERY_MEDIUM") && strpos(PJ_GALLERY_MEDIUM, ",") !== FALSE)
				{
					$this->imageSizes['medium'] = explode(",", preg_replace('/\s+/', '', PJ_GALLERY_MEDIUM));
				}
			}
		}
		
		if (defined("PJ_GALLERY_CROP"))
		{
			$this->imageCrop = (bool) PJ_GALLERY_CROP;
		}
	}
	
	private function pjActionDeleteImage($arr)
	{
		if (!is_array($arr))
		{
			$this->log('Given data is not an array');
			return FALSE;
		}
		foreach ($this->imageFiles as $file)
		{
			@clearstatcache();
			if (!empty($arr[$file]) && is_file($arr[$file]))
			{
				@unlink($arr[$file]);
			} else {
				$this->log(sprintf("%s is empty or not a file", $arr[$file]));
			}
		}
	}
	
	private function pjActionBuildFromSource(&$Image, $item, $watermark=NULL, $watermarkPosition="cc")
	{
		$data = array();
		if (empty($item['source_path']))
		{
			$this->log('source_path is empty');
			return FALSE;
		}
		foreach ($this->imageSizes as $key => $d)
		{
			if (isset($item[$key . '_path']) && !empty($item[$key . '_path']))
			{
				$dst = $item[$key . '_path'];
			} else {
				$dst = str_replace(PJ_UPLOAD_PATH . 'source/', PJ_UPLOAD_PATH . $key . '/', $item['source_path']);
			}
			$Image->loadImage($item['source_path']);
			if ($this->imageCrop)
			{
				$Image->resizeSmart($d[0], $d[1]);
			} else {
				$Image->resizeToWidth($d[0]);
			}
			$Image->saveImage($dst);
			$Image->loadImage($dst);
			if (!empty($watermark) && $key != 'small')
			{
				$Image->setWatermark($watermark, $watermarkPosition);
			}
			$Image->saveImage($dst);
			$data[$key . '_path'] = $dst;
			$data[$key . '_size'] = filesize($dst);
			$size = getimagesize($dst);
			$data[$key . '_width'] = $size[0];
			$data[$key . '_height'] = $size[1];
		}
		# Large image
		$dst = str_replace(PJ_UPLOAD_PATH . 'source/', PJ_UPLOAD_PATH . 'large/', $item['source_path']);
		$Image->loadImage($item['source_path']);
		if (!empty($watermark))
		{
			$Image->setWatermark($watermark, $watermarkPosition);
		}
		$Image->saveImage($dst);
		$data['large_path'] = $dst;
		$data['large_size'] = filesize($dst);
		$size = getimagesize($dst);
		$data['large_width'] = $size[0];
		$data['large_height'] = $size[1];
		return $data;
	}
	
	public function pjActionCompressGallery()
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
			
			$isForeignId = isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0;
			$isHash = isset($_GET['hash']) && !empty($_GET['hash']);
			
			if (!(isset($_GET['model']) && ($isForeignId ^ $isHash)))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$pjGalleryModel = pjGalleryModel::factory()->where('model', $_GET['model']);
			
			if ($isForeignId)
			{
				$pjGalleryModel->where('foreign_id', $_GET['foreign_id']);
			} elseif ($isHash) {
				$pjGalleryModel->where('hash', $_GET['hash']);
			}
			
			$arr = $pjGalleryModel->findAll()->getData();
			if (empty($arr))
			{
				$text = 'No image(s) has been found.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
			}
			$_POST['large_path_compression'] = $_POST['small_path_compression'];
			$_POST['medium_path_compression'] = $_POST['small_path_compression'];

			$image = new pjImage();
			if ($image->getErrorCode() !== 200)
			{
				foreach ($arr as $item)
				{
					$data = array();
					foreach ($this->imageFiles as $file)
					{
						if (!empty($item[$file]))
						{
							$compression = isset($_POST[$file.'_compression']) ? (int) $_POST[$file.'_compression'] : 60;
							$image->loadImage($item[$file])->saveImage($item[$file], NULL, $compression);
							@clearstatcache();
							$data[str_replace('_path', '_size', $file)] = filesize($item[$file]);
						}
					}
					if (!empty($data))
					{
						$pjGalleryModel->reset()->set('id', $item['id'])->modify($data);
					}
				}
			}
			$text = 'Image(s) has been compressed.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}
	
	public function pjActionCropGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$GalleryModel = pjGalleryModel::factory();
				$arr = $GalleryModel->find($_POST['id'])->getData();
				if (count($arr) > 0)
				{
					$Image = new pjImage();
					if ($Image->getErrorCode() !== 200)
					{
						$Image->loadImage($arr[$_POST['src']]);
						if ($_POST['dst'] == 'large_path')
						{
							$Image->crop($_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
						} else {
							$Image->crop(
								$_POST['x'],
								$_POST['y'],
								$this->imageSizes[str_replace('_path', '', $_POST['dst'])][0],
								$this->imageSizes[str_replace('_path', '', $_POST['dst'])][1],
								$_POST['w'],
								$_POST['h']
							);
						}
						$Image->saveImage($arr[$_POST['dst']]);
					} else {
						$this->log('GD is not loaded');
					}
					
					$key = str_replace('_path', '', $_POST['dst']);
					$data = array();
					$data[$key.'_size'] = filesize($arr[$_POST['dst']]);
					$size = @getimagesize($arr[$_POST['dst']]);
					if ($size !== false)
					{
						$data[$key.'_width'] = $size[0];
						$data[$key.'_height'] = $size[1];
					}
					$GalleryModel->reset()->where('id', $arr['id'])->limit(1)->modifyAll($data);
				} else {
					$this->log('Image record not found in DB');
				}
			} else {
				$this->log("\$_POST['id'] is not set or has incorrect value");
			}
		}
		exit;
	}
	
	public function pjActionEmptyGallery()
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
			
			$isForeignId = isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0;
			$isHash = isset($_GET['hash']) && !empty($_GET['hash']);
			
			if (!(isset($_GET['model']) && ($isForeignId ^ $isHash)))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$GalleryModel = pjGalleryModel::factory()->where('model', $_GET['model']);
			
			if ($isForeignId)
			{
				$GalleryModel->where('foreign_id', $_GET['foreign_id']);
			} elseif ($isHash) {
				$GalleryModel->where('hash', $_GET['hash']);
			}
			
			$arr = $GalleryModel->findAll()->getData();
			if (empty($arr))
			{
				$text = 'No image(s) has been found.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
			}
			foreach ($arr as $item)
			{
				$this->pjActionDeleteImage($item);
			}
			$GalleryModel->eraseAll();
			
			$text = 'Gallery has been emptied.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}
		
	public function pjActionDeleteGallery()
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
			
			if (!(isset($_POST['id']) && (int) $_POST['id'] > 0))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$pjGalleryModel = pjGalleryModel::factory();
			$arr = $pjGalleryModel->find($_POST['id'])->getData();
			if (empty($arr))
			{
				$text = 'Image not found.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
			}
			$this->pjActionDeleteImage($arr);
			$pjGalleryModel->erase();
			
			$text = 'Image has been deleted.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}
	
	public function pjActionGetGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjGalleryModel = pjGalleryModel::factory();
			
			if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
			{
				$pjGalleryModel->where('t1.foreign_id', $_GET['foreign_id']);
			} elseif (isset($_GET['hash']) && !empty($_GET['hash'])) {
				$pjGalleryModel->where('t1.hash', $_GET['hash']);
			} else {
				$pjGalleryModel->where('t1.id < 0');
			}
			
			if (isset($_GET['model']) && !empty($_GET['model']))
			{
				$pjGalleryModel->where('t1.model', $_GET['model']);
			}
			
			$column = 'sort';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}
			$error = NULL;
			if (isset($_GET['error']))
			{
				$error = $_GET['error'];
			}

			$total = $pjGalleryModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 100;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjGalleryModel->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
			$originals_size = $thumbs_size = 0;
			foreach ($data as $item)
			{
				$originals_size += (int) $item['source_size'];
				$thumbs_size += (int) $item['small_size'];
				$thumbs_size += (int) $item['medium_size'];
				$thumbs_size += (int) $item['large_size'];
			}
			pjAppController::jsonResponse(compact('data', 'originals_size', 'thumbs_size', 'total', 'pages', 'page', 'rowCount', 'column', 'direction', 'error'));
		}
		exit;
	}

	public function pjActionIndex()
	{
		$this->checkLogin();
	}

	private function pjActionRebuild($model, $foreign_id=NULL, $hash=NULL)
	{
		if ((isset($foreign_id) && (int) $foreign_id > 0) ^ (isset($hash) && !empty($hash)))
		{
			$Image = new pjImage();
			if ($Image->getErrorCode() !== 200)
			{
				$GalleryModel = pjGalleryModel::factory()->where('model', $model);

				if (isset($foreign_id) && (int) $foreign_id > 0)
				{
					$GalleryModel->where('foreign_id', $foreign_id);
				} elseif (isset($hash) && !empty($hash)) {
					$GalleryModel->where('hash', $hash);
				}
				
				$arr = $GalleryModel->findAll()->getData();
				foreach ($arr as $item)
				{
					$data = array();
					$data = $this->pjActionBuildFromSource($Image, $item);
					$GalleryModel->reset()->set('id', $item['id'])->modify($data);
				}
				
				$text = 'Image(s) has been re-build.';
				$this->log($text);
				return array('status' => 'OK', 'code' => 200, 'text' => $text);
			} else {
				$text = 'GD extension is not loaded';
				$this->log($text);
				return array('status' => 'ERR', 'code' => 101, 'text' => $text);
			}
		} else {
			$text = "\$_GET['foreign_id'] is not set or has incorrect value";
			$this->log($text);
			return array('status' => 'ERR', 'code' => 100, 'text' => $text);
		}
	}
	
	public function pjActionRebuildUrl()
	{
		$this->checkLogin();
		
		if ($this->isLoged())
		{
			if (!(isset($_GET['model']) && !empty($_GET['model'])))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$this->pjActionRebuild($_GET['model'], @$_GET['foreign_id'], @$_GET['hash']);
		}
		exit;
	}
	
	public function pjActionRebuildGallery()
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
			
			if (!(isset($_GET['model']) && !empty($_GET['model'])))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$result = $this->pjActionRebuild($_GET['model'], @$_GET['foreign_id'], @$_GET['hash']);
			pjAppController::jsonResponse($result);
		}
		exit;
	}
	
	public function pjActionCrop()
	{
		$this->checkLogin();
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST'
			&& isset($_POST['do_crop'], $_POST['id'], $_POST['x'], $_POST['y'], $_POST['width'], $_POST['height'], $_POST['rotate'])
			&& !empty($_POST['id']))
		{
			$arr = pjGalleryModel::factory()->find($_POST['id'])->getData();
			$gallery_set_arr = pjGallerySetModel::factory()->find($arr['foreign_id'])->getData();
			
			$data = array(
					'x' => $_POST['x'],
					'y' => $_POST['y'],
					'width' => $_POST['width'],
					'height' => $_POST['height'],
					'rotate' => $_POST['rotate'],
			);
			
			$rec_width = $arr['source_width'];
			$rec_height = $arr['source_height'];
			switch ($_POST['size']) {
				case 'small':
					$rec_width = $gallery_set_arr['small_width'];
					$rec_height = $gallery_set_arr['small_height'];
				break;
				
				case 'medium':
					$rec_width = $gallery_set_arr['medium_width'];
					$rec_height = $gallery_set_arr['medium_height'];
				break;
			}
			if(isset($_POST['create_thumb']) || isset($_POST['create_preview']))
			{
				$crop = new pjGalleryCrop($arr['source_path'], $arr[$_POST['size'] . '_path'], $data);
			}else{
				$crop = new pjGalleryCrop($arr[$_POST['size'] . '_path'], $arr[$_POST['size'] . '_path'], $data);
			}
			$crop->crop($rec_width, $rec_height, 80);
			
			pjUtil::redirect(sprintf("%sindex.php?controller=pjGallery&action=pjActionCrop&id=" . $_POST['id'] . "&size=" . $_POST['size'], PJ_INSTALL_URL));
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && (int) $_GET['id'] > 0)
		{
			$arr = pjGalleryModel::factory()->find($_GET['id'])->getData();
			if (count($arr) === 0)
			{
				pjUtil::redirect(sprintf("%sindex.php?controller=pjGallery&action=pjActionIndex&err=AG01", PJ_INSTALL_URL));
			}
			
			$this->set('arr', $arr);
			$this->set('gallery_set_arr', pjGallerySetModel::factory()->find($arr['foreign_id'])->getData());
			
			$this->appendCss('cropper.min.css', PJ_THIRD_PARTY_PATH . 'st_cropper/');
			$this->appendJs('cropper.min.js', PJ_THIRD_PARTY_PATH . 'st_cropper/');
			$this->appendCss('pj-gallery.css', $this->getConst('PLUGIN_CSS_PATH'));
			$this->appendJs('pjGallery.js', $this->getConst('PLUGIN_JS_PATH'));
		}
	}
	
	public function pjActionRotateGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$pjGalleryModel = pjGalleryModel::factory();
				$arr = $pjGalleryModel->find($_POST['id'])->getData();
				if (count($arr) > 0)
				{
					$Image = new pjImage();
					if ($Image->getErrorCode() !== 200)
					{
						$data = array();
						if (!empty($arr['small_path']))
						{
							$Image->loadImage($arr['small_path'])->rotate()->saveImage($arr['small_path']);
							$data['small_size'] = filesize($arr['small_path']);
							$size = getimagesize($arr['small_path']);
							$data['small_width'] = $size[0];
							$data['small_height'] = $size[1];
						}
						if (!empty($arr['medium_path']))
						{
							$Image->loadImage($arr['medium_path'])->rotate()->saveImage($arr['medium_path']);
							$data['medium_size'] = filesize($arr['medium_path']);
							$size = getimagesize($arr['medium_path']);
							$data['medium_width'] = $size[0];
							$data['medium_height'] = $size[1];
						}
						if (!empty($arr['large_path']))
						{
							$Image->loadImage($arr['large_path'])->rotate()->saveImage($arr['large_path']);
							$data['large_size'] = filesize($arr['large_path']);
							$size = getimagesize($arr['large_path']);
							$data['large_width'] = $size[0];
							$data['large_height'] = $size[1];
						}
						if (!empty($data))
						{
							$pjGalleryModel->modify($data);
						}
					} else {
						$this->log('GD extesion is not loaded');
					}
				} else {
					$this->log("Image record not found in DB");
				}
			} else {
				$this->log("\$_POST['id'] is not set or has incorrect value");
			}
		}
		exit;
	}
	
	public function pjActionSortGallery()
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
			
			if (!(isset($_POST['sort']) && is_array($_POST['sort'])))
			{
				$text = "\$_POST['sort'] is not set or incorrect value";
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			$pjGalleryModel = new pjGalleryModel();
			$arr = $pjGalleryModel->whereIn('id', $_POST['sort'])->orderBy("t1.sort ASC")->findAll()->getDataPair('id', 'sort');
			$fliped = array_flip($_POST['sort']);
			$combined = array_combine(array_keys($fliped), $arr);
			if (empty($combined))
			{
				$text = "No image(s) found.";
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
			}
			$pjGalleryModel->begin();
			foreach ($combined as $id => $sort)
			{
				$pjGalleryModel->setAttributes(compact('id'))->modify(compact('sort'));
			}
			$pjGalleryModel->commit();
			
			$text = "Image(s) has been sorted.";
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}

	public function pjActionUpdateGallery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$GalleryModel = pjGalleryModel::factory();
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$arr = $GalleryModel->find($_POST['id'])->getData();
				if (count($arr) > 0)
				{
					$data = array();
					$Image = new pjImage();
					if ($Image->getErrorCode() !== 200)
					{
						$Image->setFontSize(18)->setFont(PJ_WEB_PATH . 'obj/arialbd.ttf');
						
						$_POST['large_path_compression'] = $_POST['small_path_compression'];
						$_POST['medium_path_compression'] = $_POST['small_path_compression'];
								
						foreach ($this->imageFiles as $file)
						{
							@clearstatcache();
							if (!empty($arr[$file]) && is_file($arr[$file]))
							{
								if ((isset($_POST['watermark']) && !empty($_POST['watermark']) && $arr['watermark'] != $_POST['watermark']) || (isset($_POST['position']) && !empty($_POST['position']) && $arr['position'] != $_POST['position']))
								{
									if ($file != 'source_path')
									{
										if (!empty($arr['watermark']))
										{
											// Init image, then set watermark
											if (!empty($arr[$file]))
											{
												$dst = $arr[$file];
											} else {
												$dst = str_replace(PJ_UPLOAD_PATH . 'source/', PJ_UPLOAD_PATH . str_replace('_path', '', $file) . '/', $arr['source_path']);
											}
											$Image->loadImage($arr['source_path']);
											if ($file != 'large_path')
											{
												if ($this->imageCrop)
												{
													$Image->resizeSmart($this->imageSizes[str_replace('_path', '', $file)][0], $this->imageSizes[str_replace('_path', '', $file)][1]);
												} else {
													$Image->resizeToWidth($this->imageSizes[str_replace('_path', '', $file)][0]);
												}
												$Image->saveImage($dst);
												$Image->loadImage($dst);
											}
											if ($file != 'small_path')
											{
												$Image->setWatermark($_POST['watermark'], $_POST['position']);
											}
											$Image->saveImage($dst);
										} else {
											if ($file != 'small_path')
											{
												$Image
													->loadImage($arr[$file])
													->setWatermark($_POST['watermark'], $_POST['position'])
													->saveImage($arr[$file]);
											}
										}
									}
								}
								# Compression ----------------
								if (!empty($arr[$file]))
								{
									$compression = isset($_POST[$file.'_compression']) ? (int) $_POST[$file.'_compression'] : 60;
									$Image->loadImage($arr[$file])->saveImage($arr[$file], NULL, $compression);
									@clearstatcache();
									$data[str_replace('_path', '_size', $file)] = filesize($arr[$file]);
								}
								# Compression ----------------
							}
						}
					
						if (empty($_POST['watermark']) && !empty($arr['watermark']))
						{
							// Clear watermark
							foreach ($this->imageSizes as $key => $d)
							{
								if (!empty($arr[$key . '_path']))
								{
									$dst = $arr[$key . '_path'];
								} else {
									$dst = str_replace(PJ_UPLOAD_PATH . 'source/', PJ_UPLOAD_PATH . $key . '/', $arr['source_path']);
								}
								$Image->loadImage($arr['source_path']);
								if ($this->imageCrop)
								{
									$Image->resizeSmart($d[0], $d[1]);
								} else {
									$Image->resizeToWidth($d[0]);
								}
								$Image->saveImage($dst);
								$data[$key . '_path'] = $dst;
							}
							# Large image
							$dst = str_replace(PJ_UPLOAD_PATH . 'source/', PJ_UPLOAD_PATH . 'large/', $arr['source_path']);
							$Image->loadImage($arr['source_path'])->saveImage($dst);
							$data['large_path'] = $dst;
						}
					} else {
						$this->log('GD extension is not loaded');
					}
					
					//alt & watermark
					$GalleryModel->modify(array_merge($_POST, $data));
					
					$text = 'Image has been updated.';
					$this->log($text);
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
				} else {
					$text = 'Image not found.';
					$this->log($text);
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
				}
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$arr = $GalleryModel->find($_GET['id'])->getData();
				
				pjAppController::jsonResponse($arr);
			}
			
			$text = 'Missing, empty or invalid parameters.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => $text));
		}
		exit;
	}

	public function pjActionUploadGallery()
	{
		$this->checkLogin();
		$this->setAjax(true);

		ini_set('post_max_size', '50M');
		ini_set('upload_max_filesize', '50M');
		
		$resp = array();
		
		$post_max_size = ini_get('post_max_size');
		switch (strtoupper(substr($post_max_size, -1)))
		{
			case 'G':
				$post_max_size = (int) $post_max_size * 1024 * 1024 * 1024;
				break;
			case 'M':
				$post_max_size = (int) $post_max_size * 1024 * 1024;
				break;
			case 'K':
				$post_max_size = (int) $post_max_size * 1024;
				break;
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) 
			&& ((int) $_SERVER['CONTENT_LENGTH'] > $post_max_size) || ($_SERVER['CONTENT_LENGTH'] > 0 && empty($_POST) && empty($_FILES)))
		{
			$error = 'Posted data is too large. '. $_SERVER['CONTENT_LENGTH'].' bytes exceeds the maximum size of '. $post_max_size.' bytes.';
			$this->log("The \$_SERVER['CONTENT_LENGTH'] exceeds the post_max_size directive in php.ini.");
			$this->set('error', $error);
		} else {
			if (isset($_FILES['image']))
			{
				// Multiple file upload
				$files = array();
				foreach ($_FILES['image']['tmp_name'] as $k => $v)
				{
					$files[] = array(
						'name' => $_FILES['image']['name'][$k],
						'type' => $_FILES['image']['type'][$k],
						'tmp_name' => $_FILES['image']['tmp_name'][$k],
						'error' => $_FILES['image']['error'][$k],
						'size' => $_FILES['image']['size'][$k]
					);
				}
				
				// Single file upload
				# $files = array($_FILES['file']);
				
				$Image = new pjImage();
				if ($Image->getErrorCode() === 200)
				{
					$this->log('GD extension is not loaded');
				}
				$Image->setAllowedTypes(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg'));
				
				$GalleryModel = pjGalleryModel::factory();
				
				foreach ($files as $file)
				{
					if (!$Image->load($file))
					{
						$this->set('error', $Image->getError());
						$this->log($this->get('error'));
						break;
					}
					if ($Image->getImageSize() === FALSE)
					{
						$this->set('error', 'Image is corrupted or invalid.');
						$this->log($this->get('error'));
						break;
					}
					$resp = $Image->isConvertPossible();
					if ($resp['status'] === true)
					{
						$hash = md5(uniqid(rand(), true));
						$source_path = PJ_UPLOAD_PATH . 'source/' . @$_GET['foreign_id'] . '_' . $hash . '.' . $Image->getExtension();
						if ($Image->save($source_path))
						{
							$GalleryModel->reset();
								
							$data = array();
							if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
							{
								$GalleryModel->where('t1.foreign_id', $_GET['foreign_id']);
								$data['foreign_id'] = $_GET['foreign_id'];
							} elseif (isset($_GET['hash']) && !empty($_GET['hash'])) {
								$GalleryModel->where('t1.hash', $_GET['hash']);
								$data['hash'] = $_GET['hash'];
							}
							if (isset($_GET['model']) && !empty($_GET['model']))
							{
								$data['model'] = $_GET['model'];
							}
							
							$arr = $GalleryModel->orderBy('t1.sort DESC')->limit(1)->findAll()->getData();
							$sort = 1;
							if (count($arr) === 1)
							{
								$sort = (int) $arr[0]['sort'] + 1;
							}
								
							$data['mime_type'] = $file['type'];
							$data['source_path'] = $source_path;
							$data['source_size'] = $file['size'];
							$data['name'] = $file['name'];
							$data['sort'] = $sort;
							$data['created'] = date('Y-m-d H:i:s');
							
							$data = array_merge($data, $this->pjActionBuildFromSource($Image, $data));
		
							$size = $Image->getImageSize();
							$data['source_width'] = $size[0];
							$data['source_height'] = $size[1];
							
							$GalleryModel->reset()->setAttributes($data)->insert();
						} else {
							$this->log('Image has not been saved');
						}
					} else {
						// Not enough memory
						// $resp['memory_needed']
						// $resp['memory_limit']
						$this->set('error', sprintf('Allowed memory size of %u bytes exhausted (tried to allocate %u bytes)', $resp['memory_limit'], $resp['memory_needed']));
						$this->log($this->get('error'));
					}
				}
			} else {
				$this->log("\$_FILES['image'] is not set");
				$this->set('error', 'Image is not set');
			}
		}
		if ($this->get('error') !== FALSE)
		{
			$resp['error'] = $this->get('error');
		}
		header("Content-Type: text/html; charset=utf-8"); //fix for IE
		echo pjAppController::jsonEncode($resp);
		exit;
	}

	public function pjActionWatermarkGallery()
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
				
			$isForeignId = isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0;
			$isHash = isset($_GET['hash']) && !empty($_GET['hash']);
				
			if (!(isset($_GET['model']) && ($isForeignId ^ $isHash)))
			{
				$text = 'Missing, empty or invalid parameters.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => $text));
			}
			
			$pjGalleryModel = pjGalleryModel::factory()->where('model', $_GET['model']);
			
			if ($isForeignId)
			{
				$pjGalleryModel->where('foreign_id', $_GET['foreign_id']);
			} elseif ($isHash) {
				$pjGalleryModel->where('hash', $_GET['hash']);
			}
			
			$arr = $pjGalleryModel->findAll()->getData();
			if (empty($arr))
			{
				$text = 'No image(s) has been found.';
				$this->log($text);
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
			}
			$image = new pjImage();
			if ($image->getErrorCode() !== 200)
			{
				$image->setFontSize(18)->setFont(PJ_WEB_PATH . 'obj/arialbd.ttf');
				foreach ($arr as $item)
				{
					if (isset($_POST['watermark']))
					{
						$this->pjActionBuildFromSource($image, $item, $_POST['watermark'], $_POST['position']);
					} else {
						$this->pjActionBuildFromSource($image, $item);
					}
				}
			} else {
				$this->log('GD extension is not loaded');
			}
			if (isset($_POST['watermark']))
			{
				$data = array('watermark' => $_POST['watermark']);
			} else {
				$data = array('watermark' => array('NULL'));
			}
			$pjGalleryModel->modifyAll($data);
			
			$text = 'Watermark has been saved.';
			$this->log($text);
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $text));
		}
		exit;
	}
	
	public function pjActionList()
	{
		$this->checkLogin();
	
		if ($this->isCountryReady())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjCountry.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionRemoteResize()
	{
		set_time_limit(300);
		
		$params = $this->getParams();
		
		if (!(isset($params['small_width'], $params['small_height'], $params['medium_width'], $params['medium_height'])))
		{
			return array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.');
		}
		
		$pjGallerySetModel = pjGallerySetModel::factory();
		$pjGalleryModel = pjGalleryModel::factory();
	
		$this->imageSizes['small'] = array($params['small_width'], $params['small_height']);
		$this->imageSizes['medium'] = array($params['medium_width'], $params['medium_height']);
		
		$pjGallerySetModel->modifyAll(array(
			'small_width' => $this->imageSizes['small'][0],
			'small_height' => $this->imageSizes['small'][1],
			'medium_width' => $this->imageSizes['medium'][0],
			'medium_height' => $this->imageSizes['medium'][1],
			'modified' => ':NOW()',
		));
	
		$image_arr = $pjGalleryModel
			->join('pjGallerySet', 't2.id=t1.foreign_id', 'inner')
			->where('t1.model', 'pjGallerySet')
			->findAll()
			->getData();
		
		if (empty($image_arr))
		{
			return array('status' => 'ERR', 'code' => 101, 'text' => 'Image(s) not found.');
		}
	
		$pjImage = new pjImage();
		$pjImage->setFontSize(18)->setFont(PJ_WEB_PATH . 'obj/arialbd.ttf');
			
		foreach ($image_arr as $item)
		{
			$data = array();
			$data = $this->pjActionBuildFromSource($pjImage, $item, $item['watermark']);
			$pjGalleryModel->reset()->set('id', $item['id'])->modify($data);
		}
		
		return array('status' => 'OK', 'code' => 200, 'text' => 'Image(s) has been resized.');
	}
}
?>