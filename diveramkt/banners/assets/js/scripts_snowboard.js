document.addEventListener("DOMContentLoaded", function() {
	var banners_noredirect=0, banner_redirect_url='';
	document.addEventListener('click', function (e) {
		var link=false;
		if(e.target.classList.contains('_add_clicks')){
			link=e.target;
		}else if (e.target.closest('a._add_clicks')) {
			link=e.target.closest('a._add_clicks')
		}
		if(link){
			banners_noredirect=0;
			banner_redirect_url=link.href;

			if(link.target == '_blank'){
				link.removeAttribute('data-request');
				noredirect=1;
			}else{
				const urlLimpa = window.location.origin + window.location.pathname;
				if (urlLimpa === banner_redirect_url.split('#')[0] || urlLimpa+'/' === banner_redirect_url.split('#')[0]) {
					noredirect=1;
				}else{
					noredirect=0;
				}
				e.preventDefault();
			}

			Snowboard.request(link, 'onBannersAddClick', {
				loading: true,
				data: { noredirect: noredirect, id: link.getAttribute('data-id') },
				success: () => {
					if (!noredirect) {
						location.href = banner_redirect_url;
					}
				}
			});
		}
	});
});