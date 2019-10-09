
function ra_checkCart(obj, product_id, qty,base_url){
	if(obj.checked == true){
		$('check_'+product_id).hide();
		$('loader_'+product_id).show();
		jQuery(".sidebar .block-cart" ).wrap('<div id="cart_blog" />');
		jQuery(".header .links" ).wrap('<div id="top_links" />');
		
		var url = base_url+'related/index/updatecart';
		new Ajax.Request(url, {
			method: 'post',
			parameters: { pid : product_id, qty : qty, isAjax : 1 },
			onComplete: function(transport) {
				var data = transport.responseText.evalJSON();
					
					$('top_links').innerHTML = data.toplink;
					if($('cart_blog')){
						$('cart_blog').innerHTML = data.sidebar;
					}
					$('gritter-title').innerHTML = data.addmessage;
					$('gritter-pro-image').src = data.productimage; 
					$('pro_title').innerHTML = data.producttitle;
					$('check_'+product_id).show();
					$('loader_'+product_id).hide();
			}
		});
		show_confirm();
	}
	if(obj.checked == false){
		$('check_'+product_id).hide();
		$('loader_'+product_id).show();
		jQuery(".sidebar .block-cart" ).wrap('<div id="cart_blog" />');
		jQuery(".header .links" ).wrap('<div id="top_links" />');
		var url = base_url+'related/index/deletecart';
		new Ajax.Request(url, {
			method: 'post',
			parameters: { pid : product_id, qty : qty, isAjax : 1 },
			onComplete: function(transport) {
				var data = transport.responseText.evalJSON();
					$('top_links').innerHTML = data.toplink;
					if($('cart_blog')){
						$('cart_blog').innerHTML = data.sidebar;
					}
					$('gritter-title').innerHTML = data.removemessage;
					$('gritter-pro-image').src = data.productimage; 
					$('pro_title').innerHTML = data.producttitle;
					$('check_'+product_id).show();
					$('loader_'+product_id).hide();
			}
		});
		show_confirm();
	}
}
function show_confirm(){
		setTimeout(function(){
			jQuery("#gritter-notice-wrapper").fadeIn("slow", function ()
            {
				jQuery("#gritter-notice-wrapper").delay(2000).fadeOut("slow");       
            });
         }, 1000);
}
function ra_onmouseover(rowtype,img,id,count){
	$('ra_img_'+rowtype).addClassName('highlight');
	$('ra_img_'+rowtype).src = img;
	$('ra_fb_'+rowtype).href = img;
	var tr_offset = jQuery('#'+'ra_row2_'+id).offset();
	var ra_container = jQuery('#'+'ra_row2_'+id).parent().parent().parent();
	var img_obj = jQuery('#'+'ra_fb_' + rowtype);
	var new_top;
	if(count !=1){
		if ((ra_container.offset().top + ra_container.height()) > (tr_offset.top + img_obj.height()))
		{
			
			new_top = tr_offset.top;
		}
		else
		{
			
			new_top = (ra_container.offset().top + ra_container.height()) - img_obj.height();
		}
		
		img_obj.offset({top: new_top});
	}
}
function ra_onmouseout(rowtype,img){
	$('ra_img_'+rowtype).removeClassName('highlight');
	$('ra_img_'+rowtype).src = img;
	$('ra_fb_'+rowtype).href = img;
	
}

function Pop(theURL, name) {
	var pop;
	if (name)
	{
		winname = name
	}
	else
	{
		winname = "pop"
	}

	var features = "scrollbars=yes,resizable=yes,width=1280,height=600";
	pop = window.open(theURL, winname, features);
	if ((document.window != null) && (!pop.opener))
	{
		pop.opener = document.window;
	}
	pop.focus();
	miWin = true;
}
