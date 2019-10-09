/*
 * Amasty Scroll
 *  
 * @copyright   Copyright (c) 2009-2012 Amasty (http://www.amasty.com)
 */



function amscroll() {

	this.frameHeight = null;

	this.initialized = false;

	/*
	 * Button action was set
	 */
	this.loaderSet = false;

	/*
	 * Ignore flat that loader was set
	 */
	this.ignoreLoaderSetFlag = false;

	/*
	 * Current page
	 */
	this.page = 1;

	/*
	 * Amount of pages to load within curretn search/filters.
	 */
	this.pagesCount = 0;

	/*
	 * Top offset
	 */
	this.previousTop = 0;

	/*
	 * Fetch
	 */
	this.pagesMultiplier = 3;

	/*
	 * All pages loaded, now new pages to load
	 */
	this.pagesReached = false;

	/*
	 * Navigation element
	 */
	this.navBarElement = null;

	/*
	 * Holds amount of loaded pages
	 */
	this.loadedPagesNumbers = 1;

	/*
	 * Url to make request
	 */
	this.url = '';

	this.placeHolderElement = null;

	/*
	 * Ajax request can be done:
	 * - auto - automatically
	 * - button - on button click
	 */
	this.actionMode = 'auto';

	/*
	 * Label on button
	 */
	this.loadingTextButton = '';

	/*
	 * Path to loading image
	 */
	this.loadingImage = '';

	/*
	 * Progress bar config
	 */
	this.progressbar = {};

	/*
	 * Display page numbers or not
	 */
	this.pageNumbers = 0;

	this.loadNextStyle = '';

	this.init = function(params)
	{

		this.initialized = true;
		this.loaderSet = false;

		this.destroyNavBarElement();

		if (params) {
			for (param in params) {
				this[param] = params[param];
			}
		}

		/*
		 * Remove bottom toolbar
		 */
		var toolbar = $$(amscroll_toolbar_bottom)[0];
		if (toolbar) {
			$(toolbar).remove();
		}

		/*
		 * Hide pager
		 */
		var pager = $$(amscroll_pager)[0];
		if (pager) {
			$(pager).hide();
		}


		/*
		 * Get total pages count
		 */

		this.pagesCount = this.getPagesCount();

		var el = $$(amscroll_product_container_group)[0];
		/*
		 * Render page frames
		 */
		if (el && this.pagesCount > 1) {
			var i = 2;
			while(i <= this.pagesCount) {

				/* Page Number */
				if (this.pageNumbers == 1) {
					var pageNumEl =  new Element('div', {
						'class': 'amscroll-page-num',
						'id' : 'amscroll-page-num-' + i
					});
					$(pageNumEl).update('Page #' + i);
					el.insert(pageNumEl, {position: 'content' });
				}

				/* Page Frame */
				var id = 'amscroll-page-' + i;
				var element = new Element('div', {
					'class': 'amscroll-page',
					'id' : id,
					'rel' : i
				}).update('');
				el.insert(element, {position: 'content'});

				i++;
			}
		}

		/*
		 * Calculate frame height depending on loaded page
		 */
		this.getFrameHeight();


		if (!this.page || this.page == 0) {
			/*
			 * Get page number from hash
			 */
			var page = this.getUrlParam('page');

			if (page) {

				this.page = page;

				var me = this;
				if (this.getFrameHeight()) {
					$$('div.amscroll-page').each(function(item) {
						var itemPage = parseInt($(item).readAttribute('rel'));
						if (itemPage <= me.page) {
							$(item).setStyle({height : me.getFrameHeight() + 'px'});
							if (me.actionMode == 'button') {
								me.ignoreLoaderSetFlag = true;
								me.getLoadingPlaceholder(true, itemPage);
							}
						}
					});
				}

				if (this.actionMode == 'button') {
					this.ignoreLoaderSetFlag = false;
				}

				/*
				 * Scroll to previous position
				 */
				window.scrollTo(0, this.getUrlParam('top'));


				this.loadNextPage(this.page, true);

				/*
				 * Remove flag that page was in history
				 */
				this.setHashParam('external', null);
			}

			if (!this.page) {
				this.page = 2;
			}
		}
	};

	this.getFrameHeight = function()
	{
		if (this.frameHeight == null) {
			var el = $$(amscroll_product_container_group)[0];
			if (el) {
				this.frameHeight = $(el).getHeight();
			} else {
				this.frameHeight = 960;
			}
		}
		return this.frameHeight;

	};

	/*
	 * All pages were loaded
	 */
	this.limitReached = function()
	{
		return (this.loadedPagesNumbers == this.pagesCount);
	};

	/*
	 * Check that page "page" should be loaded.
	 * Order of blocks is imporant
	 */
	this.shouldLoadNextPage = function(page)
	{
		/*
		 * If page is not loaded yet or not in loading - allow
		 */
		var ret = false;
		$$('div.amscroll-page').each(function(item) {
			if (parseInt($(item).readAttribute('rel')) == page && !$(item).hasClassName('loaded') && !$(item).hasClassName('loading')) {
				ret = true;
			}
		});
		if (ret) {
			return true;
		}

		/*
		 * Page should be loaded taking into account height and current postion
		 */
	    if (document.viewport) {
	        var top = document.viewport.getScrollOffsets().top;
	        var height = document.viewport.getHeight();

	        var docHeight = Math.max(
	        		Math.max(document.body.scrollHeight, document.documentElement.scrollHeight),
	        		Math.max(document.body.clientHeight, document.documentElement.clientHeight),
	                Math.max(document.body.offsetHeight, document.documentElement.offsetHeight)
	        );

	        return (docHeight - top) <= (this.pagesMultiplier * height);
	    }

		/*
		 * Limit reached - loading should be stopped
		 */
		if (this.limitReached()) {
			return false;
		}

		return true;
	};

	this.destroyNavBarElement = function()
	{
		if ($('amscroll-navbar')) {
			$('amscroll-navbar').remove();
		}
		this.navBarElement = null;
	};

	this.renderPaginator = function(current)
	{
		var element = this.getNavBarElement();
		if (element) {
			if (current > 1 && current <= this.pagesCount && this.loadedPagesNumbers > 1) {
				element.style.display = 'block';
				var html = '<div class="amscroll-top"><a href="#">Top</a></div><div class="amscroll-pager"><ul>';
				var str = 'Page {0} of {1}';
				html += '<li>Page ' + current + ' from ' + this.pagesCount + '</li>';
				html += '</ul></div><div class="amscroll-tab">&nbsp;</div>';
				element.update(html);
			} else {
				element.style.display = 'none';
			}
		}
	};

	this.setHashParam  = function(key, value)
	{
		var params = this.getUrlParam();
		var hash = '';
		if (value == null) {
			delete params[key];
		} else {
			params[key] = value;
		}

		var i = 0;
		for (param in params) {
			hash += param + '=' + params[param] + '&';
			i++;
		}
		window.location.hash = hash;
	};


	this.getUrlParam = function(param, type)
	{
	    var hashParams = {};
	    var e,
	        a = /\+/g,  // Regex for replacing addition symbol with a space
	        r = /([^&;=]+)=?([^&;]*)/g,
	        d = function (s) { return decodeURIComponent(s.replace(a, " ")); };

	    var s = window.location.hash;

	    if (type == 'url') {
	    	s = window.location.search;
	    }
	    q = s.substring(1);

	    while (e = r.exec(q))
	       hashParams[d(e[1])] = d(e[2]);

	    if (typeof param == "undefined") {
	    	return hashParams;
	    } else {
	    	return hashParams[param];
	    }
	};


	/*
	 * Return true if scrolling down;
	 */
	this.getDirection = function()
	{
		var diff = (document.viewport.getScrollOffsets().top > this.previousTop);
		return diff;
	};

	this.setUrl = function(url)
	{
		this.url = url;
	};

	this.getLoadingPlaceholder = function(direction, page)
	{
		var p = parseInt(page);

		if (p <=0) {
			return;
		}

		var tmpElement = document.getElementById('amscroll-page-' + p);

		if (!tmpElement) {
			return null;
		}

		if (this.actionMode == 'button') {
			if ($(tmpElement).hasClassName('loading') == false && $(tmpElement).hasClassName('loaded') == false && (this.ignoreLoaderSetFlag || !this.loaderSet)) {
				$(tmpElement).addClassName('loading');

				tmpElement.innerHTML = '<input type="button" class="amscroll-load-button" style="' + this.loadNextStyle + '" onclick="amscroll_object.loadNextPage(' + p + ');" value="' + this.loadingTextButton + '" />';
				this.loaderSet = true;

				if (this.pageNumbers == 1) {
        			$('amscroll-page-num-' + p).setStyle({display: 'block'});
        		}
			}
			return tmpElement;
		}

		if ($(tmpElement) && ($(tmpElement).hasClassName('loaded') ||  $(tmpElement).hasClassName('loading'))) {
			return null;
		}

		$(tmpElement).addClassName('loading');

		return tmpElement;
	};

	/*
	 * Perform ajax action
	 */
	this.loadNextPage = function(pageNumber, direction)
	{

		/*
		 * Exceeding amount of pages
		 */
		if (pageNumber > this.pagesCount) {
			return;
		}

		/*
		 * Current object
		 */
		var me = this;
		var url = this.url;
		var tmpElement = this.getLoadingPlaceholder(direction, pageNumber);

		if (tmpElement == null) {
			return;
		}

		if (me.pageNumbers == 1) {
   			$('amscroll-page-num-' + (pageNumber)).setStyle({display: 'block'});
		}

		tmpElement.innerHTML = '<div class="amscroll-loading">&nbsp;</div>';

		var params = this.getUrlParam();
		params.is_ajax = 1;
		params.p = pageNumber;

		var request = new Ajax.Request(url, {
            method : 'get',
            parameters : params,
            onSuccess: function(response){
                data = response.responseText;
                data = data.evalJSON();

                var tmpPage = document.createElement('div');

                tmpPage.innerHTML = data.page.replace(/\?id\//g,'id/');


                //Uncomment line below in case of any redirects due to js in page code
                //tmpPage.innerHTML = data.page.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "");

                /*
                 * Remove toolbar and pagings from received HTML
                 */
                var elements = $(tmpPage).getElementsBySelector(amscroll_toolbar);
                if (elements.length > 0) {
                	elements[0].remove();
                }

                var elements = $(tmpPage).getElementsBySelector(amscroll_toolbar_bottom);
                if (elements.length > 0) {
                	elements[0].remove();
                }

                $(tmpElement).update($(tmpPage).innerHTML);

                var actualHeight = $(tmpElement).down(0).getHeight();

                /*
                 * Adjust block height
                 */

               	$(tmpElement).setStyle({height: 'auto'});

                	/*
                	 * Check that current page is not last item
                	 */
                	if (pageNumber < this.pagesCount) {
                		me.frameHeight = actualHeight;
                	}

            },
            onFailure: function(){
                setLocation(url);
            },
            onComplete: function() {

            	/* Increase amount of loaded pages */
            	me.loadedPagesNumbers++;

            	/* Mark current frame as "loaded" */
            	$(tmpElement).addClassName('loaded');

            	/* Bind click on any link or button within received frame */
            	me.bindClick();

            	amscroll_external();

            	me.loaderSet = false;

            }
        });
	};

	this.bindClick = function() {
		var me = this;
		$$(amscroll_product_container + ' a, ' + amscroll_product_container + ' input').each(function(e){
            e.onclick = function(){
                me.setHashParam('external', 1);
        }});
	};


	/*
	 * Initialize progress bar (if required)
	 */
	this.getNavBarElement = function() {

		var progressbar = this.progressbar;
		if (progressbar.enabled == 0) {
			return null;
		}
		if (this.navBarElement == null) {
			this.navBarElement = new Element('div', {
				'class': 'amscroll-navbar',
				'id' : 'amscroll-navbar',
				'style' : progressbar.offset
				}).update('');

			$(this.navBarElement).setStyle({
				'width' : progressbar.width,
				'position' : 'fixed',
				'background' : progressbar.background
			});

			$(document.body).insert(this.navBarElement);
		}
		return this.navBarElement;
	};

	this.handleScroll = function() {
		var direction = this.getDirection();
		if (document.viewport) {
	        var top = document.viewport.getScrollOffsets().top;

	        	if (top == 0) {
	        		top = 1;
	        	}

	        	var currentPage  = this.getCurrentPage();

	        	this.setHashParam('page', currentPage);

	       	if (this.pagesCount > 0) {
	       		this.renderPaginator(currentPage);
	       	}
	       	if (!this.getUrlParam('external')) {
	        	this.setHashParam('top', top);
	       	}
		}
		if (typeof amshopby_working == 'undefined' || amshopby_working == false) {
		    if (this.actionMode == 'auto') {

		    	if (this.shouldLoadNextPage(currentPage)) {
		    		this.loadNextPage(currentPage);
		    	}

		    	if (this.shouldLoadNextPage(currentPage + 1)) {
		    		this.loadNextPage(currentPage + 1);
		    	}
		    	if (this.getDirection() == false) {
			    	if (this.shouldLoadNextPage(currentPage - 1)) {
			    		this.loadNextPage(currentPage - 1);
			    	}
		    	}
		    }
		    if (this.actionMode == 'button') {
		    	if (this.shouldLoadNextPage(currentPage)) {
		    		this.getLoadingPlaceholder(true, currentPage);
		    	}

		    	if (this.shouldLoadNextPage(currentPage + 1)) {
		    		this.getLoadingPlaceholder(true, currentPage + 1);
		    	}
		    	if (this.getDirection() == false) {
		    		if (this.shouldLoadNextPage(currentPage - 1)) {
		    			this.getLoadingPlaceholder(false, currentPage - 1);
		    		}
		    	}

		    }
		}
	    this.previousTop = top;
	}

	this.getCurrentPage = function() {
		var currentPage = amscroll_params.page;
		var top = document.viewport.getScrollOffsets().top;

		if (this.getUrlParam('external')) {
    		if (this.getUrlParam('page')) {
    			currentPage = parseInt(this.getUrlParam('page'));
    		} else {
    			currentPage = parseInt(this.page);
    		}
    	} else {
    		currentPage = parseInt(Math.ceil(top/this.getFrameHeight()));
    	}
    	currentPage = parseInt(amscroll_params.page) + parseInt(currentPage);
		return currentPage;
	}

	this.getPagesCount = function() {
		var selector = 'div.pager p.amount';
		var pager = $$(selector);
		if (pager[0]) {
			var str = pager[0].innerHTML;
			var re = /\D+(\d+)\D+(\d+)\D+(\d+)/;
			var result = re.exec(str);
			if (result && result.length == 4) {
				if (result[3] > 0 && result[2] > 0) {
					return Math.ceil(parseInt(result[3])/parseInt(result[2]));
				}
			}
		}
		return 1;
	}

}

function amscroll_external()
{
	 //add here all external scripts for page reloading
}