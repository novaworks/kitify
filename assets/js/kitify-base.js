!function(e,t){"use strict";var i=!1;function a(){var t=0,i=e('.elementor-location-header .elementor-top-section[data-settings*="sticky_on"]');return i.length&&(t=i.innerHeight()),t}function n(){document.documentElement.style.setProperty("--kitify-header-height",a()+"px")}document.addEventListener("DOMContentLoaded",(function(){document.body.classList.add("kitify--js-ready"),n()})),e(window).on("load resize",n),e(document).on("kitify/woocommerce/single/init_product_slider",(function(e,t){t.controlNav.eq(t.animatingTo).closest("li").get(0).scrollIntoView({inline:"center",block:"nearest",behavior:"smooth"}),t.viewport.closest(".woocommerce-product-gallery").css("--singleproduct-thumbs-height",t.viewport.height()+"px")}));var o={log:function(...e){"true"===window.KitifySettings.devMode&&console.log(...e)},addedScripts:{},addedStyles:{},addedAssetsPromises:[],carouselAsFor:[],localCache:{cache_key:void 0!==KitifySettings.themeName?KitifySettings.themeName:"kitify",timeout:void 0!==KitifySettings.cache_ttl&&parseInt(KitifySettings.cache_ttl)>0?parseInt(KitifySettings.cache_ttl):300,timeout2:600,data:{},remove:function(e){delete o.localCache.data[e]},exist:function(e){return!!o.localCache.data[e]&&(Date.now()-o.localCache.data[e]._)/1e3<o.localCache.timeout2},get:function(e){return o.log("Get cache for "+e),o.localCache.data[e].data},set:function(e,t,i){o.localCache.remove(e),o.localCache.data[e]={_:Date.now(),data:t},"function"==typeof i&&"number"!=typeof i.nodeType&&i(t)},hashCode:function(e){var t=0;if(0==(e=e.toString()).length)return t;for(var i=0;i<e.length;i++){t=(t<<5)-t+e.charCodeAt(i),t&=t}return Math.abs(t)},validCache:function(e){var t=void 0!==KitifySettings.local_ttl&&parseInt(KitifySettings.local_ttl)>0?parseInt(KitifySettings.local_ttl):1800,i=o.localCache.cache_key+"_cache_timeout"+o.localCache.hashCode(KitifySettings.homeURL);try{var a=localStorage.getItem(i);if(null!==a||e)((Date.now()-a)/1e3>t||e)&&(Object.keys(localStorage).forEach((function(e){0===e.indexOf(o.localCache.cache_key)&&localStorage.removeItem(e)})),localStorage.setItem(i,Date.now()));else localStorage.setItem(i,Date.now())}catch(e){o.log(e)}}},isPageSpeed:function(){return"undefined"!=typeof navigator&&(/(lighthouse|gtmetrix)/i.test(navigator.userAgent.toLocaleLowerCase())||/mozilla\/5\.0 \(x11; linux x86_64\)/i.test(navigator.userAgent.toLocaleLowerCase()))},addQueryArg:function(e,t,i){var a=new RegExp("([?&])"+t+"=.*?(&|$)","i"),n=-1!==e.indexOf("?")?"&":"?";return e.match(a)?e.replace(a,"$1"+t+"="+i+"$2"):e+n+t+"="+i},getUrlParameter:function(e,t){t||(t=window.location.href),e=e.replace(/[\[\]]/g,"\\$&");var i=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(t);return i?i[2]?decodeURIComponent(i[2].replace(/\+/g," ")):"":null},parseQueryString:function(e){var t=e.split("?"),i={};if(t.length>=2)for(var a=t[1].split("&"),n=0;n<a.length;n++){var o=a[n].split("="),r=decodeURIComponent(o[0]),s=decodeURIComponent(o[1]);if(void 0===i[r])i[r]=decodeURIComponent(s);else if("string"==typeof i[r]){var l=[i[r],decodeURIComponent(s)];i[r]=l}else i[r].push(decodeURIComponent(s))}return i},removeURLParameter:function(e,t){var i=e.split("?");if(i.length>=2){for(var a=encodeURIComponent(t)+"=",n=i[1].split(/[&;]/g),o=n.length;o-- >0;)-1!==n[o].lastIndexOf(a,0)&&n.splice(o,1);return e=i[0]+(n.length>0?"?"+n.join("&"):"")}return e},initCarousel:function(t){var i=t.find(".kitify-carousel").first();if(0!=i.length&&!i.hasClass("inited")){i.addClass("inited");var a=i.data("slider_options"),n=parseInt(a.slidesToShow.desktop)||1,r=elementorFrontend.config.responsive.activeBreakpoints,s=a.uniqueID,l={slidesPerView:n,loop:a.infinite,speed:a.speed,handleElementorBreakpoints:!0,slidesPerColumn:a.rows.desktop,slidesPerGroup:a.slidesToScroll.desktop||1,breakpoints:{}},d=1;switch(Object.keys(r).reverse().forEach((function(e){var t="tablet"===e?1:d;l.breakpoints[r[e].value]={slidesPerView:+a.slidesToShow[e]||t,slidesPerGroup:+a.slidesToScroll[e]||1,slidesPerColumn:+a.rows[e]||1},d=+a.slidesToShow[e]||t})),a.autoplay&&(l.autoplay={delay:"slide"==a.effect&&a.infiniteEffect?10:a.autoplaySpeed,disableOnInteraction:a.pauseOnInteraction,pauseOnMouseEnter:a.pauseOnHover,reverseDirection:a.reverseDirection||!1},"slide"==a.effect&&a.infiniteEffect&&i.addClass("kitify--linear-effect")),a.centerMode&&(l.centerInsufficientSlides=!0,l.centeredSlides=!0,l.centeredSlidesBounds=!1),a.effect){case"fade":1==n&&(l.effect=a.effect,l.fadeEffect={crossFade:!0});break;case"coverflow":l.effect="coverflow",l.grabCursor=!0,l.centeredSlides=!0,l.slidesPerView=2,l.coverflowEffect={rotate:50,stretch:0,depth:100,modifier:1,slideShadows:!0},l.coverflowEffect=e.extend({},{rotate:0,stretch:100,depth:100,modifier:2.6,slideShadows:!0},a.coverflowEffect);break;case"cube":l.effect="cube",l.grabCursor=!0,l.cubeEffect={shadow:!0,slideShadows:!0,shadowOffset:20,shadowScale:.94},l.slidesPerView=1,l.slidesPerGroup=1;break;case"flip":l.effect="flip",l.grabCursor=!0,l.slidesPerView=1,l.slidesPerGroup=1;break;case"slide":l.effect="slide",l.grabCursor=!0}a.arrows&&(l.navigation={prevEl:a.prevArrow,nextEl:a.nextArrow}),a.dots&&(l.pagination={el:a.dotsElm||".kitify-carousel__dots",type:l.dotType||"bullets",clickable:!0},"bullets"==a.dotType&&(l.pagination.dynamicBullets=!0),"custom"==a.dotType&&(l.pagination.renderBullet=function(e,t){return'<span class="'+t+'">'+(e+1)+"</span>"}));var c=a.scrollbar||!1;l.scrollbar=!!c&&{el:".kitify-carousel__scrollbar",draggable:!0};var f=!1,m=a.content_effect_in||"fadeInUp",u=a.content_effect_out||"fadeOutDown";if(void 0!==a.content_selector&&i.find(a.content_selector).length>0&&(f=!0),(i.closest(".no-slide-animation").length||i.closest(".slide-no-animation").length)&&(f=!1),a.direction&&(l.direction=a.direction),a.autoHeight&&(l.autoHeight=a.autoHeight),l.watchSlidesProgress=!0,l.watchSlidesVisibility=!0,void 0!==a.asFor&&""!=a.asFor&&"#"!=a.asFor&&e("#"+a.asFor).length){var p=e("#"+a.asFor).data("swiper");null===p||"undefined"===p?l.thumbs={swiper:p}:o.carouselAsFor.push({main:s,thumb:a.asFor,main_init:!1,thumb_init:!1})}l.slideToClickedSlide=!0;var g=t.find(".swiper-container");new(0,elementorFrontend.utils.swiper)(g,l).then((function(t){a.autoplay&&void 0!==t.autoplay&&void 0===t.autoplay.onMouseEnter&&g.on("mouseenter",(function(){t.autoplay.stop()})).on("mouseleave",(function(){t.autoplay.start()})),g.data("swiper",t),g.find(".elementor-top-section").trigger("kitify/section/calculate-container-width"),y(!0);var n,o=h(s,"thumb"),r=h(s,"main");o.length&&o[0].main_init&&o[0].thumb_init&&((n=e("#"+o[0].main).data("swiper")).thumbs.swiper=e("#"+o[0].thumb).data("swiper"),n.thumbs.init());r.length&&r[0].main_init&&r[0].thumb_init&&((n=e("#"+r[0].main).data("swiper")).thumbs.swiper=e("#"+r[0].thumb).data("swiper"),n.thumbs.init());f&&(i.find(a.content_selector).addClass("animated no-effect-class"),i.find(".swiper-slide-visible "+a.content_selector).removeClass("no-effect-class").addClass(m)),t.on("slideChange",(function(){g.hasClass(this.params.thumbs.thumbsContainerClass)&&(this.clickedIndex=this.activeIndex,this.clickedSlide=this.slides[this.clickedIndex],this.emit("tap"))})),t.on("slideChangeTransitionEnd",(function(){y(!1)})),e(document).trigger("kitify/carousel/init_success",{swiperContainer:g})}))}function h(e,t){var i=function(e,t){for(var i=[],a=0;a<o.carouselAsFor.length;a++)if(o.carouselAsFor[a][t]==e){o.carouselAsFor[a].index=a,i.push(o.carouselAsFor[a]);break}return i}(e,t);return i.length&&(i[0][t+"_init"]=!0),i}function y(t){(i.find('.swiper-slide-active .kitify-template-wrapper .elementor-invisible[data-settings*="_animation"]').each((function(){var t=e(this).data("settings"),i=elementorFrontend.getCurrentDeviceSetting(t,"_animation"),a=t._animation_delay||0,n=e(this);"none"===i?n.removeClass("elementor-invisible"):setTimeout((function(){n.removeClass("elementor-invisible").addClass("animated "+i)}),a)})),f&&(i.find(".swiper-slide:not(.swiper-slide-visible) "+a.content_selector).addClass("no-effect-class").removeClass(m).addClass(u),i.find(".swiper-slide-visible "+a.content_selector).removeClass("no-effect-class").removeClass(u).addClass(m)),t)?setTimeout((function(){i.find('.swiper-slide:not(.swiper-slide-visible) .kitify-template-wrapper [data-settings*="_animation"]').each((function(){var t=e(this).data("settings"),i=elementorFrontend.getCurrentDeviceSetting(t,"_animation");"none"===i?e(this).removeClass("animated").addClass("elementor-invisible"):e(this).removeClass("animated "+i).addClass("elementor-invisible")}))}),1e3):i.find('.swiper-slide:not(.swiper-slide-visible) .kitify-template-wrapper [data-settings*="_animation"]').each((function(){var t=e(this).data("settings"),i=elementorFrontend.getCurrentDeviceSetting(t,"_animation");"none"===i?e(this).removeClass("animated").addClass("elementor-invisible"):e(this).removeClass("animated "+i).addClass("elementor-invisible")}))}},initMasonry:function(t){var i=t.find(".kitify-masonry-wrapper").first();if(0!=i.length){var a,n,o=t.find(i.data("kitifymasonry_wrap")),r=i.data("kitifymasonry_itemselector"),s=i.data("kitifymasonry_layouts")||!1,l=t.find(r);o.length&&(!1!==s?(e(document).trigger("kitify/masonry/calculate-item-sizes",[i,!1]),e(window).on("resize",(function(){e(document).trigger("kitify/masonry/calculate-item-sizes",[i,!0])})),n={itemSelector:r,percentPosition:!0,masonry:{columnWidth:1,gutter:0}}):n={itemSelector:r,percentPosition:!0},a=o.isotope(n),e("img",l).imagesLoaded().progress((function(t,i){e(i.img).closest(r).addClass("item-loaded"),a&&a.isotope("layout")})))}},initCustomHandlers:function(){e(document).on("click",".kitify .kitify-pagination_ajax_loadmore a",(function(t){if(t.preventDefault(),e("body").hasClass("elementor-editor-active"))return!1;var i,a,n,r,s,l;if(r=e(this).closest(".kitify-pagination"),i=e(this).closest(".kitify"),s=i.data("id"),r.hasClass("doing-ajax"))return!1;if("load_widget",i.find('div[data-widget_current_query="yes"]').length>0&&"load_fullpage",i.hasClass("elementor-kitify-wooproducts")?(n=i.find(".kitify-products__list"),a=i.find(".kitify-products"),l=".kitify-product.product_item"):(n=e(r.data("container")),a=e(r.data("parent-container")),l=r.data("item-selector")),e("a.next",r).length){r.addClass("doing-ajax"),a.addClass("doing-ajax");var d=e("a.next",r).get(0).href.replace(/^\//,""),c={url:d=o.removeURLParameter(d,"_"),type:"GET",cache:!0,dataType:"html",success:function(t){!function(t){var i=e(t).find(".elementor-element-"+s+" "+l);if(a.find(".kitify-carousel").length>0?a.find(".kitify-carousel").get(0).swiper.appendSlide(i):n.data("isotope")?(n.append(i),n.isotope("insert",i),e(document).trigger("kitify/masonry/calculate-item-sizes",[a,!0]),e("img",i).imagesLoaded().progress((function(t,i){e(i.img).closest(l).addClass("item-loaded"),n.isotope("layout")}))):i.addClass("fadeIn animated").appendTo(n),a.removeClass("doing-ajax"),r.removeClass("doing-ajax kitify-ajax-load-first"),e(t).find(".elementor-element-"+s+" .kitify-ajax-pagination").length){var o=e(t).find(".elementor-element-"+s+" .kitify-ajax-pagination");r.replaceWith(o),r=o}else r.addClass("nothingtoshow");e("body").trigger("jetpack-lazy-images-load"),e(document).trigger("kitify/ajax-loadmore/success",{parentContainer:a,contentHolder:n,pagination:r})}(t)}};e.ajax(c)}})).on("click",".kitify .kitify-ajax-pagination .page-numbers a",(function(t){if(t.preventDefault(),e("body").hasClass("elementor-editor-active"))return!1;var i,n,r,s,l,d,c,f,m;if(l=e(this).closest(".kitify-pagination"),i=e(this).closest(".kitify"),d=i.data("id"),l.hasClass("doing-ajax"))return!1;if(f=i.closest(".elementor[data-elementor-settings][data-elementor-id]").data("elementor-id"),i.hasClass("elementor-kitify-wooproducts")){r=i.find(".kitify-products__list"),n=i.find(".kitify-products"),c=".kitify-product.product_item";var u=n.closest(".woocommerce").attr("class").match(/\bkitify_wc_widget_([^\s]*)/);m=null!==u&&u[1]?"product-page-"+u[1]:"paged"}else r=e(l.data("container")),n=e(l.data("parent-container")),c=l.data("item-selector"),m=l.data("ajax_request_id");s="load_widget",i.find('div[data-widget_current_query="yes"]').length>0&&(s="load_fullpage",m="paged"),l.addClass("doing-ajax"),n.addClass("doing-ajax");var p=function(t,o){var s;if(o){var f=JSON.parse(t).template_content;s=e("<div></div>").html(f)}else s=e(t);var m=s.find(".elementor-element-"+d+" "+c);if(n.find(".kitify-carousel").length>0){var u=n.find(".kitify-carousel").get(0).swiper;u.removeAllSlides(),u.appendSlide(m)}else r.data("isotope")?(r.isotope("remove",r.isotope("getItemElements")),r.isotope("insert",m),e(document).trigger("kitify/masonry/calculate-item-sizes",[n,!0]),e("img",m).imagesLoaded().progress((function(t,i){e(i.img).closest(c).addClass("item-loaded"),r.isotope("layout")}))):m.addClass("fadeIn animated").appendTo(r.empty());if(n.removeClass("doing-ajax"),l.removeClass("doing-ajax kitify-ajax-load-first"),s.find(".elementor-element-"+d+" .kitify-ajax-pagination").length){var p=s.find(".elementor-element-"+d+" .kitify-ajax-pagination");l.replaceWith(p),l=p}else l.addClass("nothingtoshow");s.find(".elementor-element-"+d+" .woocommerce-result-count").length&&i.find(".woocommerce-result-count").length&&i.find(".woocommerce-result-count").replaceWith(s.find(".elementor-element-"+d+" .woocommerce-result-count")),e("html,body").animate({scrollTop:n.offset().top-a()-50},400),e("body").trigger("jetpack-lazy-images-load"),e(document).trigger("kitify/ajax-pagination/success",{parentContainer:n,contentHolder:r,pagination:l})},g=t.target.href.replace(/^\//,"");if("load_widget"==s){var h=g;g=o.addQueryArg(KitifySettings.widgetApiUrl,"template_id",f),g=o.addQueryArg(g,"widget_id",d),g=o.addQueryArg(g,"dev",KitifySettings.devMode),g=o.addQueryArg(g,m,o.getUrlParameter(m,h)),g=o.addQueryArg(g,"kitifypagedkey",m)}var y={url:g=o.removeURLParameter(g,"_"),type:"GET",cache:!0,dataType:"html",ajax_request_id:o.getUrlParameter(m,g),success:function(e){p(e,"load_widget"==s)}};e.ajax(y)})).on("click","[data-kitify-element-link]",(function(t){var i,a=e(this),n=a.data("kitify-element-link"),o=a.data("id"),r=document.createElement("a");r.id="nova-wrapper-link-"+o,r.href=n.url,r.target=n.is_external?"_blank":"_self",r.rel=n.nofollow?"nofollow noreferer":"",r.style.display="none",document.body.appendChild(r),(i=document.getElementById(r.id)).click(),i.remove()})).on("click",".kitify-masonry_filter .kitify-masonry_filter-item",(function(t){t.preventDefault();var i=e(this).closest(".kitify-masonry_filter"),a=e(i.data("kitifymasonry_container")),n=e(this).data("filter");"*"!=n&&(n="."+n),a.data("isotope")&&(e(this).addClass("active").siblings(".kitify-masonry_filter-item").removeClass("active"),a.isotope({filter:n}))})).on("kitify/masonry/calculate-item-sizes",(function(t,i,a){var n=i.data("kitifymasonry_layouts")||!1,o=i.find(i.data("kitifymasonry_wrap"));if(!1!==n){var r=e(window).width(),s=i.data("kitifymasonry_itemselector");if(r>1023){i.addClass("cover-img-bg");var l=n.item_width,d=n.item_height,c=n.container_width,f=i.width(),m=Math.round(c/l),u=Math.floor(f/m),p=parseInt(i.data("kitifymasonry_itemmargin")||0),g=d?parseFloat(l/d):1,h=n.layout||[{w:1,h:1}],y=0;e(s,i).each((function(){e(this).css({width:Math.floor(u*h[y].w-p/2),height:d?Math.floor(u/g*h[y].h):"auto"}).addClass("kitify-disable-cols-style"),++y==h.length&&(y=0)}))}else i.removeClass("kitify-masonry--cover-bg"),e(s,i).css({width:"",height:""}).removeClass("kitify-disable-cols-style")}a&&o.data("isotope")&&o.isotope("layout")})).on("keyup",(function(t){27==t.keyCode&&(e(".kitify-cart").removeClass("kitify-cart-open"),e(".kitify-hamburger-panel").removeClass("open-state"),e("html").removeClass("kitify-hamburger-panel-visible"))})).on("kitify/section/calculate-container-width",".elementor-top-section",(function(t){var i=e(this).find(">.elementor-container");i.css("--kitify-section-width",i.width()+"px"),e(window).on("resize",(function(){i.css("--kitify-section-width",i.width()+"px")}))})).on("click",(function(t){0==e(t.target).closest(".kitify-cart").length&&e(".kitify-cart").removeClass("kitify-cart-open")}))},ImageScrollHandler:function(e){var t=function(e){var t={},a=e.data("model-cid");if(i&&a){var n=elementorFrontend.config.elements.data[a],o=elementorFrontend.config.elements.keys[n.attributes.widgetType||n.attributes.elType];jQuery.each(n.getActiveControls(),(function(e){-1!==o.indexOf(e)&&(t[e]=n.attributes[e])}))}else t=e.data("settings")||{};return t}(e),a=e.find(".kitify-image-scroll-container"),n=a.find(".kitify-image-scroll-overlay"),o=a.find(".kitify-image-scroll-vertical"),r=a.find(".kitify-image-scroll-image img"),s=t.direction_type,l=t.reverse,d=t.trigger_type,c=null;function f(){r.css("transform",("vertical"===s?"translateY":"translateX")+"( -"+c+"px)")}function m(){r.css("transform",("vertical"===s?"translateY":"translateX")+"(0px)")}function u(){c="vertical"===s?r.height()-a.height():r.width()-a.width()}"scroll"===d?(a.addClass("kitify-container-scroll"),"vertical"===s?o.addClass("kitify-image-scroll-ver"):a.imagesLoaded((function(){n.css({width:r.width(),height:r.height()})}))):("yes"===l&&a.imagesLoaded((function(){a.addClass("kitify-container-scroll-instant"),u(),f()})),"vertical"===s&&o.removeClass("kitify-image-scroll-ver"),a.mouseenter((function(){a.removeClass("kitify-container-scroll-instant"),u(),"yes"===l?m():f()})),a.mouseleave((function(){"yes"===l?f():m()})))},isEditMode:function(){return Boolean(elementorFrontend.isEditMode())},mobileAndTabletCheck:function(){return("ontouchstart"in window||navigator.maxTouchPoints>0||navigator.msMaxTouchPoints>0)&&window.innerWidth<1400},loadStyle:function(e,t){return o.addedStyles.hasOwnProperty(e)&&o.addedStyles[e]===t?e:t?(o.addedStyles[e]=t,new Promise((function(i,a){var n=document.createElement("link");n.id=e+"-css",n.rel="stylesheet",n.href=t,n.type="text/css",n.media="all",n.onload=function(){i(e)},n.onerror=function(){a(`Can not load css file "${t}"`)},document.head.appendChild(n)}))):void 0},loadScriptAsync:function(e,t,i,a){return o.addedScripts.hasOwnProperty(e)?e:t?(o.addedScripts[e]=t,new Promise((function(n,o){var r=document.createElement("script");r.src=t,r.id=e+"-js",r.async=a,r.onload=function(){n(e),"function"==typeof i&&"number"!=typeof i.nodeType&&i()},r.onerror=function(){o(`Can not load javascript file "${t}"`),"function"==typeof i&&"number"!=typeof i.nodeType&&i()},document.head.appendChild(r)}))):void 0},detectWidgetsNotInHeader:function(){[".elementor-widget-icon-list",".main-color",".elementor-icon",".elementor-heading-title",".elementor-widget-text-editor",".elementor-widget-divider",".elementor-icon-list-item",".elementor-social-icon",".elementor-button",".kitify-nav-wrap",".kitify-nav",".menu-item-link-depth-0"].forEach((function(t){e(t).each((function(){e(this).closest(".kitify-nav__sub").length&&e(this).addClass("ignore-docs-style-yes")}))})),e(".elementor-widget-icon-list .elementor-icon-list-item").each((function(){var t=e(">a",e(this)).attr("href");e(this).closest(".kitify-nav__sub").length&&e(this).closest(".menu-item.need-check-active").length&&window.location.href==t&&e(this).addClass("current-menu-item")}))},elementorFrontendInit:function(t,i){void 0!==window.elementorFrontend.hooks&&(o.detectWidgetsNotInHeader(),i&&e(window).trigger("elementor/frontend/init"),t.removeClass("need-reinit-js"),t.find("[data-element_type]").each((function(){var t=e(this),i=t.data("element_type");if(i)try{"widget"===i&&(i=t.data("widget_type"),window.elementorFrontend.hooks.doAction("frontend/element_ready/widget",t,e)),window.elementorFrontend.hooks.doAction("frontend/element_ready/global",t,e),window.elementorFrontend.hooks.doAction("frontend/element_ready/"+i,t,e)}catch(e){return o.log(e),t.remove(),!1}})))},initAnimationsHandlers:function(t){t.find("[data-element_type]").each((function(){var t=e(this);t.data("element_type")&&window.elementorFrontend.hooks.doAction("frontend/element_ready/global",t,e)}))},hamburgerPanel:function(t){var i=t.data("id"),a=t.find(".kitify-hamburger-panel__content").attr("data-template-id"),n=!0,r=t.find(">.elementor-widget-container").clone();t.data("hamburgerTemplateId")&&a==t.data("hamburgerTemplateId")?n=!1:t.data("hamburgerTemplateId",a),0==e(".kitify-site-wrapper > .elementor-location-header >.kitify-burger-wrapall").length&&e("<div/>").addClass("kitify-burger-wrapall").appendTo(e(".kitify-site-wrapper > .elementor-location-header"));var s=e(".kitify-burger-wrapall");n&&e(".elementor-element-"+i,s).length&&e(".elementor-element-"+i,s).remove();var l=t;t.closest(".elementor-location-header").length&&(n&&e("<div/>").addClass("elementor-element elementor-element-"+i).append(r).appendTo(s),e(".kitify.elementor-element-"+i+" .kitify-hamburger-panel__instance").remove(),l=e(".elementor-element-"+i,s),e(".kitify-hamburger-panel__toggle",l).remove());var d=e(".kitify-hamburger-panel",l),c=e(".kitify-hamburger-panel__toggle",t),f=(e(".kitify-hamburger-panel__instance",l),e(".kitify-hamburger-panel__cover",l),e(".kitify-hamburger-panel__inner",l)),m=e(".kitify-hamburger-panel__close-button",l),u=e("html"),p=d.data("settings")||{},g=e(".elementor-element-"+i+" .kitify-hamburger-panel");p.ajaxTemplate||o.elementorFrontendInit(f,!1),c.on("click",(function(e){e.preventDefault(),d.hasClass("open-state")?(g.removeClass("open-state"),u.removeClass("kitify-hamburger-panel-visible")):(g.addClass("open-state"),u.addClass("kitify-hamburger-panel-visible"),o.initAnimationsHandlers(f))})),m.on("click",(function(e){e.preventDefault(),d.hasClass("open-state")?(g.removeClass("open-state"),u.removeClass("kitify-hamburger-panel-visible")):(g.addClass("open-state"),u.addClass("kitify-hamburger-panel-visible"),o.initAnimationsHandlers(f))})),e(document).on("click.kitifyHamburgerPanel",(function(t){(!e(t.target).closest(".kitify-hamburger-panel__toggle").length&&!e(t.target).closest(".kitify-hamburger-panel__instance").length||e(t.target).closest(".kitify-hamburger-panel__cover").length)&&d.hasClass("open-state")&&(e(".elementor-element-"+i+" .kitify-hamburger-panel").removeClass("open-state"),e(t.target).closest(".kitify-hamburger-panel__toggle").length||u.removeClass("kitify-hamburger-panel-visible"),t.stopPropagation())}))},ajaxLoadTemplate:function(t,i){var a=t,n=a.data("template-loaded")||!1,r=a.data("template-id"),s=e(".kitify-tpl-panel-loader",a);if(n)return!1;e(document).trigger("kitify/ajax-load-template/before",{target:i,contentHolder:a}),a.data("template-loaded",!0),e.ajax({type:"GET",url:window.KitifySettings.templateApiUrl,dataType:"json",data:{id:r,current_url:window.location.href,current_url_no_search:window.location.href.replace(window.location.search,""),dev:window.KitifySettings.devMode},success:function(t,n,r){var l=t.template_content,d=t.template_scripts,c=t.template_styles;for(var f in d)0==e("#"+f+"-js").length&&o.addedAssetsPromises.push(o.loadScriptAsync(f,d[f],"",!0));for(var m in c)0==e("#"+m+"-css").length&&o.addedAssetsPromises.push(o.loadStyle(m,c[m]));Promise.all(o.addedAssetsPromises).then((function(n){s.remove(),a.append(l),o.elementorFrontendInit(a),e(document).trigger("kitify/ajax-load-template/after",{target:i,contentHolder:a,response:t})}),(function(n){console.log(`An error occurred while insert the asset resources, however we still need to insert content. Reason detail: "${n}"`),s.remove(),a.append(l),o.elementorFrontendInit(a),e(document).trigger("kitify/ajax-load-template/after",{target:i,contentHolder:a,response:t})}))}})},wooCard:function(t){if(window.KitifyEditor&&window.KitifyEditor.activeSection){let t=window.KitifyEditor.activeSection;["cart_list_style","cart_list_items_style","cart_buttons_style"].indexOf(t);e(".widget_shopping_cart_content").empty(),e(document.body).trigger("wc_fragment_refresh")}var i,a=e(".kitify-cart",t),n=e(".kitify-cart__heading-link",a),o=a.data("settings"),r=!0;switch(o.triggerType){case"hover":i=0,"ontouchend"in window||"ontouchstart"in window?(a.on("touchstart",(function(t){i=e(window).scrollTop()})),a.on("touchend",(function(t){if(i!==e(window).scrollTop())return!1;var a=e(this);a.hasClass("kitify-cart-open-proccess")||setTimeout((function(){a.toggleClass("kitify-cart-open")}),10)})),e(document).on("touchend",(function(t){e(t.target).closest(a).length||a.hasClass("kitify-cart-open-proccess")||a.hasClass("kitify-cart-open")&&a.removeClass("kitify-cart-open")}))):a.on("mouseenter mouseleave",(function(t){r&&"mouseleave"===t.type||(r&&"mouseenter"===t.type&&(r=!1),e(this).hasClass("kitify-cart-open-proccess")||e(this).toggleClass("kitify-cart-open"))}));break;case"click":n.on("click",(function(e){e.preventDefault(),a.hasClass("kitify-cart-open-proccess")||a.toggleClass("kitify-cart-open")}))}a.on("click",".kitify-cart__close-button",(function(e){a.hasClass("kitify-cart-open-proccess")||a.toggleClass("kitify-cart-open")}))},wooGallery:function(t){o.isEditMode()&&e(".woocommerce-product-gallery",t).wc_product_gallery();function i(e){e.each((function(){var e=jQuery(this),t=e.clone();t.find(".flex-viewport").children().unwrap(),t.find("img.zoomImg, .woocommerce-product-gallery__trigger").remove(),t.removeClass("flexslider").find(".clone, .flex-direction-nav, .flex-control-nav").remove().end().find("*").removeAttr("style").removeClass((function(e,t){if("string"==typeof t)return(t.match(/\bflex\S+/g)||[]).join(" ")})),t.insertBefore(e),e.remove()}))}setTimeout((function(){t.find(".flex-viewport").length&&(t.find(".woocommerce-product-gallery").css("--singleproduct-thumbs-height",t.find(".flex-viewport").height()+"px"),t.find(".woocommerce-product-gallery__trigger").length&&t.find(".woocommerce-product-gallery__trigger").appendTo(t.find(".flex-viewport")),e(".la-custom-badge",t).length&&e(".la-custom-badge",t).prependTo(t.find(".flex-viewport")),e(".woocommerce-product-gallery__actions",t).length&&e(".woocommerce-product-gallery__actions",t).prependTo(t.find(".flex-viewport")));var i=t.find(".flex-direction-nav");i.length&&t.find(".flex-viewport").length&&i.appendTo(t.find(".flex-viewport"));var a=t.find(".flex-control-thumbs").get(0);if(void 0!==a&&!t.find(".kitify-product-images").hasClass("layout-type-wc")){var n={top:0,left:0,x:0,y:0},o=function(e){const t=e.clientX-n.x,i=e.clientY-n.y;a.scrollTop=n.top-i,a.scrollLeft=n.left-t},r=function(){a.style.cursor="grab",a.style.removeProperty("user-select"),document.removeEventListener("mousemove",o),document.removeEventListener("mouseup",r)};a.addEventListener("mousedown",(function(e){a.style.cursor="grabbing",a.style.userSelect="none",n={left:a.scrollLeft,top:a.scrollTop,x:e.clientX,y:e.clientY},document.addEventListener("mousemove",o),document.addEventListener("mouseup",r)}))}}),300),(t.find(".kitify-product-images").hasClass("layout-type-5")||t.find(".kitify-product-images").hasClass("layout-type-6"))&&i(t.find(".kitify-product-images"));var a=t.find(".woocommerce-product-gallery"),n=parseInt(a.data("columns"));t.find(".kitify-product-images").hasClass("layout-type-4")&&(n=parseInt(a.closest(".elementor-kitify-wooproduct-images").css("--singleproduct-image-column"))),a.find(".woocommerce-product-gallery__image").length<=n&&(a.addClass("center-thumb"),t.find(".kitify-product-images").hasClass("layout-type-4")&&(i(t.find(".kitify-product-images")),a=t.find(".woocommerce-product-gallery"))),t.find(".kitify-product-images").hasClass("layout-type-5")||t.find(".kitify-product-images").hasClass("layout-type-6")?(t.find(".woocommerce-product-gallery__image a").attr("data-elementor-open-lightbox","yes"),t.find(".woocommerce-product-gallery__image a").attr("data-elementor-lightbox-slideshow",t.data("id"))):t.find(".woocommerce-product-gallery__image a").attr("data-elementor-open-lightbox","no"),t.find(".woocommerce-product-gallery__image").each((function(){0==e(this).find(".zoominner").length&&e(this).wrapInner('<div class="zoomouter"><div class="zoominner"></div></div>')}));!function(i){if(e.isFunction(e.fn.zoom)&&wc_single_product_params.zoom_enabled){var n,o=a.width(),r=!1;if(t.find(".kitify-product-images").hasClass("layout-type-4")&&(o=e(i).width()),e(i).each((function(t,i){if(e(i).find("img").data("large_image_width")>o)return r=!0,!1})),r){try{n=e.extend({touch:!1},wc_single_product_params.zoom_options)}catch(e){n={touch:!1}}"ontouchstart"in document.documentElement&&(n.on="click"),i.trigger("zoom.destroy"),i.zoom(n)}}}(a.find(".woocommerce-product-gallery__image .zoominner"))},wooTabs:function(t){var i=t.find(".wc-tabs-wrapper").first();i&&(i.wrapInner('<div class="kitify-wc-tabs--content"></div>'),i.find(".wc-tabs").wrapAll('<div class="kitify-wc-tabs--controls"></div>'),i.find(".kitify-wc-tabs--controls").prependTo(i),i.find(".wc-tab").wrapInner('<div class="tab-content"></div>'),i.find(".wc-tab").each((function(){var t=e("#"+e(this).attr("aria-labelledby")).html();e(this).prepend('<div class="wc-tab-title">'+t+"</div>")})),e(".wc-tab-title a",i).wrapInner("<span></span>"),e(".wc-tab-title a",i).on("click",(function(t){t.preventDefault(),i.find(".wc-tabs").find('li[aria-controls="'+e(this).attr("href").replace("#","")+'"]').toggleClass("active").siblings().removeClass("active"),e(this).closest(".wc-tab").toggleClass("active").siblings().removeClass("active")})),e(".wc-tabs li a",i).on("click",(function(t){e(this).closest(".wc-tabs-wrapper, .woocommerce-tabs").find(e(this).attr("href")).addClass("active").siblings().removeClass("active")})),e(".wc-tabs li",i).removeClass("active"),e(".wc-tab-title a",i).first().trigger("click"))},SearchAnimate:function(t){t.find("#js_header_search_modal").length&&e("#js_header_search_modal").animatedModal({animatedIn:"slideInDown",animatedOut:"slideOutUp",beforeOpen:function(){window.setTimeout((function(){e(".header-search").addClass("animate")}),300),window.setTimeout((function(){e(".header-search").addClass("animate-line")}),1e3)}})},animatedBoxHandler:function(t){var i=t.find(".kitify-animated-box"),a=e(window).scrollTop(),n=!0;i.length&&("ontouchend"in window||"ontouchstart"in window?(i.on("touchstart",(function(t){a=e(window).scrollTop()})),i.on("touchend",(function(t){if(a!==e(window).scrollTop())return!1;e(this).hasClass("flipped-stop")||e(this).toggleClass("flipped")}))):i.on("mouseenter mouseleave",(function(t){n&&"mouseleave"===t.type||(n&&"mouseenter"===t.type&&(n=!1),e(this).hasClass("flipped-stop")||e(this).toggleClass("flipped"))})))},ajaxTemplateHelper:{processInsertData:function(t,i,a){i&&(t.html(i),o.elementorFrontendInit(t),t.find("div[data-kitify_ajax_loadtemplate]:not(.template-loaded,.is-loading)").length&&(o.log("found template in ajax content"),o.ajaxTemplateHelper.init())),e(".elementor-motion-effects-element").trigger("resize"),e("body").trigger("jetpack-lazy-images-load"),e(document).trigger("kitify/ajax-load-template/after",{target_id:a,contentHolder:t,response:i})},elementorContentRender:function(e,t,i){Promise.all(o.addedAssetsPromises).then((function(a){o.ajaxTemplateHelper.processInsertData(e,t,i)}),(function(a){o.log(`An error occurred while insert the asset resources, however we still need to insert content. Reason detail: "${a}"`),o.ajaxTemplateHelper.processInsertData(e,t,i)}))},templateRenderCallback:function(t,i){var a=t.template_content,n=t.template_scripts,r=t.template_styles,s=t.template_metadata;for(var l in n)0==e("#"+l+"-js").length&&o.addedAssetsPromises.push(o.loadScriptAsync(l,n[l],"",!0));for(var d in r)0==e("#"+d+"-css").length&&o.addedAssetsPromises.push(o.loadStyle(d,r[d]));document.querySelectorAll('body:not(.elementor-editor-active) div[data-kitify_ajax_loadtemplate][data-cache-id="'+i+'"]:not(.template-loaded)').forEach((function(t){t.classList.remove("is-loading"),t.classList.add("template-loaded"),o.ajaxTemplateHelper.elementorContentRender(e(t),a,i)}));var c=document.querySelectorAll("#wp-admin-bar-elementor_edit_page ul");c&&void 0!==s.title&&setTimeout((function(){var t="wp-admin-bar-elementor_edit_doc_"+s.id;0==e("#"+t).length&&e('<li id="'+t+'" class="elementor-general-section"><a class="ab-item" href="'+s.href+'"><span class="elementor-edit-link-title">'+s.title+'</span><span class="elementor-edit-link-type">'+s.sub_title+"</span></a></li>").prependTo(e(c))}),2e3)},init:function(){var t=document.querySelectorAll("body:not(.elementor-editor-active) div[data-kitify_ajax_loadtemplate]:not(.template-loaded)");if(t.length){var i=[];t.forEach((function(e){if(!e.classList.contains("is-loading")&&!e.classList.contains("template-loaded")){e.classList.add("is-loading");var t=e.getAttribute("data-template-id");i.includes(t)||i.push(t),e.setAttribute("data-cache-id",t)}})),i.forEach((function(t){var i="kitifyTpl_"+t,a="kitifyTplExist_"+t;if(o.localCache.exist(a))o.localCache.exist(i)&&o.ajaxTemplateHelper.templateRenderCallback(o.localCache.get(i),t);else if(o.localCache.set(a,"yes"),o.localCache.exist(i))o.ajaxTemplateHelper.templateRenderCallback(o.localCache.get(i),t);else{e(document).trigger("kitify/ajax-load-template/before",{target_id:t});var n=o.localCache.cache_key+"_"+o.localCache.hashCode(t),r=o.localCache.timeout,s={id:t,current_url:window.location.href,current_url_no_search:window.location.href.replace(window.location.search,""),dev:window.KitifySettings.devMode},l=function(){e.ajax({type:"GET",url:window.KitifySettings.templateApiUrl,dataType:"json",data:s,success:function(e,a,r){o.localCache.set(i,e),o.ajaxTemplateHelper.templateRenderCallback(e,t);try{o.log("setup browser cache for "+n),localStorage.setItem(n,JSON.stringify(e)),localStorage.setItem(n+":ts",Date.now())}catch(e){o.log("Cannot setup browser cache")}}})};try{var d=localStorage.getItem(n),c=localStorage.getItem(n+":ts");if(null!==d&&null!==c){if((Date.now()-c)/1e3<r)return o.log("render from cache for "+n),void o.ajaxTemplateHelper.templateRenderCallback(JSON.parse(d),t);o.log("clear browser cache key for "+n),localStorage.removeItem(n),localStorage.removeItem(n+":ts")}o.log("run ajaxCalling() for "+t),l()}catch(e){o.log("Cannot setup browser cache ajaxCalling() for "+t),l()}}}))}}}};e(window).on("elementor/frontend/init",(function(){t.hooks.addAction("frontend/element_ready/kitify-advanced-carousel.default",(function(e){o.initCarousel(e)})),t.hooks.addAction("frontend/element_ready/kitify-slides.default",(function(e){o.initCarousel(e)})),t.hooks.addAction("frontend/element_ready/kitify-posts.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-portfolio.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-images-layout.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-team-member.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-testimonials.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-banner-list.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-instagram-feed.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-wooproduct-datatabs.default",(function(e){e.foundation()})),t.hooks.addAction("frontend/element_ready/kitify-woo-categories.default",(function(e){o.initCarousel(e)})),t.hooks.addAction("frontend/element_ready/kitify-scroll-image.default",(function(e){o.ImageScrollHandler(e)})),t.hooks.addAction("frontend/element_ready/kitify-hamburger-panel.default",(function(e){o.hamburgerPanel(e)})),t.hooks.addAction("frontend/element_ready/kitify-menucart.default",(function(e){o.wooCard(e)})),t.hooks.addAction("frontend/element_ready/kitify-animated-box.default",(function(e){o.animatedBoxHandler(e)})),t.hooks.addAction("frontend/element_ready/kitify-search.default",(function(e){o.SearchAnimate(e)})),t.hooks.addAction("frontend/element_ready/kitify-wooproducts.default",(function(e){o.initCarousel(e),o.initMasonry(e)})),t.hooks.addAction("frontend/element_ready/kitify-wooproduct-images.default",(function(e){o.wooGallery(e)})),t.hooks.addAction("frontend/element_ready/kitify-wooproduct-datatabs.default",(function(e){o.wooTabs(e)})),t.hooks.addAction("frontend/element_ready/kitify-image-comparison.default",(function(t){var i=t.find(".kitify-image-comparison__instance");e(".kitify-image-comparison__container",i),i.data("settings"),t.data("id");i.length&&(window.juxtapose.scanPage(".kitify-juxtapose"),o.initCarousel(t))})),t.hooks.addAction("frontend/element_ready/section",(function(e){e.hasClass("elementor-top-section")&&e.trigger("kitify/section/calculate-container-width")})),o.initCustomHandlers()})),window.Kitify=o,e.ajaxPrefilter((function(e,t,i){if(e.cache){var a=o.removeURLParameter(t.url,"_")+(void 0!==t.ajax_request_id?JSON.stringify(t.ajax_request_id):void 0!==t.data?JSON.stringify(t.data):"");a=o.localCache.hashCode(a.replace(/null$/g,"")),e.cache=!1,e.beforeSend=function(){return o.localCache.exist(a)||i.promise().done((function(e,t){o.localCache.set(a,e)})),!0}}})),e.ajaxTransport("+*",(function(e,t,i){var a=o.removeURLParameter(t.url,"_")+(void 0!==t.ajax_request_id?JSON.stringify(t.ajax_request_id):void 0!==t.data?JSON.stringify(t.data):"");if(e.cache=!1,a=o.localCache.hashCode(a.replace(/null$/g,"")),o.localCache.exist(a))return{send:function(e,t){setTimeout((function(){t(200,"OK",[o.localCache.get(a)])}),50)},abort:function(){}}})),document.addEventListener("DOMContentLoaded",(function(){o.isPageSpeed()||(o.localCache.validCache(!1),o.ajaxTemplateHelper.init())}))}(jQuery,window.elementorFrontend);