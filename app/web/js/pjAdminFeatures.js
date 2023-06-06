var jQuery_1_8_2 = $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateFeature = $("#frmCreateFeature"),
			$frmUpdateFeature = $("#frmUpdateFeature"),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);

		if ($frmCreateFeature.length > 0 && validate) {
			$frmCreateFeature.validate({
				messages: {
					"i18n[1][name]": {
						required: myLabel.field_required
					},
					"i18n[2][name]": {
						required: myLabel.field_required
					},
					"i18n[3][name]": {
						required: myLabel.field_required
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
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
				}
			});
		}
		if ($frmUpdateFeature.length > 0 && validate) {
			$frmUpdateFeature.validate({
				messages: {
					"i18n[1][name]": {
						required: myLabel.field_required
					},
					"i18n[2][name]": {
						required: myLabel.field_required
					},
					"i18n[3][name]": {
						required: myLabel.field_required
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				errorClass: "err",
				wrapper: "em",
				ignore: "",
				invalidHandler: function (event, validator) {
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
				}
			});
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminFeatures&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminFeatures&action=pjActionDeleteFeature&id={:id}"}
				          ],
				columns: [{text: myLabel.feature, type: "text", sortable: true, editable: true, width: 330},
				          {text: myLabel.type, type: "text", sortable: true, editable: false, width: 200},
				          {text: myLabel.status, type: "select", sortable: true, editable: true, options: [
				                                                                                     {label: myLabel.active, value: "T"}, 
				                                                                                     {label: myLabel.inactive, value: "F"}
				                                                                                     ], applyClass: "pj-status"}],
				dataUrl: "index.php?controller=pjAdminFeatures&action=pjActionGetFeature",
				dataType: "json",
				fields: ['name', 'type', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminFeatures&action=pjActionDeleteFeatureBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.revert_status, url: "index.php?controller=pjAdminFeatures&action=pjActionStatusFeature", render: true},
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminFeatures&action=pjActionSaveFeature&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			});
		}
		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$('.pj-form-field-search').val('');
			$(this).addClass("pj-button-active").siblings(".pj-button").removeClass("pj-button-active");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminFeatures&action=pjActionGetFeature", "type", "ASC", content.page, content.rowCount);
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
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminFeatures&action=pjActionGetFeature", "type", "ASC", content.page, content.rowCount);
			return false;
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminFeatures&action=pjActionGetFeature", "type", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);