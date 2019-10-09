if(screen.width <= 640) {
	jQuery('head').prepend('<meta name="viewport" content="width=640, initial-scale=0.5, maximum-scale=0.5, user-scalable=0">')
} else {
	jQuery('head').prepend('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">');
} 



jQuery(document).ready(function() {
	var $body 	 = jQuery("body");
	var $wrapper = jQuery(".wrapper");
	var $windoW  = window.innerWidth;

	jQuery(window).resize(function() {
		$windoW  = window.innerWidth;
	});

	/* ========== MENU MOBILE ======= */
	jQuery('.openMenuMobile a').on('click', function() {
		$wrapper.addClass('slided').hide(500);
		jQuery('.mobileNavigation').show(0).addClass('slided');
	});
	jQuery('.backToDesk, .activeTopMobileNav').on('click', function() {
		jQuery('.mobileNavigation').removeClass('slided').hide(500);
		$wrapper.show(0).removeClass('slided');
	});
	$body.on('click', '#nav a.parent', function(e) {
		e.preventDefault();
		jQuery(this).siblings('.level0').toggle();
		jQuery(this).parent().toggleClass('opened');
	});

	/* ==========  HEADER  ========= */
	//flag
	jQuery('.flag').on('click', function() {
		jQuery(this).find('.nations').slideToggle(200);
	}).on('mouseleave', function() {
		setTimeout(function() { jQuery('.nations').slideUp(200); }, 2000);
	});


	//account
	if ($windoW > 767) {
		jQuery('a.account').on('click', function(e){
			e.preventDefault();
			jQuery('.customer-choice').slideToggle(200);
		});
        jQuery('.customer-choice').on( "mouseleave", function() {
            jQuery('.customer-choice').hide();
        });
	}

	//carrello
	jQuery('.thirdPart').find('.block-cart').on('click', function() {
		var $this = jQuery(this);
		$this.toggleClass('opened');
	});

	// ricerca a comparsa
	jQuery("#nav .nav-8, .searchMobile").on("click", "a", function(e) {
		e.preventDefault();
		jQuery("#search_mini_form").toggle(200);
	});

	/* ========= HOME ========== */
	if($body.hasClass('cms-home')) {
		/* hover su prodotti in slider */
		productHover('.rightBlog li.item');
		/* hover su prodotti in vetrina */
		productHover('.voluti li.item');
		/*slider*/
		makeRemoveSlider(959);
	}

	/* ======= CATEGORIA E RICERCA ======== */
	if($body.hasClass('catalog-category-view') || $body.hasClass('catalogsearch-result-index') || 
		$body.hasClass('catalogsearch-advanced-result') || $body.hasClass('megaforum-index-profile')) {
		/* sposto titolo pagina in colonna sinistra */
		if ($windoW > 767) { jQuery('.page-title').prependTo('.col-left'); }
		/* hover su prodotti  */
		productHover('.products-grid li.item');
		/* stilizzo toolbar */
        var select = jQuery('.sort-by select').hide(),
            newSelect = '<div class="select"><ul>',
            selected = '',
            notSelected = '';
        select.find('option').each(function() {
            if(jQuery(this).attr('selected')) {
                selected += "<li><a href='" + jQuery(this).val() + "' class='selected'>" + jQuery(this).text() + "</a></li>";
            } else {
                notSelected += "<li><a href='" + jQuery(this).val() + "'>" + jQuery(this).text() + "</a></li>";
            }
        });
        newSelect += selected;
        newSelect += notSelected;
        newSelect += "</ul></div>";
        jQuery('.sort-by label').after(jQuery(newSelect));
        jQuery('.sort-by').on('mouseenter', '.select', function() {
            var $this = jQuery(this);
            if(!$this.hasClass('absolute') && $this.find('.absolute').length <= 0) {
                var clone = jQuery('.select').clone().addClass('absolute');
                clone.prependTo('.select');
                clone.on('mouseleave', function() {
                    jQuery(this).remove();
                });
            }
        });


        if($windoW <= 767) {
        	var $listaProdottiNew;
        	var $listaProdotti;
        	var optioN;
        	transformSide('.col-left.sidebar', '.page-title');
	    	jQuery('.category-products').find('.products-grid').each(function(){
	    		if(typeof jQuery(this).html() != null) {
	    			$listaProdotti += jQuery(this).html();
	    		}
	    		jQuery(this).remove();
	    	});
	    	$listaProdotti = $listaProdotti.replace('undefined', '');
	    	$listaProdottiNew = jQuery('<ul class="products-grid">' + $listaProdotti + '</ul>');
	    	jQuery('.toolbar').after($listaProdottiNew);
	    	jQuery('.products-grid').find('li').removeClass('first').removeClass('last');
	    	jQuery('.products-grid').text().replace('undefined', '');
	    }
	}



	/* ======= PRODOTTO ======== */
	if($body.hasClass('catalog-product-view')) {
		var $rating = jQuery('.ratings').find('.rating');
		var $oldWidth = $rating.width();
		var $timeout;
		/* rating del prodotto */
		jQuery('.ratings').find('.rat').on('mouseenter', function() {
			var $this = jQuery(this);
			clearTimeout($timeout);
			if($this.hasClass('rat1')) { 	   $rating.css('width', '20px');  } 
			else if($this.hasClass('rat2')) {  $rating.css('width', '40px');  } 
			else if($this.hasClass('rat3')) {  $rating.css('width', '67px');  } 
			else if($this.hasClass('rat4')) {  $rating.css('width', '90px');  } 
			else if($this.hasClass('rat5')) {  $rating.css('width', '120px'); }
		}).on('mouseleave', function() {
			$timeout = setTimeout(function() { $rating.css('width', $oldWidth); } , 200);
		}).on('click', function() {
			var $this = jQuery(this);
			var $data = $this.data('check');
			jQuery('#product-review-table').find('input').removeAttr('checked');
			jQuery('#product-review-table').find('input[id="' + $data + '"]').attr('checked', 'checked');
			jQuery('#review-form').submit();
		});
		/* seleziona total look */
		jQuery('.checkBot').on('click', function(){
			var $this = jQuery(this);
			$this.toggleClass('selected');
			$this.prev().trigger('click');
		});

		/* hover su prodotti  */
		productHover('.products-grid li.item');


		if($windoW <= 767) {
			//jQuery('.product-name').after(jQuery('.product-img-box'));
		}
	}

	/*======= CARRELLO ========*/
	if($body.hasClass('checkout-cart-index')) { 
		/* aumenta diminuisci la quantità */

		jQuery('.qt').on('click', function() {
            var $this = jQuery(this);
           	if($this.hasClass('minus')) {
                $qty = parseInt($this.next().val());
				$this.next().val($qty - 1);
			} else {
                $qty = parseInt($this.prev().val());
				$this.prev().val($qty + 1);
			}
			$this.closest('form').submit();
		});
	}


	/*======== CHECKOUT =========*/
	if($body.hasClass('opc-index-index')) {
		/* slide box login*/
		jQuery('.alreadyReg a').on('click', function() {
			jQuery('#myModal').slideToggle(350);
		});
        jQuery('#myModalClose').on('click', function() {
            jQuery('#myModal').slideUp(350);
        });
	}


	/* ========== MENU SINISTRA PAGINE STATICHE ========= */
	if($body.hasClass('cms-page-view') || $body.hasClass('wishlist-index-index') || $body.hasClass('customer-account-index') || 
	   $body.hasClass('customer-address-index') || $body.hasClass('customer-account-edit') || $body.hasClass('newsletter-manage-index') ||
	   $body.hasClass('sales-order-history') || $body.hasClass('sales-order-view') || $body.hasClass('downloadable-customer-products') || 
	   $body.hasClass('amrma-customer-history') || $body.hasClass('amrma-customer-view') || $body.hasClass('product-index-index') || 
	   $body.hasClass('product-index-edit') || $body.hasClass('megaforum-index-myforumtopics') || $body.hasClass('megaforum-index-inbox')) {
		var pagina = jQuery(location).attr('href');
			pagina = new String(pagina.match('[^/]*$'));	
	    jQuery.each(jQuery('.cms-page-view .col-left.sidebar ul#menu li a'), function(index, val){
	        var link = jQuery(val).attr('href');
	        link = new String(link.match('[^/]*$'));
	        if(link.valueOf() ===  pagina.valueOf()){
	            jQuery(val).removeAttr('href');
	            jQuery(val).parent('li').addClass('current');
	        }
	    });
	    if($windoW <= 767) {
		    if(!$body.hasClass('customer-account-index')) {
		    	transformSide('.col-left.sidebar', '.page-title');
		    } else {
		    	transformSide('.col-left.sidebar', '.dashboard .welcome-msg');
		    }
		}
	}


	/* ==== DICHIARAZIONE FUNZIONI ==== */

	/* hover su prodotto in lista */
	function productHover(item) {
		jQuery(document).on('mouseenter', item, function() {
			if ($windoW > 959) {
				var $this = jQuery(this);
				var $clone = $this.clone().addClass('absolute');
				if(!$this.hasClass('absolute')) {
					$this.prepend($clone);
				}
				$this.on('mouseleave', '.absolute', function() {
					jQuery(this).remove();
				});
			}
		}).on('mouseleave', item, function() {
			jQuery(this).find('.absolute').remove();
		});
	}

	/* slider attiva o rimuovi in base alla grandezza schermo */
	function makeRemoveSlider(work) {
		$blockCt = jQuery('.block-central');
		if ($windoW > work) {
			jQuery('#cyclePrev, #cycleNext').show(0);
			$blockCt.find('div.cycle-slide').show(0);
			$blockCt.cycle({
				slides: '> div',
				next:   '#cycleNext',
				prev:    '#cyclePrev'
			});
		} else {
			$blockCt.cycle('destroy');
			$blockCt.find('div').hide(0);
			jQuery('#cyclePrev, #cycleNext').hide(0);
			$blockCt.find('div').first().show(0);
		}	
	}


	/* menu sinistra trasformato in option */
	function transformSide(item, item2) {
		var $select = jQuery("<select id='sideSelect'></select>");
		var $item   = jQuery(item);
		var $item2  = jQuery(item2);
		var option;
		$item.find('li a').each(function(){
			var $this = jQuery(this);
			var selected;
			if(!$this.parent().hasClass('current')) { selected = ''; } else { selected = 'selected'; }
			option += "<option value='" + $this.attr('href') + "' " + selected + ">" + $this.html() + "</option>";
		});
		option = option.replace('undefined', '');
		jQuery(option).appendTo($select);
		$item2.after($select);
		$item.hide(0);
		jQuery('#sideSelect').on('change', function(){
			var $this = jQuery(this);
			var $href = $this.find(':selected').val();
			location.href = $href;
		}); 
	}

	jQuery('#slOpenVideo').on('click', function(){
		jQuery('.close-slOpenVideoIframe').show();
	});
	

	jQuery('.close-slOpenVideoIframe').on('click', function(){
		jQuery('#slOpenVideoIframe').hide();
		jQuery(this).hide();
	});

	if (jQuery('#video-slide').length) {
		jQuery('#video-slide').get(0).on('play',function() {
			jQuery('.info-video-container').hide();
		});

		jQuery('#video-slide').get(0).on('ended',function() {
			jQuery(this).attr('src', "");
			jQuery(this).attr('src', jQuery(this).find('source').attr('data-src'));
			jQuery('.info-video-container').show();
		});
	}


	//SOCIAL SHARE

    jQuery(".FB_share").on({
        click: function(){
            var sharerFB = "http://www.facebook.com/sharer/sharer.php?u="+window.location.href;
            window.open(sharerFB, '', 'directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,width=500,height=300'); 
        }
     });
    
    jQuery(".TW_share").on({
        click: function(){
			sharerTW = "https://twitter.com/intent/tweet?source=webclient&text="+window.location.href;
			window.open(sharerTW, '', 'directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,width=500,height=300');
        }
     });
    
    jQuery(".GP_share").on({
        click: function(){        	
        	getStoreCode = jQuery("#hidden-store-code").attr("data-store-code");
			var sharerGPLUS = "https://plus.google.com/share?hl='"+getStoreCode+"'&url="+window.location.href;
			window.open(sharerGPLUS, '', 'directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,width=500,height=500'); 
        }
     });    
	
    jQuery(".PI_share").on({
        click: function(){        	
        	getMedia = jQuery("#zoom img").attr("src");
        	getDesc = jQuery(".tab1").text();
			var sharerPNT = "http://pinterest.com/pin/create/button/?media="+getMedia+"&description="+getDesc+"&url="+window.location.href;
			window.open(sharerPNT);
        }
     }); 
    

});