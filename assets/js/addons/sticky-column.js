( function( $, elementor ) {
  'use strict';

  var StickyColumn = {
    init: function() {
      elementor.hooks.addAction( 'frontend/element_ready/column', StickyColumn.elementorColumn );
      //elementor.hooks.addAction( 'frontend/element_ready/container', StickyColumn.elementorContainer );
    },
    elementorColumn: function( $scope ) {
      var $target  = $scope,
        $parentSection = $scope.closest( '.elementor-section' ),
        isLegacyModeActive = !!$target.find( '> .elementor-column-wrap' ).length,
        $window  = $( window ),
        columnId = $target.data( 'id' ),
        editMode = Boolean( elementor.isEditMode() ),
        settings = {},
        stickyInstance = null,
        stickyInstanceOptions = {
          topSpacing: 50,
          bottomSpacing: 50,
          containerSelector: isLegacyModeActive ? '.elementor-row' : '.elementor-container',
          innerWrapperSelector: isLegacyModeActive ? '.elementor-column-wrap' : '.elementor-widget-wrap',
        },
        $observerTarget = $target.find('.elementor-element');
      if ( ! editMode ) {
        settings = $target.data( 'kitify-settings' );

        if ( $target.hasClass( 'kitify-sticky-column' ) ) {

          if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
            stickyInstanceOptions.topSpacing = settings['topSpacing'];
            stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

            imagesLoaded( $parentSection, function() {
              $target.data( 'stickyColumnInit', true );
              stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
            } );

            var targetMutation = $target[0],
              config         = { attributes: true, childList: true, subtree: true };

            var callbackMutation = function( mutationsList, observer ) {
              for( var mutation of mutationsList ) {
                if ( 'attributes' === mutation.type ) {
                  $target[0].style.height = 'auto';
                }
              }
            };

            var observer = new MutationObserver( callbackMutation );
            observer.observe( targetMutation, config );

            $window.on( 'resize.KitifyStickyColumn orientationchange.KitifyStickyColumn', StickyColumnTools.debounce( 50, resizeDebounce ) );

            MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

            var observer = new MutationObserver( function( mutations ) {
              if ( stickyInstance ) {
                mutations.forEach( function(mutation){
                  if (mutation.attributeName === 'class') {
                    setTimeout( function() {
                      stickyInstance.destroy();
                      stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
                    }, 100 );
                  } else {
                    stickyInstance.destroy();
                    stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
                  }
                } )
              }
            } );

            $observerTarget.each( function(){
              observer.observe( $( this )[0], {
                subtree: true,
                childList: true,
                attributes: true
              } );
            } );
          }
        }
      } else {

        return false;

        settings = StickyColumn.columnEditorSettings( columnId );

        if ( 'true' === settings['sticky'] ) {
          $target.addClass( 'kitify-sticky-column' );

          if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
            stickyInstanceOptions.topSpacing = settings['topSpacing'];
            stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

            $target.data( 'stickyColumnInit', true );
            stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );

            $window.on( 'resize.KitifyStickyColumn orientationchange.KitifyStickyColumn', StickyColumnTools.debounce( 50, resizeDebounce ) );
          }
        }
      }

      function resizeDebounce() {
        var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
          availableDevices  = settings['stickyOn'] || [],
          isInit            = $target.data( 'stickyColumnInit' );

        if ( -1 !== availableDevices.indexOf( currentDeviceMode ) ) {

          if ( ! isInit ) {
            $target.data( 'stickyColumnInit', true );
            stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
            stickyInstance.updateSticky();
          }
        } else {
          $target.data( 'stickyColumnInit', false );
          stickyInstance.destroy();
        }
      }

    },
    elementorContainer: function( $scope ) {
      var $target  = $scope,
        $parentSection = $scope.closest( '.e-container--row' ),
        $window  = $( window ),
        columnId = $target.data( 'id' ),
        editMode = Boolean( elementor.isEditMode() ),
        settings = {},
        stickyInstance = null,
        stickyInstanceOptions = {
          topSpacing: 50,
          bottomSpacing: 50,
          containerSelector: '.e-container--row',
          innerWrapperSelector: '.e-container--column',
        },
        $observerTarget = $target.find('.elementor-element');

        if ( ! editMode ) {
        settings = $target.data( 'kitify-settings' );

        if ( $target.hasClass( 'kitify-sticky-column' ) ) {

          if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
            stickyInstanceOptions.topSpacing = settings['topSpacing'];
            stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

            imagesLoaded( $parentSection, function() {
              $target.data( 'stickyColumnInit', true );
              stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
            } );
            var targetMutation = $target[0],
              config         = { attributes: true, childList: true, subtree: true };

            var callbackMutation = function( mutationsList, observer ) {
              for( var mutation of mutationsList ) {
                if ( 'attributes' === mutation.type ) {
                  $target[0].style.height = 'auto';
                }
              }
            };

            var observer = new MutationObserver( callbackMutation );
            observer.observe( targetMutation, config );

            $window.on( 'resize.KitifyStickyColumn orientationchange.KitifyStickyColumn', StickyColumnTools.debounce( 50, resizeDebounce ) );

            MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

            var observer = new MutationObserver( function( mutations ) {
              if ( stickyInstance ) {
                mutations.forEach( function(mutation){
                  if (mutation.attributeName === 'class') {
                    setTimeout( function() {
                      stickyInstance.destroy();
                      stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
                    }, 100 );
                  } else {
                    stickyInstance.destroy();
                    stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
                  }
                } )
              }
            } );
            $observerTarget.each( function(){
              observer.observe( $( this )[0], {
                subtree: true,
                childList: true,
                attributes: true
              } );
            } );

          }
        }
      } else {

        return false;

        settings = StickyColumn.columnEditorSettings( columnId );

        if ( 'true' === settings['sticky'] ) {
          $target.addClass( 'kitify-sticky-column' );

          if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
            stickyInstanceOptions.topSpacing = settings['topSpacing'];
            stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

            $target.data( 'stickyColumnInit', true );
            stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );

            $window.on( 'resize.KitifyStickyColumn orientationchange.KitifyStickyColumn', StickyColumnTools.debounce( 50, resizeDebounce ) );
          }
        }
      }

      function resizeDebounce() {
        var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
          availableDevices  = settings['stickyOn'] || [],
          isInit            = $target.data( 'stickyColumnInit' );

        if ( -1 !== availableDevices.indexOf( currentDeviceMode ) ) {

          if ( ! isInit ) {
            $target.data( 'stickyColumnInit', true );
            stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
            stickyInstance.updateSticky();
          }
        } else {
          $target.data( 'stickyColumnInit', false );
          stickyInstance.destroy();
        }
      }

    },
    columnEditorSettings: function( columnId ) {
      var editorElements = null,
        columnData     = {};

      if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
        return false;
      }

      editorElements = window.elementor.elements;

      if ( ! editorElements.models ) {
        return false;
      }

      $.each( editorElements.models, function( index, obj ) {

        $.each( obj.attributes.elements.models, function( index, obj ) {
          if ( columnId == obj.id ) {
            columnData = obj.attributes.settings.attributes;
          }
        } );

      } );

      return {
        'sticky': columnData['jet_tricks_column_sticky'] || false,
        'topSpacing': columnData['jet_tricks_top_spacing'] || 50,
        'bottomSpacing': columnData['jet_tricks_bottom_spacing'] || 50,
        'stickyOn': columnData['jet_tricks_column_sticky_on'] || [ 'desktop', 'tablet', 'mobile']
      }

    },
  };
  $( window ).on( 'elementor/frontend/init', StickyColumn.init );

  var StickyColumnTools = {
    debounce: function( threshold, callback ) {
      var timeout;

      return function debounced( $event ) {
        function delayed() {
          callback.call( this, $event );
          timeout = null;
        }

        if ( timeout ) {
          clearTimeout( timeout );
        }

        timeout = setTimeout( delayed, threshold );
      };
    },
  };

}( jQuery, window.elementorFrontend ) );
