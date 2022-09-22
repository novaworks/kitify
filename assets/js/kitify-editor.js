( function( $ ) {

	'use strict';

	var KitifyEditor = {

		activeSection: null,

		editedElement: null,

		activedSubTab: null,

		modal: false,

		modalConditions: false,

		init: function() {

			window.elementor.channels.editor.on( 'section:activated', KitifyEditor.onAnimatedBoxSectionActivated );

			window.elementor.channels.editor.on( 'section:activated', KitifyEditor.onSearchSectionActivated );

			window.elementor.on( 'preview:loaded', function() {
				window.elementor.$preview[0].contentWindow.KitifyEditor = KitifyEditor;
				KitifyEditor.onPreviewLoaded();
			});
			$(document).on('kitify:editor:tab_active', KitifyEditor.onTabActive );

			$(document).on('click', '#elementor-panel .elementor-control.elementor-control-type-tab', function (e){
				var classList = this.classList.toString().match(/\selementor-control-([A-Za-z0-9._%-]*)\s/);
				var _tab_active = typeof classList[1] !== "undefined" ? classList[1] : null;
				window.KitifyEditor.activedSubTab = _tab_active;
				$(document).trigger('kitify:editor:tab_active', [_tab_active]);
			});

		},

		onSearchSectionActivated: function( sectionName, editor ) {

			var editedElement = editor.getOption( 'editedElementView' );

			if ( 'kitify-search' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.KitifyEditor.activeSection = sectionName;

		},

		onTabActive: function(event, _tab_active){
			var editedElement = window.KitifyEditor.editedElement;
			if(editedElement){
				if('kitify-animated-box' === editedElement.model.get( 'widgetType' )){
					var	allowActiveTabs = ['tab_back_general_styles','tab_back_box_inner_styles', 'tab_back_icon_styles', 'tab_back_title_styles', 'tab_back_subtitle_styles', 'tab_back_description_styles', 'tab_back_overlay', 'tab_back_order'],
						allowDeactiveTabs = ['tab_front_general_styles', 'tab_front_box_inner_styles', 'tab_front_icon_styles', 'tab_front_title_styles', 'tab_front_subtitle_styles', 'tab_front_description_styles', 'tab_front_overlay', 'tab_front_order'];
					if(allowActiveTabs.includes(_tab_active)){
						editedElement.$el.find( '.kitify-animated-box' ).addClass( 'flipped' );
						editedElement.$el.find( '.kitify-animated-box' ).addClass( 'flipped-stop' );
					}
					else if(allowDeactiveTabs.includes(_tab_active)){
						editedElement.$el.find( '.kitify-animated-box' ).removeClass( 'flipped' );
						editedElement.$el.find( '.kitify-animated-box' ).removeClass( 'flipped-stop' );
					}
				}
			}
		},

		onAnimatedBoxSectionActivated: function( sectionName, editor ) {

			window.KitifyEditor.activedSubTab = null;

			var editedElement = editor.getOption( 'editedElementView' ),
				prevEditedElement = window.KitifyEditor.editedElement;

			if ( prevEditedElement && 'kitify-animated-box' === prevEditedElement.model.get( 'widgetType' ) ) {

				prevEditedElement.$el.find( '.kitify-animated-box' ).removeClass( 'flipped' );
				prevEditedElement.$el.find( '.kitify-animated-box' ).removeClass( 'flipped-stop' );

				window.KitifyEditor.editedElement = null;
			}

			if ( 'kitify-animated-box' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			editedElement.$el.find( '.kitify-animated-box' ).addClass( 'flipped' );
			editedElement.$el.find( '.kitify-animated-box' ).addClass( 'flipped-stop' );

			window.KitifyEditor.editedElement = editedElement;
			window.KitifyEditor.activeSection = sectionName;

			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( sectionName );

			if ( isBackSide ) {
				editedElement.$el.find( '.kitify-animated-box' ).addClass( 'flipped' );
				editedElement.$el.find( '.kitify-animated-box' ).addClass( 'flipped-stop' );
			} else {
				editedElement.$el.find( '.kitify-animated-box' ).removeClass( 'flipped' );
				editedElement.$el.find( '.kitify-animated-box' ).removeClass( 'flipped-stop' );
			}

		},

		onPreviewLoaded: function() {
			var elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.elements.$document.on('click', '.kitify-tabs__edit-cover', KitifyEditor.showTemplatesModal );
			elementorFrontend.elements.$document.on('click', '.kitify-edit-template-link', KitifyEditor.showTemplatesModal );
			elementorFrontend.elements.$document.on('click', '.kitify-tabs-new-template-link', function (e){
				window.location.href = $( this ).attr( 'href' );
			} );

			KitifyEditor.getModal().on( 'hide', function() {
				window.elementor.reloadPreview();
			});
		},

		showTemplatesModal: function(evt) {
			if(evt){
				evt.preventDefault();
			}
			var editLink = $( this ).data( 'template-edit-link' );

			KitifyEditor.showModal( editLink );
		},

		showModal: function( link ) {
			var $iframe,
				$loader;

			KitifyEditor.getModal().show();

			$( '#kitify-tabs-template-edit-modal .dialog-message').html( '<iframe src="' + link + '" id="kitify-tabs-edit-frame" width="100%" height="100%"></iframe>' );
			$( '#kitify-tabs-template-edit-modal .dialog-message').append( '<div id="kitify-tabs-loading"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>' );

			$iframe = $( '#kitify-tabs-edit-frame');
			$loader = $( '#kitify-tabs-loading');

			$iframe.on( 'load', function() {
				$loader.fadeOut( 300 );
			} );
		},

		getModal: function() {

			if ( ! KitifyEditor.modal ) {
				this.modal = elementor.dialogsManager.createWidget( 'lightbox', {
					id: 'kitify-tabs-template-edit-modal',
					closeButton: true,
					hide: {
						onBackgroundClick: false
					}
				} );
			}

			return KitifyEditor.modal;
		},
	};

	$( window ).on( 'elementor:init', KitifyEditor.init );

	window.KitifyEditor = KitifyEditor;

}( jQuery ) );
