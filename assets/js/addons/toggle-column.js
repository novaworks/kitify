(function( $ ) {
    var $window = $(window);
    var $column_container = $('.kitify-toggle-column .elementor-element-populated');

    $window.on('elementor/frontend/init', function() {
      $column_container.each( function(){
        $(this).append( '<a class="nova-sidebar__toggle js-column-toogle" href="javascript:void(0)"></a>' );
      });
      $(document).on('click', '.js-column-toogle', function() {
        $('.kitify-toggle-column').toggleClass('opened');
      });
    });

}( jQuery ));
