$(function(){

	var url = window.location.pathname;
	var pathArray = url.split( '/' );
	var activePage = pathArray['4'];

	if (activePage == null) {
		activePage = 'all';
	}
	
	$('.adm-orders a').each(function(){
		var currentPageArray = ($(this).attr('href')).split( '/' );
		var currentPage = currentPageArray['4'];
		
		if (activePage == currentPage) {
			$(this).parent().addClass('active');
		}
	});
});