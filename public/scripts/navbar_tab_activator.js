// Add's 'action' class to active navigation tab. Copied from stackoverflow
$(function(){

	var url = window.location.pathname;
	// var activePage = stripTrailingSlash(url);
	var pathArray = url.split( '/' );
	var activePage = pathArray['1'];

	$('.navmenu > li > a').each(function(){
		// var currentPage = stripTrailingSlash($(this).attr('href'));
		var str = $(this).attr("href");
		// console.log(str);
		var currentPage = str.substr(1, str.length - 1);
		if (activePage == currentPage) {
			$(this).parent().addClass('active');
		}
	});
});