var jQuery_1_8_2 = $.noConflict();
(function ($, undefined) {
	$(function () {
		var dialog = ($.fn.dialog !== undefined),
			validate = ($.fn.validate !== undefined),
			$dialogDeletePeriod = $("#dialogDeletePeriod"),
			$frmListingPage = $("#frmListingPage"),
			$frmOptions = $("#frmOptions"),
			tipsy = ($.fn.tipsy !== undefined),
			tabs = ($.fn.tabs !== undefined),
			$tabs = $("#tabs"),
			$seo_tabs = $("#seo_tabs"),
			tOpt = {
				activate: function (event, ui) {
					$(":input[name='tab_id']").val($(ui.newPanel).prop('id'));
				}
			},
			seo_tOpt = {
				activate: function (event, ui) {
					$(":input[name='seo_tab_id']").val($(ui.newPanel).prop('id'));
				}
			};
		
		if ($tabs.length > 0 && tabs) {
			$tabs.tabs(tOpt);
		}
		if ($seo_tabs.length > 0 && tabs) {
			$seo_tabs.tabs(seo_tOpt);
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
		
		if ($frmListingPage.length > 0 && validate) {
			$frmListingPage.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				errorClass: "err",
				wrapper: "em",
				onkeyup: false,
				ignore: ""
			});
		}
		
		$("#content").on("focusin", ".textarea_install", function (e) {
			$(this).select();
		}).on("change", "select[name='value-enum-o_send_email']", function (e) {
			switch ($("option:selected", this).val()) {
			case 'mail|smtp::mail':
				$(".boxSmtp").hide();
				break;
			case 'mail|smtp::smtp':
				$(".boxSmtp").show();
				break;
			}
		}).on("click", ".btnAddPeriod", function () {
			var $c = $("#tblPeriodClone tbody").clone(),
			r = $c.html().replace(/\{INDEX\}/g, 'new_' + Math.ceil(Math.random() * 99999));
			$(this).closest("form").find("table").find("tbody").append(r);
		}).on("click", ".btnDeletePeriod", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($dialogDeletePeriod.length > 0 && dialog) {
				$dialogDeletePeriod.data("link", $(this)).dialog("open");
			}
			return false;
		}).on("click", ".btnRemovePeriod", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $tr = $(this).closest("tr");
			$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
				$tr.remove();
			});			
			return false;
		}).on("click", ".pj-use-theme", function (e) {
			var theme = $(this).attr('data-theme');
			$('.pj-loader').css('display', 'block');
			$.ajax({
				type: "GET",
				async: false,
				url: 'index.php?controller=pjAdminOptions&action=pjActionUpdateTheme&theme=' + theme,
				success: function (data) {
					$('.theme-holder').html(data);
					$('.pj-loader').css('display', 'none');
				}
			});
		});
		
		if ($dialogDeletePeriod.length > 0 && dialog) {
			var buttons = {};
			buttons[myLabel.btn_delete] = function () {
				var $this = $(this),
					$link = $this.data("link"),
					$tr = $link.closest("tr"),
					id = $link.data("id");
				
				$.post("index.php?controller=pjAdminOptions&action=pjActionDeletePeriod", {
					"id": id
				}).done(function (data) {
					if (data.code === undefined) {
						return;
					}
					switch (data.code) {
						case 200:
							$tr.css("backgroundColor", "#FFB4B4").fadeOut("slow", function () {
								$tr.remove();
								$this.dialog("close");
							});
							break;
					}
				});
			};
			buttons[myLabel.btn_cancel] = function () {
				$(this).dialog("close");
			};
			$dialogDeletePeriod.dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				draggable: false,
				buttons: buttons,
				width: 350
			});
		}
	});
})(jQuery_1_8_2);