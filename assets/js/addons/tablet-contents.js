;(function( $ ) {
    var $window = $(window);

    $window.on('elementor/frontend/init', function() {
        var ModuleHandler = elementorModules.frontend.handlers.Base,
            TOCHandler;

        TOCHandler = ModuleHandler.extend({
            getDefaultSettings: function () {
                const elementSettings = this.getElementSettings(),
                    listWrapperTag = 'numbers' === elementSettings.marker_view ? 'ol' : 'ul';
                return {
                    selectors: {
                        widgetContainer: '.elementor-widget-container',
                        postContentContainer: '.elementor:not([data-elementor-type="header"]):not([data-elementor-type="footer"]):not([data-elementor-type="popup"])',
                        expandButton: '.kitify-toc__toggle-button--expand',
                        collapseButton: '.kitify-toc__toggle-button--collapse',
                        body: '.kitify-toc__body',
                        headerTitle: '.kitify-toc__header-title'
                    },
                    classes: {
                        anchor: 'elementor-menu-anchor',
                        listWrapper: 'kitify-toc__list-wrapper',
                        listItem: 'kitify-toc__list-item',
                        listTextWrapper: 'kitify-toc__list-item-text-wrapper',
                        firstLevelListItem: 'kitify-toc__top-level',
                        listItemText: 'kitify-toc__list-item-text',
                        activeItem: 'elementor-item-active',
                        headingAnchor: 'kitify-toc__heading-anchor',
                        collapsed: 'kitify-toc--collapsed'
                    },
                    listWrapperTag
                };
            },

            getDefaultElements: function () {
                const settings = this.getSettings();
                return {
                    $pageContainer: this.getContainer(),
                    $widgetContainer: this.$element.find(settings.selectors.widgetContainer),
                    $expandButton: this.$element.find(settings.selectors.expandButton),
                    $collapseButton: this.$element.find(settings.selectors.collapseButton),
                    $tocBody: this.$element.find(settings.selectors.body),
                    $listItems: this.$element.find('.' + settings.classes.listItem)
                };
            },

            getContainer: function () {
                const settings = this.getSettings(),
                    elementSettings = this.getElementSettings(); // If there is a custom container defined by the user, use it as the headings-scan container

                if (elementSettings.container) {
                    return jQuery(elementSettings.container);
                } // Get the document wrapper element in which the TOC is located


                const $documentWrapper = this.$element.parents('.elementor'); // If the TOC container is a popup, only scan the popup for headings

                if ('popup' === $documentWrapper.attr('data-elementor-type')) {
                    return $documentWrapper;
                } // If the TOC container is anything other than a popup, scan only the post/page content for headings


                return jQuery(settings.selectors.postContentContainer);
            },

            bindEvents: function () {
                const elementSettings = this.getElementSettings();

                if (elementSettings.minimize_box) {
                    this.elements.$expandButton.on('click', () => this.expandBox());
                    this.elements.$collapseButton.on('click', () => this.collapseBox());
                }

                if (elementSettings.collapse_subitems) {
                    this.elements.$listItems.on('hover', event => jQuery(event.target).slideToggle());
                }
            },

            getHeadings: function () {
                // Get all headings from document by user-selected tags
                const elementSettings = this.getElementSettings(),
                    tags = elementSettings.headings_by_tags.join(','),
                    selectors = this.getSettings('selectors'),
                    excludedSelectors = elementSettings.exclude_headings_by_selector;
                return this.elements.$pageContainer.find(tags).not(selectors.headerTitle).filter((index, heading) => {
                    return !jQuery(heading).closest(excludedSelectors).length; // Handle excluded selectors if there are any
                });
            },

            addAnchorsBeforeHeadings: function () {
                const classes = this.getSettings('classes'); // Add an anchor element right before each TOC heading to create anchors for TOC links

                this.elements.$headings.before(index => {
                    // Check if the heading element itself has an ID, or if it is a widget which includes a main heading element, whether the widget wrapper has an ID
                    if (jQuery(this.elements.$headings[index]).data('hasOwnID')) {
                        return;
                    }

                    return `<span id="${classes.headingAnchor}-${index}" class="${classes.anchor} "></span>`;
                });
            },

            activateItem: function ($listItem) {
                const classes = this.getSettings('classes');
                this.deactivateActiveItem($listItem);
                $listItem.addClass(classes.activeItem);
                this.$activeItem = $listItem;

                if (!this.getElementSettings('collapse_subitems')) {
                    return;
                }

                let $activeList;

                if ($listItem.hasClass(classes.firstLevelListItem)) {
                    $activeList = $listItem.parent().next();
                } else {
                    $activeList = $listItem.parents('.' + classes.listWrapper).eq(-2);
                }

                if (!$activeList.length) {
                    delete this.$activeList;
                    return;
                }

                this.$activeList = $activeList;
                this.$activeList.stop().slideDown();
            },

            deactivateActiveItem: function ($activeToBe) {
                if (!this.$activeItem || this.$activeItem.is($activeToBe)) {
                    return;
                }

                const {
                    classes
                } = this.getSettings();
                this.$activeItem.removeClass(classes.activeItem);

                if (this.$activeList && (!$activeToBe || !this.$activeList[0].contains($activeToBe[0]))) {
                    this.$activeList.slideUp();
                }
            },

            followAnchor: function ($element, index) {
                const anchorSelector = $element[0].hash;
                let $anchor;

                try {
                    // `decodeURIComponent` for UTF8 characters in the hash.
                    $anchor = jQuery(decodeURIComponent(anchorSelector));
                } catch (e) {
                    return;
                }

                elementorFrontend.waypoint($anchor, direction => {
                    if (this.itemClicked) {
                        return;
                    }

                    const id = $anchor.attr('id');

                    if ('down' === direction) {
                        this.viewportItems[id] = true;
                        this.activateItem($element);
                    } else {
                        delete this.viewportItems[id];
                        this.activateItem(this.$listItemTexts.eq(index - 1));
                    }
                }, {
                    offset: 'bottom-in-view',
                    triggerOnce: false
                });
                elementorFrontend.waypoint($anchor, direction => {
                    if (this.itemClicked) {
                        return;
                    }

                    const id = $anchor.attr('id');

                    if ('down' === direction) {
                        delete this.viewportItems[id];

                        if (Object.keys(this.viewportItems).length) {
                            this.activateItem(this.$listItemTexts.eq(index + 1));
                        }
                    } else {
                        this.viewportItems[id] = true;
                        this.activateItem($element);
                    }
                }, {
                    offset: 0,
                    triggerOnce: false
                });
            },

            followAnchors: function () {
                this.$listItemTexts.each((index, element) => this.followAnchor(jQuery(element), index));
            },

            populateTOC: function () {
                this.listItemPointer = 0;
                const elementSettings = this.getElementSettings();

                if (elementSettings.hierarchical_view) {
                    this.createNestedList();
                } else {
                    this.createFlatList();
                }

                this.$listItemTexts = this.$element.find('.kitify-toc__list-item-text');
                this.$listItemTexts.on('click', this.onListItemClick.bind(this));

                if (!elementorFrontend.isEditMode()) {
                    this.followAnchors();
                }
            },

            createNestedList: function () {
                this.headingsData.forEach((heading, index) => {
                    heading.level = 0;

                    for (let i = index - 1; i >= 0; i--) {
                        const currentOrderedItem = this.headingsData[i];

                        if (currentOrderedItem.tag <= heading.tag) {
                            heading.level = currentOrderedItem.level;

                            if (currentOrderedItem.tag < heading.tag) {
                                heading.level++;
                            }

                            break;
                        }
                    }
                });
                this.elements.$tocBody.html(this.getNestedLevel(0));
            },

            createFlatList: function () {
                this.elements.$tocBody.html(this.getNestedLevel());
            },

            getNestedLevel: function (level) {
                const settings = this.getSettings(),
                    elementSettings = this.getElementSettings(),
                    icon = this.getElementSettings('icon');
                let renderedIcon;

                if (icon) {
                    // We generate the icon markup in PHP and make it available via get_frontend_settings(). As a result, the
                    // rendered icon is not available in the editor, so in the editor we use the regular <i> tag.
                    if (elementorFrontend.config.experimentalFeatures.e_font_icon_svg && !elementorFrontend.isEditMode()) {
                        renderedIcon = icon.rendered_tag;
                    } else {
                        renderedIcon = `<i class="${icon.value}"></i>`;
                    }
                } // Open new list/nested list


                let html = `<${settings.listWrapperTag} class="${settings.classes.listWrapper}">`; // for each list item, build its markup.

                while (this.listItemPointer < this.headingsData.length) {
                    const currentItem = this.headingsData[this.listItemPointer];
                    let listItemTextClasses = settings.classes.listItemText;

                    if (0 === currentItem.level) {
                        // If the current list item is a top level item, give it the first level class
                        listItemTextClasses += ' ' + settings.classes.firstLevelListItem;
                    }

                    if (level > currentItem.level) {
                        break;
                    }

                    if (level === currentItem.level) {
                        html += `<li class="${settings.classes.listItem}">`;
                        html += `<div class="${settings.classes.listTextWrapper}">`;
                        let liContent = `<a href="#${currentItem.anchorLink}" class="${listItemTextClasses}">${currentItem.text}</a>`; // If list type is bullets, add the bullet icon as an <i> tag

                        if ('bullets' === elementSettings.marker_view && icon) {
                            liContent = `${renderedIcon}${liContent}`;
                        }

                        html += liContent;
                        html += '</div>';
                        this.listItemPointer++;
                        const nextItem = this.headingsData[this.listItemPointer];

                        if (nextItem && level < nextItem.level) {
                            // If a new nested list has to be created under the current item,
                            // this entire method is called recursively (outside the while loop, a list wrapper is created)
                            html += this.getNestedLevel(nextItem.level);
                        }

                        html += '</li>';
                    }
                }

                html += `</${settings.listWrapperTag}>`;
                return html;
            },

            handleNoHeadingsFound: function () {
                let noHeadingsText = elementorProFrontend.config.i18n.toc_no_headings_found;

                if (elementorFrontend.isEditMode()) {
                    noHeadingsText = elementorPro.translate('toc_no_headings_found');
                }

                return this.elements.$tocBody.html(noHeadingsText);
            },

            collapseOnInit: function () {
                const minimizedOn = this.getElementSettings('minimized_on'),
                    currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

                if ('tablet' === minimizedOn && 'desktop' !== currentDeviceMode || 'mobile' === minimizedOn && 'mobile' === currentDeviceMode) {
                    this.collapseBox();
                }
            },

            getHeadingAnchorLink: function (index, classes) {
                const headingID = this.elements.$headings[index].id,
                    wrapperID = this.elements.$headings[index].closest('.elementor-widget').id;
                let anchorLink = '';

                if (headingID) {
                    anchorLink = headingID;
                } else if (wrapperID) {
                    // If the heading itself has an ID, we don't want to overwrite it
                    anchorLink = wrapperID;
                } // If there is no existing ID, use the heading text to create a semantic ID


                if (headingID || wrapperID) {
                    jQuery(this.elements.$headings[index]).data('hasOwnID', true);
                } else {
                    anchorLink = `${classes.headingAnchor}-${index}`;
                }

                return anchorLink;
            },

            setHeadingsData: function () {
                this.headingsData = [];
                const classes = this.getSettings('classes'); // Create an array for simplifying TOC list creation

                this.elements.$headings.each((index, element) => {
                    const anchorLink = this.getHeadingAnchorLink(index, classes);
                    this.headingsData.push({
                        tag: +element.nodeName.slice(1),
                        text: element.textContent,
                        anchorLink
                    });
                });
            },

            run: function () {

                this.elements.$headings = this.getHeadings();

                if (!this.elements.$headings.length) {
                    return this.handleNoHeadingsFound();
                }

                this.setHeadingsData();

                if (!elementorFrontend.isEditMode()) {
                    this.addAnchorsBeforeHeadings();
                }

                this.populateTOC();

                if (this.getElementSettings('minimize_box')) {
                    this.collapseOnInit();
                }
            },

            expandBox: function () {
                const boxHeight = this.getCurrentDeviceSetting('min_height');
                this.$element.removeClass(this.getSettings('classes.collapsed'));
                this.elements.$tocBody.slideDown(); // return container to the full height in case a min-height is defined by the user

                this.elements.$widgetContainer.css('min-height', boxHeight.size + boxHeight.unit);
            },

            collapseBox: function () {
                this.$element.addClass(this.getSettings('classes.collapsed'));
                this.elements.$tocBody.slideUp(); // close container in case a min-height is defined by the user

                this.elements.$widgetContainer.css('min-height', '0px');
            },

            onInit: function () {
                ModuleHandler.prototype.onInit.apply(this, arguments);
                this.viewportItems = [];
                jQuery(() => this.run());
            },

            onListItemClick: function (event) {
                this.itemClicked = true;
                setTimeout(() => this.itemClicked = false, 2000);
                const $clickedItem = jQuery(event.target),
                    $list = $clickedItem.parent().next(),
                    collapseNestedList = this.getElementSettings('collapse_subitems');
                let listIsActive;

                if (collapseNestedList && $clickedItem.hasClass(this.getSettings('classes.firstLevelListItem'))) {
                    if ($list.is(':visible')) {
                        listIsActive = true;
                    }
                }

                this.activateItem($clickedItem);

                if (collapseNestedList && listIsActive) {
                    $list.slideUp();
                }
            }
        });

        elementorFrontend.elementsHandler.attachHandler( 'kitify-table-of-contents', TOCHandler );
    });

}( jQuery ));
