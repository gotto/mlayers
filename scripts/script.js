$(function() {
	$(".menu-button").click(function() {
		$(".menu").slideToggle();
	});
	$(".menu li").click(function() {
		location.href = $(this).children("a").attr("href");
	});
});