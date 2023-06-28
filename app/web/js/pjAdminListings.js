var jQuery_1_8_2 = $.noConflict();

(function ($, undefined) {

	$(function () {

		var datagrid = ($.fn.datagrid !== undefined),

			datepicker = ($.fn.datepicker !== undefined),

			gallery = ($.fn.gallery !== undefined),

			chosen = ($.fn.chosen !== undefined),

			dialog = ($.fn.dialog !== undefined),

			validate = ($.fn.validate !== undefined),

			tipsy = ($.fn.tipsy !== undefined),

			spinner = ($.fn.spinner !== undefined),

			tabs = ($.fn.tabs !== undefined),

			$frmCreateListing = $("#frmCreateListing"),

			$frmUpdateListing = $("#frmUpdateListing"),

			$frmExportCars = $("#frmExportCars"),

			$dialogDeletePrice = $("#dialogDeletePrice"),

			$dialogLimit = $("#dialogLimit"),

			$gallery = $("#gallery"),

			$tabs = $("#tabs"),

			tOpt = {

				activate: function (event, ui) {

					$(":input[name='tab_id']").val($(ui.newPanel).prop('id'));

				}

			};

		

		if ($tabs.length > 0 && tabs) {

			$tabs.tabs(tOpt);

		}

		if (tipsy) {

			$(".listing-tip").tipsy({

				offset: 1,

				opacity: 1,

				html: true,

				gravity: "nw",

				className: "tipsy-listing"

			});

		}

		$(".field-int").spinner({

			min: 0

		});

		

		if ($gallery.length > 0 && gallery) {

			$gallery.gallery({

				compressUrl: "index.php?controller=pjGallery&action=pjActionCompressGallery&model=pjListing&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,

				getUrl: "index.php?controller=pjGallery&action=pjActionGetGallery&model=pjListing&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,

				deleteUrl: "index.php?controller=pjGallery&action=pjActionDeleteGallery",

				emptyUrl: "index.php?controller=pjGallery&action=pjActionEmptyGallery&model=pjListing&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,

				rebuildUrl: "index.php?controller=pjGallery&action=pjActionRebuildGallery&model=pjListing&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,

				resizeUrl: "index.php?controller=pjGallery&action=pjActionCrop&model=pjListing&id={:id}&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash + ($frmUpdateListing.length > 0 ? "&query_string=" + encodeURIComponent("controller=pjAdminListings&action=pjActionUpdate&id=" + myGallery.foreign_id + "&tab_id=tabs-5") : ""),

				rotateUrl: "index.php?controller=pjGallery&action=pjActionRotateGallery",

				sortUrl: "index.php?controller=pjGallery&action=pjActionSortGallery",

				updateUrl: "index.php?controller=pjGallery&action=pjActionUpdateGallery",

				uploadUrl: "index.php?controller=pjGallery&action=pjActionUploadGallery&model=pjListing&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash,

				watermarkUrl: "index.php?controller=pjGallery&action=pjActionWatermarkGallery&model=pjListing&foreign_id=" + myGallery.foreign_id + "&hash=" + myGallery.hash

			});

		}

		if (spinner) {

			$(".spin").spinner({

				min: 0,

				stop: function (event, ui) {

					var $this = $(this),

						$chained = $this.closest("p").find(".spin").not(this),

						name = $this.attr("name");

					if (name.match(/_from$/) !== null) {

						$chained.spinner("option", "min", $this.val());

					} else if (name.match(/_to$/) !== null) {

						$chained.spinner("option", "max", $this.val());

					}

				}

			});

		}



		if (chosen) {

			$("#owner_id").chosen();

			$("#make_id").chosen();

			$("#model_id").chosen();

		}

		

		if ($frmCreateListing.length > 0 && validate) {

			$frmCreateListing.validate({

				rules: {

					"listing_refid": {

						required: true,

						remote: "index.php?controller=pjAdminListings&action=pjActionCheckRefId"

					}

				},

				errorPlacement: function (error, element) {

					error.insertAfter(element.parent());

				},

				errorClass: "err",

				wrapper: "em",

				onkeyup: false,

				ignore: ".ignore"

			});

			tinymce.init({

				relative_urls : false,

				remove_script_host : false,

			    selector: "textarea.mceEditor",

			    theme: "modern",

			    browser_spellcheck : true,

			    contextmenu: false,

			    width: 570,

			    height: 350,

		    	plugins: [

			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",

			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",

			         "save table contextmenu directionality emoticons template paste textcolor"

		        ],

		        toolbar: "insertfile undo redo | styleselect fontselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"

			});


		}

		

		if ($dialogDeletePrice.length > 0 && dialog) {

			$dialogDeletePrice.dialog({

				modal: true,

				autoOpen: false,

				resizable: false,

				draggable: false,

				buttons: {

					"Delete": function () {

						var $this = $(this),

							$link = $this.data("link"),

							$tr = $link.closest("tr");

						$.post("index.php?controller=pjAdminListings&action=pjActionDeletePrice", {

							id: $link.data("id")

						}).done(function () {

							$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {

								$tr.remove();

								$this.dialog("close");

							});

						});

					},

					"Cancel": function () {

						$(this).dialog("close");

					}

				}

			});

		}

		if ($frmExportCars.length > 0 && validate) {

			$frmExportCars.validate({

				rules: {

					"password": {

						required: function(){

							if($('#feed').is(':checked'))

							{

								return true;

							}else{

								return false;

							}

						}

					}

				},

				errorPlacement: function (error, element) {

					error.insertAfter(element.parent());

				},

				onkeyup: false,

				errorClass: "err",

				wrapper: "em",

				ignore: ".ignore"

			});

		}

		if ($("#grid").length > 0 && datagrid) {

			function formatImage(val, obj) {

				var src = val ? val : 'app/web/img/backend/no_img.png';

				return ['<a href="index.php?controller=pjAdminListings&action=pjActionUpdate&id=', obj.id ,'"><img src="', src, '" style="width: 100px" /></a>'].join("");

			}

			

			function formatOwner(val, obj) {

				return ['<a href="index.php?controller=pjAdminUsers&action=pjActionUpdate&id=', obj.owner_id, '">', $.datagrid.wordwrap(obj.owner_name, 20, '<br>', true), '</a>'].join("");

			}

			

			function formatRefid(val, obj) {

				return $.datagrid.wordwrap(val, 25, '<br>', true);

			}

			

			var gridOpts = {

				buttons: [{type: "edit", url: "index.php?controller=pjAdminListings&action=pjActionUpdate&id={:id}"},

				          {type: "delete", url: "index.php?controller=pjAdminListings&action=pjActionDeleteListing&id={:id}"},

				        //   {type: "menu", url: "#", text: myLabel.more, items:[

				        //       {text: myLabel.exp_date_plus_30, url: "index.php?controller=pjAdminListings&action=pjActionExpireListing&id={:id}", ajax: true, render: true}

				        //    ]}
						],

				columns: [{text: myLabel.image, type: "text", sortable: false, editable: false, renderer: formatImage, width: 130},

				          {text: myLabel.details, type: "text", sortable: true, editable: false, width: 170},

				          {text: myLabel.owner, type: "text", sortable: true, editable: false, renderer: formatOwner, width: 170},

				        //   {text: myLabel.expire, type: "text", sortable: true, editable: false, width: 80},

				        //   {text: myLabel.publish, type: "select", sortable: true, editable: true, editableWidth: 95, width: 80, options: [

					    //                                                                                  {label: myLabel.active, value: "T"}, 

					    //                                                                                  {label: myLabel.inactive, value: "F"},

					    //                                                                                  {label: myLabel.exp_date, value: "E"}

					    //                                                                                  ], applyClass: "pj-status"}

				          ],

				dataUrl: "index.php?controller=pjAdminListings&action=pjActionGetListing" + pjGrid.queryString,

				dataType: "json",

				fields: ['image', 'details', 'owner_name'], // 'expire', 'status'

				paginator: {

					actions: [

						{text: myLabel.delete_selected, url: "index.php?controller=pjAdminListings&action=pjActionDeleteListingBulk", render: true, confirmation: myLabel.delete_confirm},

						{text: myLabel.exp_date_plus_30, url: "index.php?controller=pjAdminListings&action=pjActionExpireListing", render: true, confirmation: myLabel.extend_confirm},

						{text: myLabel.published, url: "index.php?controller=pjAdminListings&action=pjActionStatusListing&status=T", render: true},

						{text: myLabel.not_published, url: "index.php?controller=pjAdminListings&action=pjActionStatusListing&status=F", render: true}

					],

					gotoPage: true,

					paginate: true,

					total: true,

					rowCount: true

				},

				saveUrl: "index.php?controller=pjAdminListings&action=pjActionSaveListing&id={:id}",

				select: {

					field: "id",

					name: "record[]"

				}

			};

			if (pjGrid.isOwner === true) {

				function formatExtend(val, obj) {

					return ['<a class="pj-button" href="index.php?controller=pjAdminListings&action=pjActionPayment&id=', val, '">', myLabel.extend_exp_date, '</a>'].join("");

				}

				

				gridOpts.buttons = [

				    {type: "edit", url: "index.php?controller=pjAdminListings&action=pjActionUpdate&id={:id}"},

				    {type: "delete", url: "index.php?controller=pjAdminListings&action=pjActionDeleteListing&id={:id}"}

				];

				gridOpts.columns = [

				    {text: myLabel.image, type: "text", sortable: false, editable: false, renderer: formatImage, width: 100},

				    {text: myLabel.ref_id, type: "text", sortable: true, editable: true, width: 340},

					// {text: 'VIN', type: "text", sortable: true, editable: false, width: 240},

				    // {text: myLabel.expire, type: "text", sortable: true, editable: false, width: 80},

					// {text: "", type: "text", sortable: false, editable: false, renderer: formatExtend, width: 165}

				];
				// console.log(myLabel, "options");

				// gridOpts.fields = ['image', 'listing_refid', 'expire', 'id'];
				gridOpts.fields = ['image', 'listing_refid'];

				gridOpts.paginator.actions = [{text: myLabel.delete_selected, url: "index.php?controller=pjAdminListings&action=pjActionDeleteListingBulk", render: true, confirmation: myLabel.delete_confirm}];

			}

			

			var $grid = $("#grid").datagrid(gridOpts);

			

			$(document).on("click", ".btn-all", function (e) {

				if (e && e.preventDefault) {

					e.preventDefault();

				}

				$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");

				var content = $grid.datagrid("option", "content"),

					cache = $grid.datagrid("option", "cache");

				$.extend(cache, {

					status: "",

					q: ""

				});

				$grid.datagrid("option", "cache", cache);

				$grid.datagrid("load", "index.php?controller=pjAdminListings&action=pjActionGetListing", "id", "DESC", content.page, content.rowCount);

				return false;

			}).on("click", ".btn-filter", function (e) {

				if (e && e.preventDefault) {

					e.preventDefault();

				}

				

				var $this = $(this),

					content = $grid.datagrid("option", "content"),

					cache = $grid.datagrid("option", "cache"),

					obj = {};

				$this.addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");

				obj.status = "";

				obj.is_featured = "";

				obj[$this.data("column")] = $this.data("value");

				$.extend(cache, obj);

				$grid.datagrid("option", "cache", cache);

				$grid.datagrid("load", "index.php?controller=pjAdminListings&action=pjActionGetListing", "id", "DESC", content.page, content.rowCount);

				return false;

			}).on("click", ".pj-button-detailed, .pj-button-detailed-arrow", function (e) {

				e.stopPropagation();

				$(".pj-form-filter-advanced").toggle();

			}).on("submit", ".frm-filter-advanced", function (e) {

				if (e && e.preventDefault) {

					e.preventDefault();

				}

				var obj = {},

					$this = $(this),

					arr = $this.serializeArray(),

					content = $grid.datagrid("option", "content"),

					cache = $grid.datagrid("option", "cache");

				for (var i = 0, iCnt = arr.length; i < iCnt; i++) {

					obj[arr[i].name] = arr[i].value;

				}

				$.extend(cache, obj);

				$grid.datagrid("option", "cache", cache);

				$grid.datagrid("load", "index.php?controller=pjAdminListings&action=pjActionGetListing", "id", "ASC", content.page, content.rowCount);

				return false;

			}).on("reset", ".frm-filter-advanced", function (e) {

				if (e && e.preventDefault) {

					e.preventDefault();

				}

				$(".pj-button-detailed").trigger("click");

				if (chosen) {

					var model_clone = $('#model_clone').html();

					model_clone = model_clone.replace("{model_clone_id}", "model_id");

				

					$("#make_id").val('').trigger("liszt:updated");

					$("#model_container").html(model_clone);

					$('#model_id').chosen();

				}

				$('listing_refid').val('');

				$('#listing_refid').val('');

				$('#year_from').val('');

				$('#year_to').val('');

				$('#power_from').val('');

				$('#power_to').val('');

				$('#mileage_from').val('');

				$('#mileage_to').val('');

				$('#feature_class_id').val('');

				$('#feature_doors_id').val('');

				$('#feature_fuel_id').val('');

				$('#feature_gearbox_id').val('');

				$('#feature_seats_id').val('');

				$('#feature_type_id').val('');

			}).on("submit", ".frm-filter", function (e) {

				if (e && e.preventDefault) {

					e.preventDefault();

				}

				var $this = $(this),

					content = $grid.datagrid("option", "content"),

					cache = $grid.datagrid("option", "cache");

				$.extend(cache, {

					q: $this.find("input[name='q']").val(),

					listing_refid: "",

					make_id: "",

					model_id: "",

					year_from: "",

					year_to: "",

					power_from: "",

					power_to: "",

					mileage_from: "",

					mileage_to: "",

					feature_class_id: "",

					feature_doors_id: "",

					feature_fuel_id: "",

					feature_gearbox_id: "",

					feature_seats_id: "",

					feature_type_id: ""

				});

				$grid.datagrid("option", "cache", cache);

				$grid.datagrid("load", "index.php?controller=pjAdminListings&action=pjActionGetListing", "id", "ASC", content.page, content.rowCount);

				return false;

			});

		}

		

		if ($frmUpdateListing.length > 0 && validate) {

			function getStats(id) 

			{

			    var body = tinymce.get(id).getBody(), text = tinymce.trim(body.innerText || body.textContent);



			    return {

			        chars: text.length,

			        words: text.split(/[\w\u2019\'-]+/).length

			    };

			}

			$frmUpdateListing.validate({

				rules: {

					"listing_refid": {

						required: true,

						remote: "index.php?controller=pjAdminListings&action=pjActionCheckRefId&id=" + $frmUpdateListing.find("input[name='id']").val()

					}

				},

				errorPlacement: function (error, element) {

					if(element.attr('name') == 'listing_mileage' || element.attr('name') == 'listing_power')

					{

						error.insertAfter(element.parent().parent());

					}else{

						error.insertAfter(element.parent());

					}

				},

				errorClass: "err",

				wrapper: "em",

				onkeyup: false,

				ignore: "",

				invalidHandler: function (event, validator) {

				    if (validator.numberOfInvalids()) {

				    	var index = $(validator.errorList[0].element, this).closest("div[id^='tabs-']").index();

				    	if ($tabs.length > 0 && tabs && index !== -1) {

				    		$tabs.tabs(tOpt).tabs("option", "active", index-1);

				    		$("a.pj-form-langbar-item:first").trigger("click");

				    	}

				    };

				    $(".pj-multilang-wrap").each(function( index ) {

						if($(this).attr('data-index') == myLabel.localeId)

						{

							$(this).css('display','block');

						}else{

							$(this).css('display','none');

						}

					});

					$(".pj-form-langbar-item").each(function( index ) {

						if($(this).attr('data-index') == myLabel.localeId)

						{

							$(this).addClass('pj-form-langbar-item-active');

						}else{

							$(this).removeClass('pj-form-langbar-item-active');

						}

					});

				},

				submitHandler: function(form){

					var is_valid = true;

					$('.mceEditor').each(function(index){

						var lang_id = $(this).attr('data-index');

						var mce_id = 'i18n_' + lang_id + '_description';

						if(getStats(mce_id).chars > 65535)

						{

							is_valid = false;

						}

					});

					if(is_valid == false)

					{

						$tabs.tabs(tOpt).tabs("option", "active", 2);

						$dialogLimit.dialog('open');

					}else{

						form.submit();

					}

					return false;

				}

			});

			if ($dialogLimit.length > 0 && dialog) {

				$dialogLimit.dialog({

					modal: true,

					autoOpen: false,

					resizable: false,

					draggable: false,

					buttons: {

						"OK": function () {

							$(this).dialog("close");

						}

					}

				});

			}

			tinymce.init({

				relative_urls : false,

				remove_script_host : false,

			    selector: "textarea.mceEditor",

			    theme: "modern",

			    browser_spellcheck : true,

			    contextmenu: false,

			    width: 570,

			    height: 350,

		    	plugins: [

			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",

			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",

			         "save table contextmenu directionality emoticons template paste textcolor"

		        ],

		        toolbar: "insertfile undo redo | styleselect fontselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"

			});

			

			$("a.fancybox").fancybox();

			

			if (spinner) {

				$("input[name='listing_bedrooms'], input[name='listing_bathrooms'], input[name='listing_adults'], input[name='listing_children']").spinner({

					min: 0

				});

				$(".field-int").spinner({

					min: 0,

					stop: function (event, ui) {

						var $this = $(this),

							name = $this.attr("name");

						if (name == "o_min_booking_lenght") {

							$("input[name='o_max_booking_lenght']").spinner("option", "min", $this.val());

						} else if (name == "o_max_booking_lenght") {

							$("input[name='o_min_booking_lenght']").spinner("option", "max", $this.val());

						}

					}

				});

				$("input[name='o_deposit_payment']").spinner("option", {

					min: 0,

					max: 100,

					step: 0.01,

					numberFormat: "n"

				});

				$("input[name='o_tax_payment']").spinner("option", {

					step: 0.01,

					numberFormat: "n"

				});

				$("input[name='o_min_booking_lenght'], input[name='o_max_booking_lenght']").spinner("option", "min", 1);

			}

			

			if (chosen) {

				$("#owner_id").chosen();

				$("#country_id").chosen();

			}

			

		}

		

		$("#content").on("change", "#make_id", function (e) {

			if (e && e.preventDefault) {

				e.preventDefault();

			}

			var make_id = $(this).val();

			$.ajax({

				type: "GET",

				dataType: 'html',

				url: 'index.php?controller=pjAdminListings&action=pjActionGetModels&id=' + make_id,

				success: function (res) {

					$('#model_container').html(res);

					$("#model_id").chosen();

				}

			});

		}).on("focusin", ".datepick", function (e) {

			var $this = $(this);

			$this.datepicker({

				firstDay: $this.attr("rel"),

				dateFormat: $this.attr("rev")

			});

		}).on("click", ".pj-form-field-icon-date", function (e) {

			var $dp = $(this).parent().siblings("input[type='text']");

			if ($dp.hasClass("hasDatepicker")) {

				$dp.datepicker("show");

			} else {

				$dp.trigger("focusin").datepicker("show");

			}

		}).on("click", ".pj-checkbox", function () {

			var $this = $(this);

			if ($this.find("input[type='checkbox']").is(":checked")) {

				$this.addClass("pj-checkbox-checked");

			} else {

				$this.removeClass("pj-checkbox-checked");

			}

		}).on("change", "#status", function (e) {

			if($(this).val() == 'E')

			{

				$('#expiration_container').css('display', 'block');

			}else{

				$('#expiration_container').css('display', 'none');

			}

		}).on("click", "#file", function (e) {

			$('#tsSubmitButton').val(myLabel.btn_export);

			$('.tsFeedContainer').hide();

			$('.tsPassowrdContainer').hide();

		}).on("click", "#feed", function (e) {

			$('.tsPassowrdContainer').show();

			$('#tsSubmitButton').val(myLabel.btn_get_url);

		}).on("focus", "#listing_feed", function (e) {

			$(this).select();

		}).on("change", "#owner_id", function (e) {

			if($(this).val() != '')

			{

				$('#pjCssEditOwner').css('display', 'block');

				var href = $('#pjCssEditOwner').attr('data-href');

				href = href.replace("{ID}", $(this).val());

				$('#pjCssEditOwner').attr('href', href);

			}else{

				$('#pjCssEditOwner').css('display', 'none');

			}

		});

		

		if ($("#export_grid").length > 0 && datagrid) 

		{

			var $grid = $("#export_grid").datagrid({

				buttons: [{type: "view", url: "index.php?controller=pjAdminListings&action=pjActionExportFeed{:params}", 'target' : "_blank"},

				          {type: "delete", url: "index.php?controller=pjAdminListings&action=pjActionDeletePassword&id={:id}"}

				          ],

				columns: [{text: myLabel.format, type: "text", sortable: false, editable: false, width: 300},

				          {text: myLabel.car_created, type: "text", sortable: false, editable: false, width: 310}

				          ],

				dataUrl: "index.php?controller=pjAdminListings&action=pjActionGetPassword",

				dataType: "json",

				fields: ['format', 'period'],

				paginator: {

					actions: [

					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminListings&action=pjActionDeletePasswordBulk", render: true, confirmation: myLabel.delete_confirm}

					],

					gotoPage: true,

					paginate: true,

					total: true,

					rowCount: true

				},

				saveUrl: "index.php?controller=pjAdminListings&action=pjActionSavePassword&id={:id}",

				select: {

					field: "id",

					name: "record[]"

				}

			});

		}

	});

})(jQuery_1_8_2);