  
 /**
	* PLUGIN -> hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
	* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
	* 
	* @param  f  onMouseOver function || An object with configuration options
	* @param  g  onMouseOut function  || Nothing (use configuration options object)
	* @author    Brian Cherne brian(at)cherne(dot)net
	*/
$.fn.hoverIntent = function (f, g) {
    var cfg = {
        sensitivity: 7,
        interval: 100,
        timeout: 0
    };
    cfg = $.extend(cfg, g ? {
        over: f,
        out: g
    } : f);
    var cX, cY, pX, pY;
    var track = function (ev) {
        cX = ev.pageX;
        cY = ev.pageY
    };
    var compare = function (ev, ob) {
        ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
        if ((Math.abs(pX - cX) + Math.abs(pY - cY)) < cfg.sensitivity) {
            $(ob).unbind("mousemove", track);
            ob.hoverIntent_s = 1;
            return cfg.over.apply(ob, [ev])
        } else {
            pX = cX;
            pY = cY;
            ob.hoverIntent_t = setTimeout(function () {
                compare(ev, ob)
            }, cfg.interval)
        }
    };
    var delay = function (ev, ob) {
        ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
        ob.hoverIntent_s = 0;
        return cfg.out.apply(ob, [ev])
    };
    var handleHover = function (e) {
        var ev = jQuery.extend({}, e);
        var ob = this;
        if (ob.hoverIntent_t) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t)
        }
        if (e.type == "mouseenter") {
            pX = ev.pageX;
            pY = ev.pageY;
            $(ob).bind("mousemove", track);
            if (ob.hoverIntent_s != 1) {
                ob.hoverIntent_t = setTimeout(function () {
                    compare(ev, ob)
                }, cfg.interval)
            }
        } else {
            $(ob).unbind("mousemove", track);
            if (ob.hoverIntent_s == 1) {
                ob.hoverIntent_t = setTimeout(function () {
                    delay(ev, ob)
                }, cfg.timeout)
            }
        }
    };
    return this.bind('mouseenter', handleHover).bind('mouseleave', handleHover)
};

 /**
	* PLUGIN -> zentriert und passt die größe von bildern an das elternelement an
	*/
jQuery.fn.fitToParent = function(options) {
	
	var defaults = {
	 spacing: 0,
	 parent: false
	}, options = $.extend(defaults, options);

	this.each(function() {
			var width  						= $(this).width(),
					height 						= $(this).height(),
					parentWidth  			= ( parent ) ? $(this).parents(parent).width() : $(this).parent().width(),
					parentHeight 			= ( parent ) ? $(this).parents(parent).height() : $(this).parent().height(),
					orientation				= (width == height) ? 'cubic' : (width > height) ? 'horizontal' : 'vertical',
					parentOrientation	= (parentWidth == parentHeight) ? 'cubic' : (parentWidth > parentHeight) ? 'horizontal' : 'vertical',
					space							= options.spacing,
					spaceSize					= options.spacing * 2,
					_nowidth					= (width === 'undefined' || width === null || width === 0),
					_noheight					= (height === 'undefined' || height === null || height === 0)
					overflow					= ((width - parentWidth) > (height - parentHeight)) ? 'x' : 'y'
			;
			
			if (_nowidth || _noheight) return;
			
			// NEW
			if(width > parentWidth || height > parentHeight) {
				
				if (orientation === 'cubic' && parentOrientation === 'cubic' ) {
					
					var ratio = 1;
					var newWidth  		= parentWidth;
					var newHeight 		= parentHeight;
					var marginTop  		= 'auto';
					var marginLeft 		= 'auto';
					
				} else {
					
					if (overflow === 'x') {
						var ratio 				= parentWidth / width;
						var newWidth  		= parentWidth;
						var newHeight 		= height * ratio;
						var marginTop  		= (parentHeight - newHeight) / 2;
						var marginLeft 		= 'auto';
						var height 				= height * ratio;
					} else {
						var ratio 				= parentHeight / height;
						var newWidth  		= width * ratio;
						var newHeight 		= parentHeight;
						var marginTop  		= 'auto';
						var marginLeft 		= (parentWidth - newWidth) / 2;
						var width 				= width * ratio;
					}
					
				}
				
			} else {
				
				var ratio = 1;
				var newWidth  			= width;
				var newHeight 			= height;
				var marginTop  			= (parentHeight - height) / 2;
				var marginLeft 			= (parentWidth - width ) / 2;
				
			}
			// NEW
			
			if (space > 0) {
				
				if (isNaN(marginTop)) marginTop = null;
				if (isNaN(marginLeft)) marginLeft = null;
				
				var newWidth  			= newWidth - spaceSize;
				var newHeight 			= newHeight - spaceSize;
				var marginTop  			= marginTop + space;
				var marginLeft 			= marginLeft + space;
			}
			
			$(this).removeAttr('width').removeAttr('height').css({marginTop: marginTop, marginLeft: marginLeft, 'width': newWidth, 'height': newHeight});
	});
    	
  
  /*!
   * jQuery.ScrollTo
   * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
   * Dual licensed under MIT and GPL.
   * Date: 4/09/2012
   *
   * @projectDescription Easy element scrolling using jQuery.
   * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
   * @author Ariel Flesler
   * @version 1.4.3.1
   *
   * @id jQuery.scrollTo
   * @id jQuery.fn.scrollTo
   * @param {String, Number, DOMElement, jQuery, Object} target Where to scroll the matched elements.
   *	  The different options for target are:
   *		- A number position (will be applied to all axes).
   *		- A string position ('44', '100px', '+=90', etc ) will be applied to all axes
   *		- A jQuery/DOM element ( logically, child of the element to scroll )
   *		- A string selector, that will be relative to the element to scroll ( 'li:eq(2)', etc )
   *		- A hash { top:x, left:y }, x and y can be any kind of number/string like above.
   *		- A percentage of the container's dimension/s, for example: 50% to go to the middle.
   *		- The string 'max' for go-to-end. 
   * @param {Number, Function} duration The OVERALL length of the animation, this argument can be the settings object instead.
   * @param {Object,Function} settings Optional set of settings or the onAfter callback.
   *	 @option {String} axis Which axis must be scrolled, use 'x', 'y', 'xy' or 'yx'.
   *	 @option {Number, Function} duration The OVERALL length of the animation.
   *	 @option {String} easing The easing method for the animation.
   *	 @option {Boolean} margin If true, the margin of the target element will be deducted from the final position.
   *	 @option {Object, Number} offset Add/deduct from the end position. One number for both axes or { top:x, left:y }.
   *	 @option {Object, Number} over Add/deduct the height/width multiplied by 'over', can be { top:x, left:y } when using both axes.
   *	 @option {Boolean} queue If true, and both axis are given, the 2nd axis will only be animated after the first one ends.
   *	 @option {Function} onAfter Function to be called after the scrolling ends. 
   *	 @option {Function} onAfterFirst If queuing is activated, this function will be called after the first scrolling ends.
   * @return {jQuery} Returns the same jQuery object, for chaining.
   *
   * @desc Scroll to a fixed position
   * @example $('div').scrollTo( 340 );
   *
   * @desc Scroll relatively to the actual position
   * @example $('div').scrollTo( '+=340px', { axis:'y' } );
   *
   * @desc Scroll using a selector (relative to the scrolled element)
   * @example $('div').scrollTo( 'p.paragraph:eq(2)', 500, { easing:'swing', queue:true, axis:'xy' } );
   *
   * @desc Scroll to a DOM element (same for jQuery object)
   * @example var second_child = document.getElementById('container').firstChild.nextSibling;
   *			$('#container').scrollTo( second_child, { duration:500, axis:'x', onAfter:function(){
   *				alert('scrolled!!');																   
   *			}});
   *
   * @desc Scroll on both axes, to different values
   * @example $('div').scrollTo( { top: 300, left:'+=200' }, { axis:'xy', offset:-20 } );
   */
  
  ;(function( $ ){
  	
  	var $scrollTo = $.scrollTo = function( target, duration, settings ){
  		$(window).scrollTo( target, duration, settings );
  	};
  
  	$scrollTo.defaults = {
  		axis:'xy',
  		duration: parseFloat($.fn.jquery) >= 1.3 ? 0 : 1,
  		limit:true
  	};
  
  	// Returns the element that needs to be animated to scroll the window.
  	// Kept for backwards compatibility (specially for localScroll & serialScroll)
  	$scrollTo.window = function( scope ){
  		return $(window)._scrollable();
  	};
  
  	// Hack, hack, hack :)
  	// Returns the real elements to scroll (supports window/iframes, documents and regular nodes)
  	$.fn._scrollable = function(){
  		return this.map(function(){
  			var elem = this,
  				isWin = !elem.nodeName || $.inArray( elem.nodeName.toLowerCase(), ['iframe','#document','html','body'] ) != -1;
  
  				if( !isWin )
  					return elem;
  
  			var doc = (elem.contentWindow || elem).document || elem.ownerDocument || elem;
  			
  			return /webkit/i.test(navigator.userAgent) || doc.compatMode == 'BackCompat' ?
  				doc.body : 
  				doc.documentElement;
  		});
  	};
  
  	$.fn.scrollTo = function( target, duration, settings ){
  		if( typeof duration == 'object' ){
  			settings = duration;
  			duration = 0;
  		}
  		if( typeof settings == 'function' )
  			settings = { onAfter:settings };
  			
  		if( target == 'max' )
  			target = 9e9;
  			
  		settings = $.extend( {}, $scrollTo.defaults, settings );
  		// Speed is still recognized for backwards compatibility
  		duration = duration || settings.duration;
  		// Make sure the settings are given right
  		settings.queue = settings.queue && settings.axis.length > 1;
  		
  		if( settings.queue )
  			// Let's keep the overall duration
  			duration /= 2;
  		settings.offset = both( settings.offset );
  		settings.over = both( settings.over );
  
  		return this._scrollable().each(function(){
  			// Null target yields nothing, just like jQuery does
  			if (target == null) return;
  
  			var elem = this,
  				$elem = $(elem),
  				targ = target, toff, attr = {},
  				win = $elem.is('html,body');
  
  			switch( typeof targ ){
  				// A number will pass the regex
  				case 'number':
  				case 'string':
  					if( /^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ) ){
  						targ = both( targ );
  						// We are done
  						break;
  					}
  					// Relative selector, no break!
  					targ = $(targ,this);
  					if (!targ.length) return;
  				case 'object':
  					// DOMElement / jQuery
  					if( targ.is || targ.style )
  						// Get the real position of the target 
  						toff = (targ = $(targ)).offset();
  			}
  			$.each( settings.axis.split(''), function( i, axis ){
  				var Pos	= axis == 'x' ? 'Left' : 'Top',
  					pos = Pos.toLowerCase(),
  					key = 'scroll' + Pos,
  					old = elem[key],
  					max = $scrollTo.max(elem, axis);
  
  				if( toff ){// jQuery / DOMElement
  					attr[key] = toff[pos] + ( win ? 0 : old - $elem.offset()[pos] );
  
  					// If it's a dom element, reduce the margin
  					if( settings.margin ){
  						attr[key] -= parseInt(targ.css('margin'+Pos)) || 0;
  						attr[key] -= parseInt(targ.css('border'+Pos+'Width')) || 0;
  					}
  					
  					attr[key] += settings.offset[pos] || 0;
  					
  					if( settings.over[pos] )
  						// Scroll to a fraction of its width/height
  						attr[key] += targ[axis=='x'?'width':'height']() * settings.over[pos];
  				}else{ 
  					var val = targ[pos];
  					// Handle percentage values
  					attr[key] = val.slice && val.slice(-1) == '%' ? 
  						parseFloat(val) / 100 * max
  						: val;
  				}
  
  				// Number or 'number'
  				if( settings.limit && /^\d+$/.test(attr[key]) )
  					// Check the limits
  					attr[key] = attr[key] <= 0 ? 0 : Math.min( attr[key], max );
  
  				// Queueing axes
  				if( !i && settings.queue ){
  					// Don't waste time animating, if there's no need.
  					if( old != attr[key] )
  						// Intermediate animation
  						animate( settings.onAfterFirst );
  					// Don't animate this axis again in the next iteration.
  					delete attr[key];
  				}
  			});
  
  			animate( settings.onAfter );			
  
  			function animate( callback ){
  				$elem.animate( attr, duration, settings.easing, callback && function(){
  					callback.call(this, target, settings);
  				});
  			};
  
  		}).end();
  	};
  	
  	// Max scrolling position, works on quirks mode
  	// It only fails (not too badly) on IE, quirks mode.
  	$scrollTo.max = function( elem, axis ){
  		var Dim = axis == 'x' ? 'Width' : 'Height',
  			scroll = 'scroll'+Dim;
  		
  		if( !$(elem).is('html,body') )
  			return elem[scroll] - $(elem)[Dim.toLowerCase()]();
  		
  		var size = 'client' + Dim,
  			html = elem.ownerDocument.documentElement,
  			body = elem.ownerDocument.body;
  
  		return Math.max( html[scroll], body[scroll] ) 
  			 - Math.min( html[size]  , body[size]   );
  	};
  
  	function both( val ){
  		return typeof val == 'object' ? val : { top:val, left:val };
  	};
  
  })( jQuery );
    
};

  
  /*!
   * jQuery.SESSION Handling
   * Author https://github.com/AlexChittock/JQuery-Session-Plugin/blob/master/jquery.session.js
  */
(function($){

    $.session = {

        _id: null,

        _cookieCache: undefined,

        _init: function()
        {
            if (!window.name) {
                window.name = Math.random();
            }
            this._id = window.name;
            this._initCache();

            // See if we've changed protcols

            var matches = (new RegExp(this._generatePrefix() + "=([^;]+);")).exec(document.cookie);
            if (matches && document.location.protocol !== matches[1]) {
               this._clearSession();
               for (var key in this._cookieCache) {
                   try {
                   window.sessionStorage.setItem(key, this._cookieCache[key]);
                   } catch (e) {};
               }
            }

            document.cookie = this._generatePrefix() + "=" + document.location.protocol + ';path=/;expires=' + (new Date((new Date).getTime() + 120000)).toUTCString();

        },

        _generatePrefix: function()
        {
            return '__session:' + this._id + ':';
        },

        _initCache: function()
        {
            var cookies = document.cookie.split(';');
            this._cookieCache = {};
            for (var i in cookies) {
                var kv = cookies[i].split('=');
                if ((new RegExp(this._generatePrefix() + '.+')).test(kv[0]) && kv[1]) {
                    this._cookieCache[kv[0].split(':', 3)[2]] = kv[1];
                }
            }
        },

        _setFallback: function(key, value, onceOnly)
        {
            var cookie = this._generatePrefix() + key + "=" + value + "; path=/";
            if (onceOnly) {
                cookie += "; expires=" + (new Date(Date.now() + 120000)).toUTCString();
            }
            document.cookie = cookie;
            this._cookieCache[key] = value;
            return this;
        },

        _getFallback: function(key)
        {
            if (!this._cookieCache) {
                this._initCache();
            }
            return this._cookieCache[key];
        },

        _clearFallback: function()
        {
            for (var i in this._cookieCache) {
                document.cookie = this._generatePrefix() + i + '=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            }
            this._cookieCache = {};
        },

        _deleteFallback: function(key)
        {
            document.cookie = this._generatePrefix() + key + '=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            delete this._cookieCache[key];
        },

        get: function(key)
        {
            return window.sessionStorage.getItem(key) || this._getFallback(key);
        },

        set: function(key, value, onceOnly)
        {
            try {
                window.sessionStorage.setItem(key, value);
            } catch (e) {}
            this._setFallback(key, value, onceOnly || false);
            return this;
        },
        
        'delete': function(key){
            return this.remove(key);
        },

        remove: function(key)
        {
            try {
            window.sessionStorage.removeItem(key);
            } catch (e) {};
            this._deleteFallback(key);
            return this;
        },

        _clearSession: function()
        {
          try {
                window.sessionStorage.clear();
            } catch (e) {
                for (var i in window.sessionStorage) {
                    window.sessionStorage.removeItem(i);
                }
            }
        },

        clear: function()
        {
            this._clearSession();
            this._clearFallback();
            return this;
        }

    };

    $.session._init();

})(jQuery);



/**
 * jCarousel Core
 */
(function($) {
    'use strict';

    var jCarousel = $.jCarousel = {};

    jCarousel.version = '@VERSION';

    var rRelativeTarget = /^([+\-]=)?(.+)$/;

    jCarousel.parseTarget = function(target) {
        var relative = false,
            parts    = typeof target !== 'object' ?
                           rRelativeTarget.exec(target) :
                           null;

        if (parts) {
            target = parseInt(parts[2], 10) || 0;

            if (parts[1]) {
                relative = true;
                if (parts[1] === '-=') {
                    target *= -1;
                }
            }
        } else if (typeof target !== 'object') {
            target = parseInt(target, 10) || 0;
        }

        return {
            target: target,
            relative: relative
        };
    };

    jCarousel.detectCarousel = function(element) {
        var carousel;

        while (element.length > 0) {
            carousel = element.filter('[data-jcarousel]');

            if (carousel.length > 0) {
                return carousel;
            }

            carousel = element.find('[data-jcarousel]');

            if (carousel.length > 0) {
                return carousel;
            }

            element = element.parent();
        }

        return null;
    };

    jCarousel.base = function(pluginName) {
        return {
            version:  jCarousel.version,
            _options:  {},
            _element:  null,
            _carousel: null,
            _init:     $.noop,
            _create:   $.noop,
            _destroy:  $.noop,
            _reload:   $.noop,
            create: function() {
                this._element
                    .attr('data-' + pluginName.toLowerCase(), true)
                    .data(pluginName, this);

                if (false === this._trigger('create')) {
                    return this;
                }

                this._create();

                this._trigger('createend');

                return this;
            },
            destroy: function() {
                if (false === this._trigger('destroy')) {
                    return this;
                }

                this._destroy();

                this._trigger('destroyend');

                this._element
                    .removeData(pluginName)
                    .removeAttr('data-' + pluginName.toLowerCase());

                return this;
            },
            reload: function(options) {
                if (false === this._trigger('reload')) {
                    return this;
                }

                if (options) {
                    this.options(options);
                }

                this._reload();

                this._trigger('reloadend');

                return this;
            },
            element: function() {
                return this._element;
            },
            options: function(key, value) {
                if (arguments.length === 0) {
                    return $.extend({}, this._options);
                }

                if (typeof key === 'string') {
                    if (typeof value === 'undefined') {
                        return typeof this._options[key] === 'undefined' ?
                                null :
                                this._options[key];
                    }

                    this._options[key] = value;
                } else {
                    this._options = $.extend({}, this._options, key);
                }

                return this;
            },
            carousel: function() {
                if (!this._carousel) {
                    this._carousel = jCarousel.detectCarousel(this.options('carousel') || this._element);

                    if (!this._carousel) {
                        $.error('Could not detect carousel for plugin "' + pluginName + '"');
                    }
                }

                return this._carousel;
            },
            _trigger: function(type, element, data) {
                var event,
                    defaultPrevented = false;

                data = [this].concat(data || []);

                (element || this._element).each(function() {
                    event = $.Event((pluginName + ':' + type).toLowerCase());

                    $(this).trigger(event, data);

                    if (event.isDefaultPrevented()) {
                        defaultPrevented = true;
                    }
                });

                return !defaultPrevented;
            }
        };
    };

    jCarousel.plugin = function(pluginName, pluginPrototype) {
        var Plugin = $[pluginName] = function(element, options) {
            this._element = $(element);
            this.options(options);

            this._init();
            this.create();
        };

        Plugin.fn = Plugin.prototype = $.extend(
            {},
            jCarousel.base(pluginName),
            pluginPrototype
        );

        $.fn[pluginName] = function(options) {
            var args        = Array.prototype.slice.call(arguments, 1),
                returnValue = this;

            if (typeof options === 'string') {
                this.each(function() {
                    var instance = $(this).data(pluginName);

                    if (!instance) {
                        return $.error(
                            'Cannot call methods on ' + pluginName + ' prior to initialization; ' +
                            'attempted to call method "' + options + '"'
                        );
                    }

                    if (!$.isFunction(instance[options]) || options.charAt(0) === '_') {
                        return $.error(
                            'No such method "' + options + '" for ' + pluginName + ' instance'
                        );
                    }

                    var methodValue = instance[options].apply(instance, args);

                    if (methodValue !== instance && typeof methodValue !== 'undefined') {
                        returnValue = methodValue;
                        return false;
                    }
                });
            } else {
                this.each(function() {
                    var instance = $(this).data(pluginName);

                    if (instance instanceof Plugin) {
                        instance.reload(options);
                    } else {
                        new Plugin(this, options);
                    }
                });
            }

            return returnValue;
        };

        return Plugin;
    };
}(jQuery));

/**
 * jCarousel Control Plugin
 *
 * Depends:
 *     core.js
 *     core_plugin.js
 */
(function($) {
    'use strict';

    $.jCarousel.plugin('jcarouselControl', {
        _options: {
            target: '+=1',
            event:  'click',
            method: 'scroll'
        },
        _active: null,
        _init: function() {
            this.onDestroy = $.proxy(function() {
                this._destroy();
                this.carousel()
                    .one('jcarousel:createend', $.proxy(this._create, this));
            }, this);
            this.onReload = $.proxy(this._reload, this);
            this.onEvent = $.proxy(function(e) {
                e.preventDefault();

                var method = this.options('method');

                if ($.isFunction(method)) {
                    method.call(this);
                } else {
                    this.carousel()
                        .jcarousel(this.options('method'), this.options('target'));
                }
            }, this);
        },
        _create: function() {
            this.carousel()
                .one('jcarousel:destroy', this.onDestroy)
                .on('jcarousel:reloadend jcarousel:scrollend', this.onReload);

            this._element
                .on(this.options('event') + '.jcarouselcontrol', this.onEvent);

            this._reload();
        },
        _destroy: function() {
            this._element
                .off('.jcarouselcontrol', this.onEvent);

            this.carousel()
                .off('jcarousel:destroy', this.onDestroy)
                .off('jcarousel:reloadend jcarousel:scrollend', this.onReload);
        },
        _reload: function() {
            var parsed   = $.jCarousel.parseTarget(this.options('target')),
                carousel = this.carousel(),
                active;

            if (parsed.relative) {
                active = carousel
                    .jcarousel(parsed.target > 0 ? 'hasNext' : 'hasPrev');
            } else {
                var target = typeof parsed.target !== 'object' ?
                                carousel.jcarousel('items').eq(parsed.target) :
                                parsed.target;

                active = carousel.jcarousel('target').index(target) >= 0;
            }

            if (this._active !== active) {
                this._trigger(active ? 'active' : 'inactive');
                this._active = active;
            }

            return this;
        }
    });
}(jQuery));

/*! 
 * bootstrap-touch-carousel v0.7.1
 * https://github.com/ixisio/bootstrap-touch-carousel.git 
 * 
 * Copyright (c) 2014 (ixisio) Andreas Klein
 * Licensed under the MIT license
 * 
 * 
 * Including Hammer.js@1.0.6dev, http://eightmedia.github.com/hammer.js 
 */ 
+ function (a) {
    "use strict";

    function b(a, b) {
        var c = document.createElement("div").style;
        for (var d in a)
            if (void 0 !== c[a[d]]) return "pfx" == b ? a[d] : !0;
        return !1
    }

    function c() {
        var a = document.createElement("bootstrap"),
            b = {
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "oTransitionEnd otransitionend",
                transition: "transitionend"
            };
        for (var c in b)
            if (void 0 !== a.style[c]) return {
                end: b[c]
            }
    }

    function d() {
        var a = ["transformProperty", "WebkitTransform", "MozTransform", "msTransform"];
        return !!b(a)
    }

    function e() {
        return "WebKitCSSMatrix" in window && "m11" in new WebKitCSSMatrix
    }
    if (!("ontouchstart" in window || navigator.msMaxTouchPoints)) return !1;
    a.fn.emulateTransitionEnd = function (b) {
        var c = !1,
            d = this;
        a(this).one(a.support.transition.end, function () {
            c = !0
        });
        var e = function () {
            c || a(d).trigger(a.support.transition.end)
        };
        return setTimeout(e, b), this
    }, a(function () {
        a.support.transition = c(), a.support.csstransforms = d(), a.support.csstransforms3d = e()
    });
    var f = "touch-carousel",
        g = function (b, c) {
            return this.$element = a(b), this.$itemsWrapper = this.$element.find(".carousel-inner"), this.$items = this.$element.find(".item"), this.$indicators = this.$element.find(".carousel-indicators"), this.pane_width = this.pane_count = this.current_pane = 0, this.onGesture = !1, this.options = c, this._setPaneDimensions(), this.$items.length <= 1 ? this.disable() : (this._regTouchGestures(), a(window).on("orientationchange resize", a.proxy(this._setPaneDimensions, this)), void 0)
        };
    g.DEFAULTS = {
        interval: !1,
        toughness: .25
    }, g.prototype.cycle = function (b) {
        return b || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this
    }, g.prototype.to = function (a) {
        return a > this.$items.length - 1 || 0 > a ? void 0 : this._showPane(a)
    }, g.prototype.pause = function (a) {
        return a || (this.paused = !0), clearInterval(this.interval), this.interval = null, this
    }, g.prototype._regTouchGestures = function () {
        this.$itemsWrapper.add(this.$indicators).hammer({
            drag_lock_to_axis: !0
        }).on("release dragleft dragright swipeleft swiperight", a.proxy(this._handleGestures, this))
    }, g.prototype._setPaneDimensions = function () {
        this.pane_width = this.$element.width(), this.pane_count = this.$items.length, this.$itemsWrapper.width(this.pane_width * this.pane_count), this.$items.width(this.pane_width)
    }, g.prototype._showPane = function (a) {
        this.$items.eq(this.current_pane).toggleClass("active"), a >= this.pane_count && this.pause(), a = Math.max(0, Math.min(a, this.pane_count - 1)), this.$items.eq(a).toggleClass("active"), this.current_pane = a;
        var b = -(100 / this.pane_count * this.current_pane);
        return this._setContainerOffset(b, !0, a), this
    }, g.prototype._setContainerOffset = function (b, c, d) {
        var e = this;
        if (this.$itemsWrapper.removeClass("animate"), c && this.$itemsWrapper.addClass("animate"), a.support.csstransforms3d) this.onGesture = !0, this.$itemsWrapper.css("transform", "translate3d(" + b + "%,0,0) scale3d(1,1,1)");
        else if (a.support.csstransforms) this.onGesture = !0, this.$itemsWrapper.css("transform", "translate(" + b + "%,0)");
        else {
            var f = this.pane_width * this.pane_count / 100 * b;
            this.$itemsWrapper.css("left", f + "px")
        }
        a.support.transition ? this.$itemsWrapper.one(a.support.transition.end, function () {
            e.$itemsWrapper.removeClass("animate"), e.onGesture = !1, e._updateIndicators(d)
        }) : (this.$itemsWrapper.removeClass("animate"), this.onGesture = !1, this._updateIndicators(d))
    }, g.prototype.next = function () {
        return this._showPane(this.current_pane + 1)
    }, g.prototype.prev = function () {
        return this._showPane(this.current_pane - 1)
    }, g.prototype._handleGestures = function (a) {
        if (a.gesture.preventDefault(), !this.sliding) switch (this.pause(), a.type) {
        case "dragright":
        case "dragleft":
            var b = -(100 / this.pane_count) * this.current_pane,
                c = 100 / this.pane_width * a.gesture.deltaX / this.pane_count;
            (0 === this.current_pane && a.gesture.direction == Hammer.DIRECTION_RIGHT || this.current_pane == this.pane_count - 1 && a.gesture.direction == Hammer.DIRECTION_LEFT) && (c *= this.options.toughness), this._setContainerOffset(c + b);
            break;
        case "swipeleft":
            this.next(), a.gesture.stopDetect();
            break;
        case "swiperight":
            this.prev(), a.gesture.stopDetect();
            break;
        case "release":
            Math.abs(a.gesture.deltaX) > this.pane_width / 2 ? "right" == a.gesture.direction ? this.prev() : this.next() : this._showPane(this.current_pane, !0)
        }
    }, g.prototype.disable = function () {
        return this.$indicators.hide(), this.$element.removeData(f), !1
    }, g.prototype._updateIndicators = function (a) {
        return this.$indicators.length && (this.$indicators.find(".active").removeClass("active"), this.$indicators.children().eq(a).addClass("active")), this
    };
    var h = a.fn.carousel;
    a.fn.carousel = function (b) {
        return this.each(function () {
            var c = a(this),
                d = c.data(f),
                e = a.extend({}, g.DEFAULTS, c.data(), "object" == typeof b && b),
                h = "string" == typeof b ? b : e.slide;
            d || c.data(f, d = new g(this, e)).addClass(f), "number" == typeof b ? d.to(b) : h ? d[h]() : e.interval && d.pause().cycle()
        })
    }, a.fn.carousel.Constructor = g, a.fn.carousel.noConflict = function () {
        return a.fn.carousel = h, this
    }, a(document).off("click.bs.carousel").on("click.bs.carousel.data-api", "[data-slide], [data-slide-to]", function (b) {
        var c, d = a(this),
            e = a(d.attr("data-target") || (c = d.attr("href")) && c.replace(/.*(?=#[^\s]+$)/, "")),
            g = a.extend({}, e.data(), d.data()),
            h = d.attr("data-slide-to");
        h && (g.interval = !1), e.carousel(g), (h = d.attr("data-slide-to")) && e.data(f).to(h), b.preventDefault()
    })
}(window.jQuery),
function (a, b) {
    "use strict";

    function c() {
        if (!d.READY) {
            d.event.determineEventTypes();
            for (var a in d.gestures) d.gestures.hasOwnProperty(a) && d.detection.register(d.gestures[a]);
            d.event.onTouch(d.DOCUMENT, d.EVENT_MOVE, d.detection.detect), d.event.onTouch(d.DOCUMENT, d.EVENT_END, d.detection.detect), d.READY = !0
        }
    }
    var d = function (a, b) {
        return new d.Instance(a, b || {})
    };
    d.defaults = {
        stop_browser_behavior: {
            userSelect: "none",
            touchAction: "none",
            touchCallout: "none",
            contentZooming: "none",
            userDrag: "none",
            tapHighlightColor: "rgba(0,0,0,0)"
        }
    }, d.HAS_POINTEREVENTS = a.navigator.pointerEnabled || a.navigator.msPointerEnabled, d.HAS_TOUCHEVENTS = "ontouchstart" in a, d.MOBILE_REGEX = /mobile|tablet|ip(ad|hone|od)|android|silk/i, d.NO_MOUSEEVENTS = d.HAS_TOUCHEVENTS && a.navigator.userAgent.match(d.MOBILE_REGEX), d.EVENT_TYPES = {}, d.DIRECTION_DOWN = "down", d.DIRECTION_LEFT = "left", d.DIRECTION_UP = "up", d.DIRECTION_RIGHT = "right", d.POINTER_MOUSE = "mouse", d.POINTER_TOUCH = "touch", d.POINTER_PEN = "pen", d.EVENT_START = "start", d.EVENT_MOVE = "move", d.EVENT_END = "end", d.DOCUMENT = a.document, d.plugins = {}, d.READY = !1, d.Instance = function (a, b) {
        var e = this;
        return c(), this.element = a, this.enabled = !0, this.options = d.utils.extend(d.utils.extend({}, d.defaults), b || {}), this.options.stop_browser_behavior && d.utils.stopDefaultBrowserBehavior(this.element, this.options.stop_browser_behavior), d.event.onTouch(a, d.EVENT_START, function (a) {
            e.enabled && d.detection.startDetect(e, a)
        }), this
    }, d.Instance.prototype = {
        on: function (a, b) {
            for (var c = a.split(" "), d = 0; d < c.length; d++) this.element.addEventListener(c[d], b, !1);
            return this
        },
        off: function (a, b) {
            for (var c = a.split(" "), d = 0; d < c.length; d++) this.element.removeEventListener(c[d], b, !1);
            return this
        },
        trigger: function (a, b) {
            b || (b = {});
            var c = d.DOCUMENT.createEvent("Event");
            c.initEvent(a, !0, !0), c.gesture = b;
            var e = this.element;
            return d.utils.hasParent(b.target, e) && (e = b.target), e.dispatchEvent(c), this
        },
        enable: function (a) {
            return this.enabled = a, this
        }
    };
    var e = null,
        f = !1,
        g = !1;
    d.event = {
        bindDom: function (a, b, c) {
            for (var d = b.split(" "), e = 0; e < d.length; e++) a.addEventListener(d[e], c, !1)
        },
        onTouch: function (a, b, c) {
            var h = this;
            this.bindDom(a, d.EVENT_TYPES[b], function (i) {
                var j = i.type.toLowerCase();
                if (!j.match(/mouse/) || !g) {
                    j.match(/touch/) || j.match(/pointerdown/) || j.match(/mouse/) && 1 === i.which ? f = !0 : j.match(/mouse/) && 1 !== i.which && (f = !1), j.match(/touch|pointer/) && (g = !0);
                    var k = 0;
                    f && (d.HAS_POINTEREVENTS && b != d.EVENT_END ? k = d.PointerEvent.updatePointer(b, i) : j.match(/touch/) ? k = i.touches.length : g || (k = j.match(/up/) ? 0 : 1), k > 0 && b == d.EVENT_END ? b = d.EVENT_MOVE : k || (b = d.EVENT_END), (k || null === e) && (e = i), c.call(d.detection, h.collectEventData(a, b, h.getTouchList(e, b), i)), d.HAS_POINTEREVENTS && b == d.EVENT_END && (k = d.PointerEvent.updatePointer(b, i))), k || (e = null, f = !1, g = !1, d.PointerEvent.reset())
                }
            })
        },
        determineEventTypes: function () {
            var a;
            a = d.HAS_POINTEREVENTS ? d.PointerEvent.getEvents() : d.NO_MOUSEEVENTS ? ["touchstart", "touchmove", "touchend touchcancel"] : ["touchstart mousedown", "touchmove mousemove", "touchend touchcancel mouseup"], d.EVENT_TYPES[d.EVENT_START] = a[0], d.EVENT_TYPES[d.EVENT_MOVE] = a[1], d.EVENT_TYPES[d.EVENT_END] = a[2]
        },
        getTouchList: function (a) {
            return d.HAS_POINTEREVENTS ? d.PointerEvent.getTouchList() : a.touches ? a.touches : (a.indentifier = 1, [a])
        },
        collectEventData: function (a, b, c, e) {
            var f = d.POINTER_TOUCH;
            return (e.type.match(/mouse/) || d.PointerEvent.matchType(d.POINTER_MOUSE, e)) && (f = d.POINTER_MOUSE), {
                center: d.utils.getCenter(c),
                timeStamp: (new Date).getTime(),
                target: e.target,
                touches: c,
                eventType: b,
                pointerType: f,
                srcEvent: e,
                preventDefault: function () {
                    this.srcEvent.preventManipulation && this.srcEvent.preventManipulation(), this.srcEvent.preventDefault && this.srcEvent.preventDefault()
                },
                stopPropagation: function () {
                    this.srcEvent.stopPropagation()
                },
                stopDetect: function () {
                    return d.detection.stopDetect()
                }
            }
        }
    }, d.PointerEvent = {
        pointers: {},
        getTouchList: function () {
            var a = this,
                b = [];
            return Object.keys(a.pointers).sort().forEach(function (c) {
                b.push(a.pointers[c])
            }), b
        },
        updatePointer: function (a, b) {
            return a == d.EVENT_END ? this.pointers = {} : (b.identifier = b.pointerId, this.pointers[b.pointerId] = b), Object.keys(this.pointers).length
        },
        matchType: function (a, b) {
            if (!b.pointerType) return !1;
            var c = {};
            return c[d.POINTER_MOUSE] = b.pointerType == b.MSPOINTER_TYPE_MOUSE || b.pointerType == d.POINTER_MOUSE, c[d.POINTER_TOUCH] = b.pointerType == b.MSPOINTER_TYPE_TOUCH || b.pointerType == d.POINTER_TOUCH, c[d.POINTER_PEN] = b.pointerType == b.MSPOINTER_TYPE_PEN || b.pointerType == d.POINTER_PEN, c[a]
        },
        getEvents: function () {
            return ["pointerdown MSPointerDown", "pointermove MSPointerMove", "pointerup pointercancel MSPointerUp MSPointerCancel"]
        },
        reset: function () {
            this.pointers = {}
        }
    }, d.utils = {
        extend: function (a, c, d) {
            for (var e in c) a[e] !== b && d || (a[e] = c[e]);
            return a
        },
        hasParent: function (a, b) {
            for (; a;) {
                if (a == b) return !0;
                a = a.parentNode
            }
            return !1
        },
        getCenter: function (a) {
            for (var b = [], c = [], d = 0, e = a.length; e > d; d++) b.push(a[d].pageX), c.push(a[d].pageY);
            return {
                pageX: (Math.min.apply(Math, b) + Math.max.apply(Math, b)) / 2,
                pageY: (Math.min.apply(Math, c) + Math.max.apply(Math, c)) / 2
            }
        },
        getVelocity: function (a, b, c) {
            return {
                x: Math.abs(b / a) || 0,
                y: Math.abs(c / a) || 0
            }
        },
        getAngle: function (a, b) {
            var c = b.pageY - a.pageY,
                d = b.pageX - a.pageX;
            return 180 * Math.atan2(c, d) / Math.PI
        },
        getDirection: function (a, b) {
            var c = Math.abs(a.pageX - b.pageX),
                e = Math.abs(a.pageY - b.pageY);
            return c >= e ? a.pageX - b.pageX > 0 ? d.DIRECTION_LEFT : d.DIRECTION_RIGHT : a.pageY - b.pageY > 0 ? d.DIRECTION_UP : d.DIRECTION_DOWN
        },
        getDistance: function (a, b) {
            var c = b.pageX - a.pageX,
                d = b.pageY - a.pageY;
            return Math.sqrt(c * c + d * d)
        },
        getScale: function (a, b) {
            return a.length >= 2 && b.length >= 2 ? this.getDistance(b[0], b[1]) / this.getDistance(a[0], a[1]) : 1
        },
        getRotation: function (a, b) {
            return a.length >= 2 && b.length >= 2 ? this.getAngle(b[1], b[0]) - this.getAngle(a[1], a[0]) : 0
        },
        isVertical: function (a) {
            return a == d.DIRECTION_UP || a == d.DIRECTION_DOWN
        },
        stopDefaultBrowserBehavior: function (a, b) {
            var c, d = ["webkit", "khtml", "moz", "Moz", "ms", "o", ""];
            if (b && a.style) {
                for (var e = 0; e < d.length; e++)
                    for (var f in b) b.hasOwnProperty(f) && (c = f, d[e] && (c = d[e] + c.substring(0, 1).toUpperCase() + c.substring(1)), a.style[c] = b[f]);
                "none" == b.userSelect && (a.onselectstart = function () {
                    return !1
                }), "none" == b.userDrag && (a.ondragstart = function () {
                    return !1
                })
            }
        }
    }, d.detection = {
        gestures: [],
        current: null,
        previous: null,
        stopped: !1,
        startDetect: function (a, b) {
            this.current || (this.stopped = !1, this.current = {
                inst: a,
                startEvent: d.utils.extend({}, b),
                lastEvent: !1,
                name: ""
            }, this.detect(b))
        },
        detect: function (a) {
            if (this.current && !this.stopped) {
                a = this.extendEventData(a);
                for (var b = this.current.inst.options, c = 0, e = this.gestures.length; e > c; c++) {
                    var f = this.gestures[c];
                    if (!this.stopped && b[f.name] !== !1 && f.handler.call(f, a, this.current.inst) === !1) {
                        this.stopDetect();
                        break
                    }
                }
                return this.current && (this.current.lastEvent = a), a.eventType == d.EVENT_END && !a.touches.length - 1 && this.stopDetect(), a
            }
        },
        stopDetect: function () {
            this.previous = d.utils.extend({}, this.current), this.current = null, this.stopped = !0
        },
        extendEventData: function (a) {
            var b = this.current.startEvent;
            if (b && (a.touches.length != b.touches.length || a.touches === b.touches)) {
                b.touches = [];
                for (var c = 0, e = a.touches.length; e > c; c++) b.touches.push(d.utils.extend({}, a.touches[c]))
            }
            var f = a.timeStamp - b.timeStamp,
                g = a.center.pageX - b.center.pageX,
                h = a.center.pageY - b.center.pageY,
                i = d.utils.getVelocity(f, g, h);
            return d.utils.extend(a, {
                deltaTime: f,
                deltaX: g,
                deltaY: h,
                velocityX: i.x,
                velocityY: i.y,
                distance: d.utils.getDistance(b.center, a.center),
                angle: d.utils.getAngle(b.center, a.center),
                interimAngle: this.current.lastEvent && d.utils.getAngle(this.current.lastEvent.center, a.center),
                direction: d.utils.getDirection(b.center, a.center),
                interimDirection: this.current.lastEvent && d.utils.getDirection(this.current.lastEvent.center, a.center),
                scale: d.utils.getScale(b.touches, a.touches),
                rotation: d.utils.getRotation(b.touches, a.touches),
                startEvent: b
            }), a
        },
        register: function (a) {
            var c = a.defaults || {};
            return c[a.name] === b && (c[a.name] = !0), d.utils.extend(d.defaults, c, !0), a.index = a.index || 1e3, this.gestures.push(a), this.gestures.sort(function (a, b) {
                return a.index < b.index ? -1 : a.index > b.index ? 1 : 0
            }), this.gestures
        }
    }, d.gestures = d.gestures || {}, d.gestures.Hold = {
        name: "hold",
        index: 10,
        defaults: {
            hold_timeout: 500,
            hold_threshold: 1
        },
        timer: null,
        handler: function (a, b) {
            switch (a.eventType) {
            case d.EVENT_START:
                clearTimeout(this.timer), d.detection.current.name = this.name, this.timer = setTimeout(function () {
                    "hold" == d.detection.current.name && b.trigger("hold", a)
                }, b.options.hold_timeout);
                break;
            case d.EVENT_MOVE:
                a.distance > b.options.hold_threshold && clearTimeout(this.timer);
                break;
            case d.EVENT_END:
                clearTimeout(this.timer)
            }
        }
    }, d.gestures.Tap = {
        name: "tap",
        index: 100,
        defaults: {
            tap_max_touchtime: 250,
            tap_max_distance: 10,
            tap_always: !0,
            doubletap_distance: 20,
            doubletap_interval: 300
        },
        handler: function (a, b) {
            if (a.eventType == d.EVENT_END && "touchcancel" != a.srcEvent.type) {
                var c = d.detection.previous,
                    e = !1;
                if (a.deltaTime > b.options.tap_max_touchtime || a.distance > b.options.tap_max_distance) return;
                c && "tap" == c.name && a.timeStamp - c.lastEvent.timeStamp < b.options.doubletap_interval && a.distance < b.options.doubletap_distance && (b.trigger("doubletap", a), e = !0), (!e || b.options.tap_always) && (d.detection.current.name = "tap", b.trigger(d.detection.current.name, a))
            }
        }
    }, d.gestures.Swipe = {
        name: "swipe",
        index: 40,
        defaults: {
            swipe_max_touches: 1,
            swipe_velocity: .7
        },
        handler: function (a, b) {
            if (a.eventType == d.EVENT_END) {
                if (b.options.swipe_max_touches > 0 && a.touches.length > b.options.swipe_max_touches) return;
                (a.velocityX > b.options.swipe_velocity || a.velocityY > b.options.swipe_velocity) && (b.trigger(this.name, a), b.trigger(this.name + a.direction, a))
            }
        }
    }, d.gestures.Drag = {
        name: "drag",
        index: 50,
        defaults: {
            drag_min_distance: 10,
            correct_for_drag_min_distance: !0,
            drag_max_touches: 1,
            drag_block_horizontal: !1,
            drag_block_vertical: !1,
            drag_lock_to_axis: !1,
            drag_lock_min_distance: 25
        },
        triggered: !1,
        handler: function (a, b) {
            if (d.detection.current.name != this.name && this.triggered) return b.trigger(this.name + "end", a), this.triggered = !1, void 0;
            if (!(b.options.drag_max_touches > 0 && a.touches.length > b.options.drag_max_touches)) switch (a.eventType) {
            case d.EVENT_START:
                this.triggered = !1;
                break;
            case d.EVENT_MOVE:
                if (a.distance < b.options.drag_min_distance && d.detection.current.name != this.name) return;
                if (d.detection.current.name != this.name && (d.detection.current.name = this.name, b.options.correct_for_drag_min_distance)) {
                    var c = Math.abs(b.options.drag_min_distance / a.distance);
                    d.detection.current.startEvent.center.pageX += a.deltaX * c, d.detection.current.startEvent.center.pageY += a.deltaY * c, a = d.detection.extendEventData(a)
                }(d.detection.current.lastEvent.drag_locked_to_axis || b.options.drag_lock_to_axis && b.options.drag_lock_min_distance <= a.distance) && (a.drag_locked_to_axis = !0);
                var e = d.detection.current.lastEvent.direction;
                a.drag_locked_to_axis && e !== a.direction && (a.direction = d.utils.isVertical(e) ? a.deltaY < 0 ? d.DIRECTION_UP : d.DIRECTION_DOWN : a.deltaX < 0 ? d.DIRECTION_LEFT : d.DIRECTION_RIGHT), this.triggered || (b.trigger(this.name + "start", a), this.triggered = !0), b.trigger(this.name, a), b.trigger(this.name + a.direction, a), (b.options.drag_block_vertical && d.utils.isVertical(a.direction) || b.options.drag_block_horizontal && !d.utils.isVertical(a.direction)) && a.preventDefault();
                break;
            case d.EVENT_END:
                this.triggered && b.trigger(this.name + "end", a), this.triggered = !1
            }
        }
    }, d.gestures.Transform = {
        name: "transform",
        index: 45,
        defaults: {
            transform_min_scale: .01,
            transform_min_rotation: 1,
            transform_always_block: !1
        },
        triggered: !1,
        handler: function (a, b) {
            if (d.detection.current.name != this.name && this.triggered) return b.trigger(this.name + "end", a), this.triggered = !1, void 0;
            if (!(a.touches.length < 2)) switch (b.options.transform_always_block && a.preventDefault(), a.eventType) {
            case d.EVENT_START:
                this.triggered = !1;
                break;
            case d.EVENT_MOVE:
                var c = Math.abs(1 - a.scale),
                    e = Math.abs(a.rotation);
                if (c < b.options.transform_min_scale && e < b.options.transform_min_rotation) return;
                d.detection.current.name = this.name, this.triggered || (b.trigger(this.name + "start", a), this.triggered = !0), b.trigger(this.name, a), e > b.options.transform_min_rotation && b.trigger("rotate", a), c > b.options.transform_min_scale && (b.trigger("pinch", a), b.trigger("pinch" + (a.scale < 1 ? "in" : "out"), a));
                break;
            case d.EVENT_END:
                this.triggered && b.trigger(this.name + "end", a), this.triggered = !1
            }
        }
    }, d.gestures.Touch = {
        name: "touch",
        index: -1 / 0,
        defaults: {
            prevent_default: !1,
            prevent_mouseevents: !1
        },
        handler: function (a, b) {
            return b.options.prevent_mouseevents && a.pointerType == d.POINTER_MOUSE ? (a.stopDetect(), void 0) : (b.options.prevent_default && a.preventDefault(), a.eventType == d.EVENT_START && b.trigger(this.name, a), void 0)
        }
    }, d.gestures.Release = {
        name: "release",
        index: 1 / 0,
        handler: function (a, b) {
            a.eventType == d.EVENT_END && b.trigger(this.name, a)
        }
    }, "function" == typeof define && "object" == typeof define.amd && define.amd ? define(function () {
        return d
    }) : "object" == typeof module && "object" == typeof module.exports ? module.exports = d : a.Hammer = d
}(this),
function (a) {
    "use strict";
    var b = function (b, c) {
        return c === a ? b : (b.event.bindDom = function (b, d, e) {
            c(b).on(d, function (b) {
                var c = b.originalEvent || b;
                c.pageX === a && (c.pageX = b.pageX, c.pageY = b.pageY), c.target || (c.target = b.target), c.which === a && (c.which = c.button), c.preventDefault || (c.preventDefault = b.preventDefault), c.stopPropagation || (c.stopPropagation = b.stopPropagation), e.call(this, c)
            })
        }, b.Instance.prototype.on = function (a, b) {
            return c(this.element).on(a, b)
        }, b.Instance.prototype.off = function (a, b) {
            return c(this.element).off(a, b)
        }, b.Instance.prototype.trigger = function (a, b) {
            var d = c(this.element);
            return d.has(b.target).length && (d = c(b.target)), d.trigger({
                type: a,
                gesture: b
            })
        }, c.fn.hammer = function (a) {
            return this.each(function () {
                var d = c(this),
                    e = d.data("hammer");
                e ? e && a && b.utils.extend(e.options, a) : d.data("hammer", new b(this, a || {}))
            })
        }, b)
    };
    "function" == typeof define && "object" == typeof define.amd && define.amd ? define("hammer-jquery", ["hammer", "jquery"], b) : b(window.Hammer, window.jQuery || window.Zepto)
}();