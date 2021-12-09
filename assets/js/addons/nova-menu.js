( function( $, elementor ) {

    "use strict";

    $( window ).on( 'elementor/frontend/init', function (){
        elementor.hooks.addAction( 'frontend/element_ready/kitify-nova-menu.default', function ( $scope ){
            if ( $scope.data( 'initialized' ) ) {
                return;
            }

            $scope.data( 'initialized', true );

            var mobileActiveClass = 'kitify-mobile-menu-active',
                _has_mobile_bkp   = false;

            if($scope.find('.kitify-nova-mobile-menu').length){
                _has_mobile_bkp = $scope.find('.kitify-nova-mobile-menu').data('mobile-breakpoint');
            }

            function checkActiveMobileTrigger(){
                if(_has_mobile_bkp !== false && $(window).width() <= _has_mobile_bkp){
                    $scope.find('.kitify-nova-mobile-menu').addClass('kitify-active--mbmenu');
                }
                else{
                    $scope.find('.kitify-nova-mobile-menu').removeClass('kitify-active--mbmenu');
                }
            }
            checkActiveMobileTrigger();
            $(window).on('resize', checkActiveMobileTrigger);

        } );
    } );

}( jQuery, window.elementorFrontend ) );
