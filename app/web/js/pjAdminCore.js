(function ($, undefined) {
	$(function () {
		$(".pj-table tbody tr").hover(
			function () {
				$(this).addClass("pj-table-row-hover");
			}, 
			function () {
				$(this).removeClass("pj-table-row-hover");
			}
		);
		$(".pj-button").hover(
			function () {
				$(this).addClass("pj-button-hover");
			}, 
			function () {
				$(this).removeClass("pj-button-hover");
			}
		);
		$(".pj-checkbox").hover(
				function () {
					$(this).addClass("pj-checkbox-hover");
				}, 
				function () {
					$(this).removeClass("pj-checkbox-hover");
				}
			);
		$("#content").on("click", ".notice-close", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).closest(".notice-box").fadeOut();
			return false;
		});
		
		$("html").on("click", "*", function (e) {
			if ($(this).hasClass("dropdown-toggle")) {
				$(this).toggleClass("dropdown-closed").siblings("ul").toggle();
				e.preventDefault();
				return false;
			}

			$(".dropdown-nav")
				.find(".dropdown-toggle").addClass("dropdown-closed")
				.end()
				.find("ul").hide();
		});
	});
})(jQuery);