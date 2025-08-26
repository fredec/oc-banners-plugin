document.addEventListener("DOMContentLoaded", function() {
	var banners_noredirect=0, banner_redirect_url='';
	document.addEventListener('click', function (e) {
		if (e.target.closest('a._add_clicks')) {
			banners_noredirect=0;
			banner_redirect_url=e.target.href;

			if(e.target.target == '_blank'){
				e.target.removeAttribute('data-request');
				noredirect=1;
			}else{
				noredirect=0;
				e.preventDefault();
			}

			Snowboard.request(e.target, 'onBannersAddClick', {
				loading: true,
				data: { noredirect: noredirect, id: e.target.getAttribute('data-id') },
				success: () => {
					if (!noredirect) {
						location.href = banner_redirect_url;
					}
				}
			});
		}
	});
});