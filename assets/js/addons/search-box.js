( function( $, elementor ) {
	'use strict';

	var kitify_search = kitify_search || {};
    kitify_search.init = function () {
		this.focusSearchField();
		this.clickCategorySearch();
		this.clickSearchAdaptive();
		this.instanceSearch();
    };
    /**
	 * Product instance search
	 */
    kitify_search.instanceSearch = function () {

		var $modal = $('.kitify-search-box');

		var xhr = null,
			searchCache = {},
			$form = $modal.find('form');

		var $settings = $modal.data('settings');

		if ($settings['header_ajax_search'] != '1') {
			return;
		}

		$modal.on('keyup', '.kitify-search-box__field, .search-modal__field', function (e) {
			var valid = false,
			$search = $(this);

			if (typeof e.which == 'undefined') {
				valid = true;
			} else if (typeof e.which == 'number' && e.which > 0) {
				valid = !e.ctrlKey && !e.metaKey && !e.altKey;
			}

			if (!valid) {
				return;
			}

			if (xhr) {
				xhr.abort();
			}

			var $categoryWidth 	= $('.kitify-search-box__categories-label').is(":visible") ? $('.kitify-search-box__categories-label').outerWidth(true) : 0,
				$dividerWidth 	= $('.kitify-search-box__divider').is(":visible") ? $('.kitify-search-box__divider').outerWidth(true) : 0,
				$spacing 		= $categoryWidth + $dividerWidth + 10;

				if ( $('.kitify-search-box__container > div:first-child').hasClass('kitify-search-box__categories-label') ) {
					$spacing = 10;
				}

			if ($('body').hasClass('rtl')) {
				$modal.find('.close-search-results').css('left', $spacing);
			} else {
				$modal.find('.close-search-results').css('right', $spacing);
			}

			$modal.find('.kitify-search-box__trending').removeClass('kitify-search-box__trending--open');

			$modal.find('.result-list-found, .result-list-not-found').html('');

			var $currentForm = $search.closest('.kitify-search-box__form, .search-modal__form');

			if ($search.val().length < 2) {
				$currentForm.removeClass('searching searched actived found-products found-no-product invalid-length');
			}

			search($currentForm);
		}).on('click', '.kitify-search-box__categories-container a', function () {
			if (xhr) {
				xhr.abort();
			}

			$modal.find('.result-list-found').html('');
			var $currentForm = $(this).closest('.kitify-search-box__form');

			search($currentForm);
		}).on('focusout', '.kitify-search-box__field, .search-modal__field', function () {
			var $search = $(this),
				$currentForm = $search .closest('.kitify-search-box__form, .search-modal__form');

			if ($search.val().length < 2) {
				$currentForm.removeClass('searching searched actived found-products found-no-product invalid-length');
			}
		});

		$modal.on('click', '.close-search-results', function (e) {
			e.preventDefault();
			$modal.find('.kitify-search-box__field, .search-modal__field').val('');
			$modal.find('.kitify-search-box__form, .search-modal__form').removeClass('searching searched actived found-products found-no-product invalid-length');

			$modal.find('.result-list-found').html('');
		});

		/**
		 * Private function for search
		 */
		function search($currentForm) {
			var $search = $currentForm.find('input.kitify-search-box__field, input.search-modal__field'),
				keyword = $search.val(),
				cat = 0,
				$results = $currentForm.find('.search-results');

			if ($currentForm.find('input.category-name').length > 0) {
				cat = $currentForm.find('input.category-name').val();
			}

			if (keyword.trim().length < 2) {
				$currentForm.removeClass('searching found-products found-no-product').addClass('invalid-length');
				return;
			}

			$currentForm.removeClass('found-products found-no-product').addClass('searching');

			var keycat = keyword + cat,
				url = $form.attr('action') + '?' + $form.serialize();

			if (keycat in searchCache) {
				var result = searchCache[keycat];

				$currentForm.removeClass('searching');
				$currentForm.addClass('found-products');
				$results.html(result.products);


				$(document.body).trigger('kitify_ajax_search_request_success', [$results]);

				$currentForm.removeClass('invalid-length');
				$currentForm.addClass('searched actived');
			} else {
				var data = {
						'term': keyword,
						'cat': cat,
						'ajax_search_number': $settings['header_search_number'],
						'search_type': $currentForm.find('input.kitify-search-box__post-type, input.search-modal__post-type').val()
					},
					ajax_url = $settings['ajax_url'].toString().replace('%%endpoint%%', 'kitify_instance_search_form');

				xhr = $.post(
					ajax_url,
					data,
					function (response) {
						var $products = response.data;

						$currentForm.removeClass('searching');
						$currentForm.addClass('found-products');
						$results.html($products);
						$currentForm.removeClass('invalid-length');

						$(document.body).trigger('kitify_ajax_search_request_success', [$results]);

						// Cache
						searchCache[keycat] = {
							found: true,
							products: $products
						};

						$results.find('.view-more a').attr('href', url);

						$currentForm.addClass('searched actived');

					}
				);
			}
		}

		$( '.site-header .kitify-search-box__field' ).on( 'input', function() {
			var value = $(this).val();

			$( '.site-header .kitify-search-box__field' ).val(value);
		} );
	}
    /**
	 * Open trending post when focus on search field
	 */
    kitify_search.focusSearchField = function() {
		$( '.kitify-search-box .kitify-search-box__field' ).on( 'focus', function() {
			var $field = $( this );
			var $trendingSearches = $field.closest( '.kitify-search-box' ).find( '.kitify-search-box__trending--outside' );

			$field.closest('.kitify-search-box__form').addClass( 'kitify-search-box__form--focused' );

			if ( ! $field.closest('.kitify-search-box__form').hasClass( 'searched' ) ) {
				$trendingSearches.addClass( 'kitify-search-box__trending--open' );
			}
			$field.addClass( 'kitify-search-box--focused' );

			$field.closest('.kitify-search-box__form').find('.kitify-search-box__results').removeClass( 'hidden' );

			$( window ).one( 'scroll', function() {
				$field.trigger('blur');
			} );
		} );

		$( document.body ).on( 'click', '.kitify-search-box__trending-label, .kitify-search-box__categories-label', function() {
			$( '.kitify-search-box__trending--outside' ).removeClass( 'kitify-search-box__trending--open' );
			$('.kitify-search-box__form').removeClass( 'kitify-search-box__form--focused' );
		}).on( 'click', 'div', function( event ) {
			var $target = $( event.target );

			if ( $target.is( '.kitify-search-box' ) || $target.closest( '.kitify-search-box' ).length || $target.closest( '.search-modal__form' ).length ) {
				return;
			}

			$( '.kitify-search-box__trending--outside' ).removeClass( 'kitify-search-box__trending--open' );
			$( '.kitify-search-box' ).removeClass( 'kitify-search-box--focused' );
			$('.kitify-search-box__form').removeClass( 'kitify-search-box__form--focused' );

			$( '.kitify-search-box').find('.kitify-search-box__results').addClass( 'hidden' );
		} );

		var width = $( '.kitify-search-box--form' ).data('width');

		if ( width ) {
			$( window ).on('resize', function () {
				if ($( window ).width() > 1300) {
					$( '.kitify-search-box--form' ).css('max-width', width);
				} else {
					$( '.kitify-search-box--form' ).removeAttr("style");
				}

			}).trigger('resize');
		}
	};

	/**
	 * Open category list
	 */
	kitify_search.clickCategorySearch = function() {
		var $modal = $('.kitify-search-box');
		var $settings = $modal.data('settings');

		if ( ! $settings['header_search_type'] ) {
			return;
		}

		if ( $settings['header_search_type'] == 'adaptive' && $settings['header_search_type'] == 'post' ) {
			return;
		}

		$( '.kitify-search-box__categories-label' ).on( 'click', function() {
			$( this ).closest('.kitify-search-box__form').find('.kitify-search-box__categories').addClass( 'kitify-search-box__categories--open' );
			$( this ).closest('.kitify-search-box__form').addClass( 'categories--open' );
		});

		$( document.body ).on( 'click', '.kitify-search-box__categories-close', function() {
			$(this).closest('.kitify-search-box__categories').removeClass('kitify-search-box__categories--open');
			$('.kitify-search-box__form').removeClass( 'categories--open' );
		}).on( 'click', 'div', function( event ) {
			var $target = $( event.target );

			if ( $target.is( '.kitify-search-box' ) || $target.closest( '.kitify-search-box' ).length ) {
				return;
			}

			$( '.kitify-search-box__categories' ).removeClass('kitify-search-box__categories--open');
			$( '.kitify-search-box__form' ).removeClass( 'categories--open' );
		} );

		$( '.kitify-search-box__categories a' ).on( 'click', function(e) {
			e.preventDefault();

			$( '.kitify-search-box__categories a' ).removeClass('active');
			$(this).addClass('active');

			var cat = $(this).attr('data-slug'),
				text = $(this).text();

			$(this).closest('.kitify-search-box__form').find('input.category-name').val(cat);
			$(this).closest('.kitify-search-box__categories').removeClass('kitify-search-box__categories--open');
			$('.kitify-search-box__form').removeClass( 'categories--open' );
			$('.kitify-search-box__form').find('.kitify-search-box__categories-label').find('.kitify-search-box__categories-text').text(text);

			var $categoryWidth = $('.kitify-search-box__categories-label').is(":visible") ? $('.kitify-search-box__categories-label').outerWidth(true) : 0,
				$dividerWidth = $('.kitify-search-box__divider').is(":visible") ? $('.kitify-search-box__divider').outerWidth(true) : 0;

			if (motta.$body.hasClass('rtl')) {
				$(this).closest('.kitify-search-box__form').find('.close-search-results').css('left', $categoryWidth + $dividerWidth + 10);
			} else {
				$(this).closest('.kitify-search-box__form').find('.close-search-results').css('right', $categoryWidth + $dividerWidth + 10);
			}
		});

		$(window).on( 'load', function() {
			var cat = $('.kitify-search-box__form').find('input.category-name').val();

			if( cat ) {
				var item = $('.kitify-search-box__categories').find('a[data-slug="'+cat+'"]');
				$( '.kitify-search-box__categories a' ).removeClass('active');
				item.addClass('active');
			}
		});
	};

	/**
	 * Open search adaptive
	 */
	kitify_search.clickSearchAdaptive = function() {
		$( '.kitify-search-box--icon' ).on( 'click', '.kitify-search-box__icon', function() {
			$( this ).closest('.kitify-search-box--icon').addClass( 'kitify-search-box--icon-open' );
		});

		$( document.body ).on( 'click', 'div', function( event ) {
			var $target = $( event.target );

			if ( $target.is( '.kitify-search-box--icon' ) || $target.closest( '.kitify-search-box--icon' ).length ) {
				return;
			}

			$( '.kitify-search-box--icon' ).removeClass( 'kitify-search-box--icon-open' );
		} );
	};
    $( window ).on( 'elementor/frontend/init', function (){
        elementor.hooks.addAction( 'frontend/element_ready/kitify-search-box.default', function ( $scope ){

            if(elementor.isEditMode()){
                return;
            }
            kitify_search.init();
        } );
    } );

}( jQuery, window.elementorFrontend ) );