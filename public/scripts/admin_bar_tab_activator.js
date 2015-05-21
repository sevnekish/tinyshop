// Add's 'action' class to active navigation tab. 
$(function(){

	var url = window.location.pathname;
	// var activePage = stripTrailingSlash(url);
	var pathArray = url.split( '/' );
	var activePage = pathArray['2'];

	$('.adminbar a').each(function(){
		// var currentPage = stripTrailingSlash($(this).attr('href'));
		var currentPageArray = ($(this).attr('href')).split( '/' );
		var currentPage = currentPageArray['2'];
		
		if (activePage == currentPage) {
			$(this).addClass('active');
		}
	});
});