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
		// if (mottaData.header_ajax_search != '1') {
		// 	return;
		// }

		var $modal = $('.kitify-search-box');

		var xhr = null,
			searchCache = {},
			$form = $modal.find('form');

		$modal.on('keyup', '.header-search__field, .search-modal__field', function (e) {
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

			var $categoryWidth 	= $('.header-search__categories-label').is(":visible") ? $('.header-search__categories-label').outerWidth(true) : 0,
				$dividerWidth 	= $('.header-search__divider').is(":visible") ? $('.header-search__divider').outerWidth(true) : 0,
				$spacing 		= $categoryWidth + $dividerWidth + 10;

				if ( $('.header-search__container > div:first-child').hasClass('header-search__categories-label') ) {
					$spacing = 10;
				}

			if ($('body').hasClass('rtl')) {
				$modal.find('.close-search-results').css('left', $spacing);
			} else {
				$modal.find('.close-search-results').css('right', $spacing);
			}

			$modal.find('.header-search__trending').removeClass('header-search__trending--open');

			$modal.find('.result-list-found, .result-list-not-found').html('');

			var $currentForm = $search.closest('.header-search__form, .search-modal__form');

			if ($search.val().length < 2) {
				$currentForm.removeClass('searching searched actived found-products found-no-product invalid-length');
			}

			search($currentForm);
		}).on('click', '.header-search__categories-container a', function () {
			if (xhr) {
				xhr.abort();
			}

			$modal.find('.result-list-found').html('');
			var $currentForm = $(this).closest('.header-search__form');

			search($currentForm);
		}).on('focusout', '.header-search__field, .search-modal__field', function () {
			var $search = $(this),
				$currentForm = $search .closest('.header-search__form, .search-modal__form');

			if ($search.val().length < 2) {
				$currentForm.removeClass('searching searched actived found-products found-no-product invalid-length');
			}
		});

		$modal.on('click', '.close-search-results', function (e) {
			e.preventDefault();
			$modal.find('.header-search__field, .search-modal__field').val('');
			$modal.find('.header-search__form, .search-modal__form').removeClass('searching searched actived found-products found-no-product invalid-length');

			$modal.find('.result-list-found').html('');
		});

		/**
		 * Private function for search
		 */
		function search($currentForm) {
			var $search = $currentForm.find('input.header-search__field, input.search-modal__field'),
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


				$(document.body).trigger('motta_ajax_search_request_success', [$results]);

				$currentForm.removeClass('invalid-length');
				$currentForm.addClass('searched actived');
			} else {
				var data = {
						'term': keyword,
						'cat': cat,
						'ajax_search_number': mottaData.header_search_number,
						'search_type': $currentForm.find('input.header-search__post-type, input.search-modal__post-type').val()
					},
					ajax_url = mottaData.ajax_url.toString().replace('%%endpoint%%', 'motta_instance_search_form');

				xhr = $.post(
					ajax_url,
					data,
					function (response) {
						var $products = response.data;

						$currentForm.removeClass('searching');
						$currentForm.addClass('found-products');
						$results.html($products);
						$currentForm.removeClass('invalid-length');

						$(document.body).trigger('motta_ajax_search_request_success', [$results]);

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

		$( '.site-header .header-search__field' ).on( 'input', function() {
			var value = $(this).val();

			$( '.site-header .header-search__field' ).val(value);
		} );
	}
    /**
	 * Open trending post when focus on search field
	 */
    kitify_search.focusSearchField = function() {
		$( '.kitify-search-box .header-search__field' ).on( 'focus', function() {
			var $field = $( this );
			var $trendingSearches = $field.closest( '.kitify-search-box' ).find( '.header-search__trending--outside' );

			$field.closest('.header-search__form').addClass( 'header-search__form--focused' );

			if ( ! $field.closest('.header-search__form').hasClass( 'searched' ) ) {
				$trendingSearches.addClass( 'header-search__trending--open' );
			}
			$field.addClass( 'header-search--focused' );

			$field.closest('.header-search__form').find('.header-search__results').removeClass( 'hidden' );

			$( window ).one( 'scroll', function() {
				$field.trigger('blur');
			} );
		} );

		$( document.body ).on( 'click', '.header-search__trending-label, .header-search__categories-label', function() {
			$( '.header-search__trending--outside' ).removeClass( 'header-search__trending--open' );
			$('.header-search__form').removeClass( 'header-search__form--focused' );
		}).on( 'click', 'div', function( event ) {
			var $target = $( event.target );

			if ( $target.is( '.kitify-search-box' ) || $target.closest( '.kitify-search-box' ).length || $target.closest( '.search-modal__form' ).length ) {
				return;
			}

			$( '.header-search__trending--outside' ).removeClass( 'header-search__trending--open' );
			$( '.header-search' ).removeClass( 'header-search--focused' );
			$('.header-search__form').removeClass( 'header-search__form--focused' );

			$( '.header-search').find('.header-search__results').addClass( 'hidden' );
		} );

		var width = $( '.header-search--form' ).data('width');

		if ( width ) {
			$( window ).on('resize', function () {
				if ($( window ).width() > 1300) {
					$( '.header-search--form' ).css('max-width', width);
				} else {
					$( '.header-search--form' ).removeAttr("style");
				}

			}).trigger('resize');
		}
	};

	/**
	 * Open category list
	 */
	kitify_search.clickCategorySearch = function() {
		// if ( ! mottaData.header_search_type ) {
		// 	return;
		// }

		// if ( mottaData.header_search_type == 'adaptive' && mottaData.post_type == 'post' ) {
		// 	return;
		// }

		$( '.header-search__categories-label' ).on( 'click', function() {
			$( this ).closest('.header-search__form').find('.header-search__categories').addClass( 'header-search__categories--open' );
			$( this ).closest('.header-search__form').addClass( 'categories--open' );
		});

		$( document.body ).on( 'click', '.header-search__categories-close', function() {
			$(this).closest('.header-search__categories').removeClass('header-search__categories--open');
			$('.header-search__form').removeClass( 'categories--open' );
		}).on( 'click', 'div', function( event ) {
			var $target = $( event.target );

			if ( $target.is( '.kitify-search-box' ) || $target.closest( '.kitify-search-box' ).length ) {
				return;
			}

			$( '.header-search__categories' ).removeClass('header-search__categories--open');
			$( '.header-search__form' ).removeClass( 'categories--open' );
		} );

		$( '.header-search__categories a' ).on( 'click', function(e) {
			e.preventDefault();

			$( '.header-search__categories a' ).removeClass('active');
			$(this).addClass('active');

			var cat = $(this).attr('data-slug'),
				text = $(this).text();

			$(this).closest('.header-search__form').find('input.category-name').val(cat);
			$(this).closest('.header-search__categories').removeClass('header-search__categories--open');
			$('.header-search__form').removeClass( 'categories--open' );
			$('.header-search__form').find('.header-search__categories-label').find('.header-search__categories-text').text(text);

			var $categoryWidth = $('.header-search__categories-label').is(":visible") ? $('.header-search__categories-label').outerWidth(true) : 0,
				$dividerWidth = $('.header-search__divider').is(":visible") ? $('.header-search__divider').outerWidth(true) : 0;

			if (motta.$body.hasClass('rtl')) {
				$(this).closest('.header-search__form').find('.close-search-results').css('left', $categoryWidth + $dividerWidth + 10);
			} else {
				$(this).closest('.header-search__form').find('.close-search-results').css('right', $categoryWidth + $dividerWidth + 10);
			}
		});

		$(window).on( 'load', function() {
			var cat = $('.header-search__form').find('input.category-name').val();

			if( cat ) {
				var item = $('.header-search__categories').find('a[data-slug="'+cat+'"]');
				$( '.header-search__categories a' ).removeClass('active');
				item.addClass('active');
			}
		});
	};

	/**
	 * Open search adaptive
	 */
	kitify_search.clickSearchAdaptive = function() {
		$( '.header-search--icon' ).on( 'click', '.header-search__icon', function() {
			$( this ).closest('.header-search--icon').addClass( 'header-search--icon-open' );
		});

		$( document.body ).on( 'click', 'div', function( event ) {
			var $target = $( event.target );

			if ( $target.is( '.header-search--icon' ) || $target.closest( '.header-search--icon' ).length ) {
				return;
			}

			$( '.header-search--icon' ).removeClass( 'header-search--icon-open' );
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