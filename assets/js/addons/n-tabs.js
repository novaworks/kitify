( function( $, elementorFrontend ) {

    "use strict";

    class NestedTabs extends elementorModules.frontend.handlers.Base {
        getDefaultSettings() {
            return {
                isInPopup: !!this.$element.closest('.elementor-location-popup').length,
                active_class: 'e-active',
                selectors: {
                    tabs: '.kitify-ntabs',
                    control: '.kitify-ntabs-heading',
                    controlItem: '.kitify-ntab-title:not(.clone--item)',
                    content: '.kitify-ntabs-content',
                    contentItem: '>.elementor-element',
                    cControlItem: '>.kitify-ntab-title',
                },
            };
        }

        _debounce(callback, delay = 100){
            let timer;
            return evt => {
                if(timer) clearTimeout(timer);
                timer = setTimeout( callback, delay, evt);
            }
        }

        getDefaultElements() {
            const selectors = this.getSettings( 'selectors' );
            const elements = {
                $tabs: this.$element.find( selectors.tabs ).first(),
                $control: this.$element.find( selectors.control ).first(),
                $content: this.$element.find( selectors.content ).first()
            }
            elements.$selectBoxWrap = $('.ntabs-selectbox--wrap', elements.$control);
            elements.$selectBoxControl = $('.ntabs-selectbox--label', elements.$selectBoxWrap);
            elements.$controlItem = $(selectors.controlItem, elements.$control);
            elements.$cControlItem = $(selectors.cControlItem, elements.$content);
            elements.$contentItem = $(selectors.contentItem, elements.$content);
            return elements;
        }

        bindEvents() {
            this.elements.$controlItem.on( 'click', this.onControlItemClick.bind( this ) );
            this.elements.$cControlItem.on( 'click', this.onCollapseControlItemClick.bind( this ) );
            this.elements.$controlItem.first().trigger('click', [ true ]);

            this.elements.$selectBoxControl.on( 'click', this.onSelectBoxControlClick.bind( this ) );

            this.onCanChangeToSelectBox();
            window.addEventListener('resize', this._debounce( () => this.onCanChangeToSelectBox() ) )
            document.addEventListener( 'click', this.onSelectBoxClose.bind(this) )
        }

        onCollapseControlItemClick( evt ) {
            evt.preventDefault();
            let cIndex = evt.currentTarget.getAttribute('data-tabindex') - 1;
            this.elements.$controlItem.eq(cIndex).trigger('click');
        }

        onControlItemClick( evt, isAutoTrigger ) {
            evt.preventDefault();

            const tab_as_selectbox = this.getElementSettings('tab_as_selectbox');
            let active_class = this.getSettings('active_class');
            let $currentItem = $(evt.currentTarget);
            let cIndex = $currentItem.data('tabindex') - 1;

            if(tab_as_selectbox === 'yes'){
                this.elements.$selectBoxWrap.removeClass('e-open')
            }

            if( this.elements.$tabs.hasClass('e-active-selectbox') ){
                this.elements.$control.toggleClass('e-open');
            }

            if($currentItem.hasClass(active_class)){
                return;
            }
            if(!this.getSettings('isInPopup') && !isAutoTrigger){

                let _offset = this.elements.$tabs.offset().top - 100 - parseInt(document.documentElement.style.getPropertyValue('--kitify-header-height') || 0);
                if(elementorFrontend.elements.$wpAdminBar.length > 0){
                    _offset -= elementorFrontend.elements.$wpAdminBar.height()
                }

                $('html,body').animate({
                    scrollTop: _offset
                }, 300);
            }

            const _callback = ( idx, item ) => {
                if(idx !== cIndex){
                    $(item).removeClass(active_class)
                }
                else{
                    $(item).addClass(active_class)
                }
            }
            this.elements.$controlItem.each( _callback )
            this.elements.$cControlItem.each( _callback )
            this.elements.$contentItem.each( _callback );

            let $activeContent = this.elements.$contentItem.eq( cIndex );

            if($('.slick-slider', $activeContent).length > 0){
                try{ $('.slick-slider', $activeContent).slick('setPosition') }
                catch (e) { }
            }
            if($('.swiper-container', $activeContent).length > 0){
                try{ $('.swiper-container', $activeContent).data('swiper').resize.resizeHandler() }
                catch (e) { }
            }
            $('.kitify-masonry-wrapper', $activeContent).trigger('resize');

            if(tab_as_selectbox === 'yes'){
                let cloneItem = $currentItem.clone();
                cloneItem.removeAttr('id');
                cloneItem.addClass('clone--item');
                $('.ntabs-selectbox--label .kitify-ntab-title', this.elements.$control).replaceWith(cloneItem);
            }

            $(document).trigger('lastudio-kit/active-tabs', [ $activeContent ]);
        }

        onSelectBoxControlClick( evt ) {
            evt.preventDefault();
            this.elements.$selectBoxWrap.toggleClass('e-open');
        }

        onSelectBoxClose( evt ){
            if( !$(evt.target).closest(this.elements.$selectBoxWrap).length ){
                this.elements.$selectBoxWrap.removeClass('e-open')
            }
        }

        onElementChange(propertyName) {

        }

        onCanChangeToSelectBox() {
            let breakpoint_selector = this.getElementSettings('breakpoint_selector');
            let sticky_breakpoint = this.getElementSettings('sticky_breakpoint');
            if(breakpoint_selector && breakpoint_selector !== 'none'){
                let maxWidth = elementorFrontend.breakpoints.responsiveConfig.breakpoints[breakpoint_selector].value + 1;
                if( window.innerWidth < maxWidth){
                    this.elements.$tabs.addClass('e-active-selectbox');
                }
                else{
                    this.elements.$tabs.removeClass('e-active-selectbox');
                }
            }
            if(sticky_breakpoint && sticky_breakpoint !== 'none'){
                if(sticky_breakpoint === 'all'){
                    this.elements.$control.addClass('e--sticky');
                }
                else{
                    let maxWidth = elementorFrontend.breakpoints.responsiveConfig.breakpoints[sticky_breakpoint].value + 1;
                    if( window.innerWidth < maxWidth){
                        this.elements.$control.addClass('e--sticky');
                    }
                    else{
                        this.elements.$control.removeClass('e--sticky');
                    }
                }
            }
        }
    }

    $( window ).on( 'elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/kitify-nested-tabs.default', ( $element ) => {
            elementorFrontend.elementsHandler.addHandler( NestedTabs, { $element } );
        } );
    } );

}( jQuery, window.elementorFrontend ) );
