( function( $, elementor ) {

    "use strict";

    $( window ).on( 'elementor/frontend/init', function (){
        elementor.hooks.addAction( 'frontend/element_ready/kitify-nav-menu.default', function ( $scope ){
            if ( $scope.data( 'initialized' ) ) {
                return;
            }

            $scope.data( 'initialized', true );

            var hoverClass        = 'kitify-nav-hover',
                hoverOutClass     = 'kitify-nav-hover-out',
                mobileActiveClass = 'kitify-mobile-menu-active',
                _has_mobile_bkp   = false;

            if($scope.find('.kitify-mobile-menu').length){
                _has_mobile_bkp = $scope.find('.kitify-mobile-menu').data('mobile-breakpoint');
            }

            function checkActiveMobileTrigger(){
                if(_has_mobile_bkp !== false && $(window).width() <= _has_mobile_bkp){
                    $scope.find('.kitify-mobile-menu').addClass('kitify-active--mbmenu');
                }
                else{
                    $scope.find('.kitify-mobile-menu').removeClass('kitify-active--mbmenu');
                }
            }
            checkActiveMobileTrigger();
            $(window).on('resize', checkActiveMobileTrigger);

            $scope.find( '.kitify-nav:not(.kitify-nav--vertical-sub-bottom)' ).hoverIntent({
                over: function() {
                    $( this ).addClass( hoverClass );
                },
                out: function() {
                    var $this = $( this );
                    $this.removeClass( hoverClass );
                    $this.addClass( hoverOutClass );
                    setTimeout( function() {
                        $this.removeClass( hoverOutClass );
                    }, 200 );
                },
                timeout: 200,
                selector: '.menu-item-has-children'
            });

            if ( Kitify.mobileAndTabletCheck() ) {
                $scope.find( '.kitify-nav:not(.kitify-nav--vertical-sub-bottom)' ).on( 'touchstart.kitifyNavMenu', '.menu-item > a', touchStartItem );
                $scope.find( '.kitify-nav:not(.kitify-nav--vertical-sub-bottom)' ).on( 'touchend.kitifyNavMenu', '.menu-item > a', touchEndItem );

                $( document ).on( 'touchstart.kitifyNavMenu', prepareHideSubMenus );
                $( document ).on( 'touchend.kitifyNavMenu', hideSubMenus );
            } else {
                $scope.find( '.kitify-nav:not(.kitify-nav--vertical-sub-bottom)' ).on( 'click.kitifyNavMenu', '.menu-item > a', clickItem );
            }

            if ( ! Kitify.isEditMode() ) {
                initMenuAnchorsHandler();
            }

            function touchStartItem( event ) {
                var $currentTarget = $( event.currentTarget ),
                    $this = $currentTarget.closest( '.menu-item' );

                $this.data( 'offset', $( window ).scrollTop() );
                $this.data( 'elemOffset', $this.offset().top );
            }

            function touchEndItem( event ) {
                var $this,
                    $siblingsItems,
                    $link,
                    $currentTarget,
                    subMenu,
                    offset,
                    elemOffset,
                    $hamburgerPanel;

                event.preventDefault();

                $currentTarget  = $( event.currentTarget );
                $this           = $currentTarget.closest( '.menu-item' );
                $siblingsItems  = $this.siblings( '.menu-item.menu-item-has-children' );
                $link           = $( '> a', $this );
                subMenu         = $( '.kitify-nav__sub:first', $this );
                offset          = $this.data( 'offset' );
                elemOffset      = $this.data( 'elemOffset' );
                $hamburgerPanel = $this.closest( '.kitify-hamburger-panel' );

                if ( offset !== $( window ).scrollTop() || elemOffset !== $this.offset().top ) {
                    return false;
                }

                if ( $siblingsItems[0] ) {
                    $siblingsItems.removeClass( hoverClass );
                    $( '.menu-item-has-children', $siblingsItems ).removeClass( hoverClass );
                }

                if ( ! $( '.kitify-nav__sub', $this )[0] || $this.hasClass( hoverClass ) ) {
                    $link.trigger( 'click' ); // Need for a smooth scroll when clicking on an anchor link
                    window.location.href = $link.attr( 'href' );

                    if ( $scope.find( '.kitify-nav-wrap' ).hasClass( mobileActiveClass ) ) {
                        $scope.find( '.kitify-nav-wrap' ).removeClass( mobileActiveClass );
                    }

                    if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
                        $hamburgerPanel.removeClass( 'open-state' );
                        $( 'html' ).removeClass( 'kitify-hamburger-panel-visible' );
                    }

                    return false;
                }

                if ( subMenu[0] ) {
                    $this.addClass( hoverClass );
                }
            }

            function clickItem( event ) {
                var $currentTarget  = $( event.currentTarget ),
                    $menuItem       = $currentTarget.closest( '.menu-item' ),
                    $hamburgerPanel = $menuItem.closest( '.kitify-hamburger-panel' );

                if ( ! $menuItem.hasClass( 'menu-item-has-children' ) || $menuItem.hasClass( hoverClass ) ) {

                    if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
                        $hamburgerPanel.removeClass( 'open-state' );
                        $( 'html' ).removeClass( 'kitify-hamburger-panel-visible' );
                    }

                }
            }

            var scrollOffset;

            function prepareHideSubMenus( event ) {
                scrollOffset = $( window ).scrollTop();
            }

            function hideSubMenus( event ) {
                var $menu = $scope.find( '.kitify-nav' );

                if ( 'touchend' === event.type && scrollOffset !== $( window ).scrollTop() ) {
                    return;
                }

                if ( $( event.target ).closest( $menu ).length ) {
                    return;
                }

                var $openMenuItems = $( '.menu-item-has-children.' + hoverClass, $menu );

                if ( ! $openMenuItems[0] ) {
                    return;
                }

                $openMenuItems.removeClass( hoverClass );
                $openMenuItems.addClass( hoverOutClass );

                setTimeout( function() {
                    $openMenuItems.removeClass( hoverOutClass );
                }, 200 );

                if ( $menu.hasClass( 'kitify-nav--vertical-sub-bottom' ) ) {
                    $( '.kitify-nav__sub', $openMenuItems ).slideUp( 200 );
                }

                event.stopPropagation();
            }

            // START Vertical Layout: Sub-menu at the bottom
            $scope.find( '.kitify-nav--vertical-sub-bottom' ).on( 'click.kitifyNavMenu', '.menu-item > a', verticalSubBottomHandler );

            function verticalSubBottomHandler( event ) {
                var $currentTarget  = $( event.currentTarget ),
                    $menuItem       = $currentTarget.closest( '.menu-item' ),
                    $siblingsItems  = $menuItem.siblings( '.menu-item.menu-item-has-children' ),
                    $subMenu        = $( '.kitify-nav__sub:first', $menuItem ),
                    $hamburgerPanel = $menuItem.closest( '.kitify-hamburger-panel' );

                if ( ! $menuItem.hasClass( 'menu-item-has-children' ) || $menuItem.hasClass( hoverClass ) ) {

                    if ( $scope.find( '.kitify-nav-wrap' ).hasClass( mobileActiveClass ) ) {
                        $scope.find( '.kitify-nav-wrap' ).removeClass( mobileActiveClass );
                    }

                    if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
                        $hamburgerPanel.removeClass( 'open-state' );
                        $( 'html' ).removeClass( 'kitify-hamburger-panel-visible' );
                    }

                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                if ( $siblingsItems[0] ) {
                    $siblingsItems.removeClass( hoverClass );
                    $( '.menu-item-has-children', $siblingsItems ).removeClass( hoverClass );
                    $( '.kitify-nav__sub', $siblingsItems ).slideUp( 200 );
                }

                if ( $subMenu[0] ) {
                    $subMenu.slideDown( 200 );
                    $menuItem.addClass( hoverClass );
                }
            }

            $( document ).on( 'click.kitifyNavMenu', hideVerticalSubBottomMenus );

            function hideVerticalSubBottomMenus( event ) {
                if ( ! $scope.find( '.kitify-nav' ).hasClass( 'kitify-nav--vertical-sub-bottom' ) ) {
                    return;
                }

                hideSubMenus( event );
            }
            // END Vertical Layout: Sub-menu at the bottom

            // Mobile trigger click event
            $( '.kitify-nav__mobile-trigger', $scope ).on( 'click.kitifyNavMenu', function( event ) {
                $( this ).closest( '.kitify-nav-wrap' ).toggleClass( mobileActiveClass );
            } );

            // START Mobile Layout: Left-side, Right-side
            if ( 'ontouchend' in window ) {
                $( document ).on( 'touchend.kitifyMobileNavMenu', removeMobileActiveClass );
            } else {
                $( document ).on( 'click.kitifyMobileNavMenu', removeMobileActiveClass );
            }

            function removeMobileActiveClass( event ) {
                var mobileLayout = $scope.find( '.kitify-nav-wrap' ).data( 'mobile-layout' ),
                    $navWrap     = $scope.find( '.kitify-nav-wrap' ),
                    $trigger     = $scope.find( '.kitify-nav__mobile-trigger' ),
                    $menu        = $scope.find( '.kitify-nav' );

                if ( 'left-side' !== mobileLayout && 'right-side' !== mobileLayout ) {
                    return;
                }

                if ( 'touchend' === event.type && scrollOffset !== $( window ).scrollTop() ) {
                    return;
                }

                if ( $( event.target ).closest( $trigger ).length || $( event.target ).closest( $menu ).length ) {
                    return;
                }

                if ( ! $navWrap.hasClass( mobileActiveClass ) ) {
                    return;
                }

                $navWrap.removeClass( mobileActiveClass );

                event.stopPropagation();
            }

            $( '.kitify-nav__mobile-close-btn', $scope ).on( 'click.kitifyMobileNavMenu', function( event ) {
                $( this ).closest( '.kitify-nav-wrap' ).removeClass( mobileActiveClass );
            } );

            // END Mobile Layout: Left-side, Right-side

            // START Mobile Layout: Full-width
            var initMobileFullWidthCss = false;

            setFullWidthMenuPosition();
            $( window ).on( 'resize.kitifyMobileNavMenu', setFullWidthMenuPosition );

            function setFullWidthMenuPosition() {
                var mobileLayout = $scope.find( '.kitify-nav-wrap' ).data( 'mobile-layout' );

                if ( 'full-width' !== mobileLayout ) {
                    return;
                }

                var $menu = $scope.find( '.kitify-nav' ),
                    currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

                if ( 'mobile' !== currentDeviceMode ) {
                    if ( initMobileFullWidthCss ) {
                        $menu.css( { 'left': '' } );
                        initMobileFullWidthCss = false;
                    }
                    return;
                }

                if ( initMobileFullWidthCss ) {
                    $menu.css( { 'left': '' } );
                }

                var offset = - $menu.offset().left;

                $menu.css( { 'left': offset } );
                initMobileFullWidthCss = true;
            }
            // END Mobile Layout: Full-width

            // Menu Anchors Handler
            function initMenuAnchorsHandler() {
                var $anchorLinks = $scope.find( '.menu-item-link[href*="#"]' );

                if ( $anchorLinks[0] ) {
                    $anchorLinks.each( function() {
                        if ( '' !== this.hash && location.pathname === this.pathname ) {
                            menuAnchorHandler( $( this ) );
                        }
                    } );
                }
            }

            function menuAnchorHandler( $anchorLink ) {
                var anchorHash = $anchorLink[0].hash,
                    activeClass = 'current-menu-item',
                    rootMargin = '-50% 0% -50%',
                    $anchor;

                try {
                    $anchor = $( decodeURIComponent( anchorHash ) );
                } catch (e) {
                    return;
                }

                if ( !$anchor[0] ) {
                    return;
                }

                if ( $anchor.hasClass( 'elementor-menu-anchor' ) ) {
                    rootMargin = '300px 0% -300px';
                }

                var observer = new IntersectionObserver( function( entries ) {
                        if ( entries[0].isIntersecting ) {
                            $anchorLink.parent( '.menu-item' ).addClass( activeClass );
                        } else {
                            $anchorLink.parent( '.menu-item' ).removeClass( activeClass );
                        }
                    },
                    {
                        rootMargin: rootMargin
                    }
                );

                observer.observe( $anchor[0] );
            }

            if ( Kitify.isEditMode() ) {
                $scope.data( 'initialized', false );
            }
        } );
    } );

}( jQuery, window.elementorFrontend ) );