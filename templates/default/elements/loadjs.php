<script type="text/javascript">
var pjQ = pjQ || {},
	AutoClassifieds_<?php echo $index; ?>;
(function () {
	"use strict";
	var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),

	loadCssHack = function(url, callback){
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = url;

		document.getElementsByTagName('head')[0].appendChild(link);

		var img = document.createElement('img');
		img.onerror = function(){
			if (callback && typeof callback === "function") {
				callback();
			}
		};
		img.src = url;
	},
	loadRemote = function(url, type, callback) {
		if (type === "css" && isSafari) {
			loadCssHack(url, callback);
			return;
		}
		var _element, _type, _attr, scr, s, element;
		
		switch (type) {
		case 'css':
			_element = "link";
			_type = "text/css";
			_attr = "href";
			break;
		case 'js':
			_element = "script";
			_type = "text/javascript";
			_attr = "src";
			break;
		}
		
		scr = document.getElementsByTagName(_element);
		s = scr[scr.length - 1];
		element = document.createElement(_element);
		element.type = _type;
		if (type == "css") {
			element.rel = "stylesheet";
		}
		if (element.readyState) {
			element.onreadystatechange = function () {
				if (element.readyState == "loaded" || element.readyState == "complete") {
					element.onreadystatechange = null;
					if (callback && typeof callback === "function") {
						callback();
					}
				}
			};
		} else {
			element.onload = function () {
				if (callback && typeof callback === "function") {
					callback();
				}
			};
		}
		element[_attr] = url;
		s.parentNode.insertBefore(element, s.nextSibling);
	},
	loadScript = function (url, callback) {
		loadRemote(url, "js", callback);
	},
	loadCss = function (url, callback) {
		loadRemote(url, "css", callback);
	},
	options = {
		server: "<?php echo PJ_INSTALL_URL; ?>",
		folder: "<?php echo PJ_INSTALL_FOLDER; ?>",
		index: "<?php echo $index; ?>",

		min_year: 1990,
		max_year: <?php echo date("Y"); ?>,
		year_from: <?php echo isset($_GET['year_from']) && (int) $_GET['year_from'] > 0 ? $_GET['year_from'] : 1900; ?>,
		year_to: <?php echo isset($_GET['year_to']) && (int) $_GET['year_to'] > 0 ? $_GET['year_to'] : date("Y"); ?>,
				
		min_mileage: 0,
		max_mileage: 500000,
		mileage_from: <?php echo isset($_GET['mileage_from']) && (int) $_GET['mileage_from'] > 0 ? $_GET['mileage_from'] : 0; ?>,
		mileage_to: <?php echo isset($_GET['mileage_to']) && (int) $_GET['mileage_to'] > 0 ? $_GET['mileage_to'] : 500000; ?>,
		
		min_price: 0,
		max_price: 500000,
		price_from: <?php echo isset($_GET['price_from']) && (int) $_GET['price_from'] > 0 ? $_GET['price_from'] : 0; ?>,
		price_to: <?php echo isset($_GET['price_to']) && (int) $_GET['price_to'] > 0  ? $_GET['price_to'] : 500000; ?>,

		min_power: 0,
		max_power: 1000,
		power_from: <?php echo isset($_GET['power_from']) && (int) $_GET['power_from'] > 0 ? $_GET['power_from'] : 0; ?>,
		power_to: <?php echo isset($_GET['power_to']) && (int) $_GET['power_to'] > 0 ? $_GET['power_to'] : 1000; ?>
	};
	<?php
	$dm = new pjDependencyManager(PJ_INSTALL_PATH . PJ_THIRD_PARTY_PATH);
	$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
	?>
	loadScript("<?php echo PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_jquery')); ?>/pjQuery.min.js", function () {
		loadScript("<?php echo PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_jquery_slider')); ?>/pjQuery-ui.js", function () {
			loadScript("<?php echo PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_bootstrap')); ?>pjQuery.bootstrap.min.js", function () {
				loadScript("<?php echo PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_validate')); ?>pjQuery.validate.min.js", function () {
					loadScript("<?php echo PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_validate')); ?>pjQuery.additional-methods.min.js", function () {
						loadScript("<?php echo PJ_INSTALL_URL . preg_replace('|^' . PJ_INSTALL_PATH . '|', '', $dm->getPath('pj_fancybox')); ?>pjQuery.fancybox.js", function () {
							loadScript("<?php echo PJ_INSTALL_URL . PJ_TEMPLATE_PATH . basename(dirname(dirname(__FILE__))); ?>/js/pjListings.js", function () {
								AutoClassifieds_<?php echo $index; ?> = new AutoClassifieds(options);
							});
						});	
					});
				});
			});
		});
	});
})();
</script>