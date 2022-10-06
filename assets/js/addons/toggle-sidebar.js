( function( $, elementor ) {

    $( window ).on( 'elementor/frontend/init', function (){
      elementor.hooks.addAction( 'frontend/element_ready/kitify-sidebar.default', function ( $scope ){
        if ( $scope.data( 'initialized' ) ) {
            return;
        }
        $scope.data( 'initialized', true );
        var _has_mobile_bkp   = false;
        if($scope.find('.kitify-toggle-sidebar').length){
            _has_mobile_bkp = $scope.find('.kitify-toggle-sidebar').data('breakpoint');
        }
        function checkActiveToggle(){
            if(_has_mobile_bkp !== false && $(window).width() <= _has_mobile_bkp){
                $scope.find('.kitify-toggle-sidebar').addClass('kitify-active-sidebar-toggle');
            }
            else{
                $scope.find('.kitify-toggle-sidebar').removeClass('kitify-active-sidebar-toggle');
            }
        }
        checkActiveToggle();
        $(window).on('resize', checkActiveToggle);
        $(document).on('click', '.js-column-toggle', function() {
          $('body').toggleClass('toogle-opened');
          $('.kitify-toggle-sidebar').toggleClass('opened');
        });
      });
      elementor.hooks.addAction( 'frontend/element_ready/kitify-wooproducts.default', function ( $scope ){
        _has_btn_bkp = false;
        if($scope.find('.nova-product-filter').length){
            _has_btn_bkp = $scope.find('.nova-product-filter').data('breakpoint');
        }
        function checkActiveToggle(){
            if(_has_btn_bkp !== false && $(window).width() <= _has_btn_bkp){
                $scope.find('.nova-product-filter').addClass('kitify-active-sidebar-toggle');
            }
            else{
                $scope.find('.nova-product-filter').removeClass('kitify-active-sidebar-toggle');
            }
        }
        checkActiveToggle();
        $(window).on('resize', checkActiveToggle);
      });

    });

}( jQuery, window.elementorFrontend ) );
