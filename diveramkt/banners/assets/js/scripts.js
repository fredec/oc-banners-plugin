$(document).ready(function(){

	$(document).on('click','a._add_clicks',function(e){
		var noredirect=0, redirect_url=$(this).attr('href');
		if($(this).attr('target') == '_blank'){
			$(this).removeAttr('data-request');
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
		$(this).request('onBannersAddClick', {
			loading: $.oc.stripeLoadIndicator,
			data: { noredirect: noredirect },
			success: function(){
				if(!noredirect) location.href = redirect_url;
			},
		});
	});

});