/*global jQuery, _ */
(function (exports, $) {

    $(function (){
        $( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on( 'change', function() {

                var checkbox_values = $( this ).closest( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                    function() {
                        return this.value;
                    }
                ).get().join( ',' );

                $( this ).closest( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
            }
        );
    })


})(wp, jQuery);
