<?php
/**
 * Main dashboard template
 */
?><div id="kitify-menu-settings-nav">
    <transition name="popup">
        <cx-vui-popup
            class="nav-settings-popup"
            :class="{'editor-mode':editorVisible}"
            v-model="itemSettingItem"
            :footer="false"
            body-width="false"
            @on-cancel="navSettingPopupClose"
        >
            <div class="cx-vui-popup__content-inner" slot="content">

                <cx-vui-tabs
                    class="nav-settings-tabs"
                    :class="{ 'loading-state':getItemDataState }"
                    :in-panel="false"
                    :value="defaultActiveTab"
                    layout="vertical"
                    v-if="!editorVisible"
                >
                    <cx-vui-tabs-panel
                        name="mega-menu-tab"
                        label="<?php _e( 'Mega Content', 'kitify' ); ?>"
                        key="mega-menu-tab"
                    >
                        <div class="cx-vui-tabs-panel__inner" v-if="!getItemDataState">

                            <div class="cx-vui-alert info-type" v-if="!isTopItem">
                                <div class="cx-vui-alert__icon">
                                    <svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 20.5C10.3672 20.5 11.4609 19.4062 11.4609 18H6.5C6.5 19.4062 7.59375 20.5 9 20.5ZM17.3984 14.6797C16.6562 13.8594 15.2109 12.6484 15.2109 8.625C15.2109 5.61719 13.1016 3.19531 10.2109 2.57031V1.75C10.2109 1.08594 9.66406 0.5 9 0.5C8.29688 0.5 7.75 1.08594 7.75 1.75V2.57031C4.85938 3.19531 2.75 5.61719 2.75 8.625C2.75 12.6484 1.30469 13.8594 0.5625 14.6797C0.328125 14.9141 0.210938 15.2266 0.25 15.5C0.25 16.1641 0.71875 16.75 1.5 16.75H16.4609C17.2422 16.75 17.7109 16.1641 17.75 15.5C17.75 15.2266 17.6328 14.9141 17.3984 14.6797Z"/>
                                    </svg>
                                </div>
                                <div class="cx-vui-alert__message"><?php
                                    _e( 'Content for sub-menu items available only for mobile view at this moment', 'kitify' );
                                    ?></div>
                            </div>

                            <cx-vui-switcher
                                name="enabled"
                                label="<?php _e( 'Mega submenu enabled', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                return-true="true"
                                return-false="false"
                                v-model="controlData['enabled']['value']">
                            </cx-vui-switcher>

                            <div class="cx-vui-component cx-vui-component--equalwidth">
                                <div class="cx-vui-component__meta">
                                    <label class="cx-vui-component__label"><?php _e( 'Mega menu item content', 'kitify' ); ?></label>
                                </div>
                                <div class="cx-vui-component__control">
                                    <cx-vui-button
                                        button-style="accent-border"
                                        size="mini"
                                        @click="openEditor"
                                    >
                                        <span slot="label"><?php _e( 'Edit Mega Menu Item Content', 'kitify' ); ?></span>
                                    </cx-vui-button>
                                </div>
                            </div>

                            <cx-vui-select
                                name="custom_mega_menu_position"
                                label="<?php _e( 'Mega menu position', 'kitify' ); ?>"
                                description="<?php _e( 'Megamenu container position relative to item. Desktop layout only.', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                :options-list="controlData['custom_mega_menu_position']['options']"
                                v-model="controlData['custom_mega_menu_position']['value']"
                                v-if="isTopItem"
                            >
                            </cx-vui-select>

                            <cx-vui-input
                                name="custom_mega_menu_width"
                                label="<?php _e( 'Custom mega menu width', 'kitify' ); ?>"
                                description="<?php _e( 'Set custom mega menu width for this item(px). Desktop layout only.', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                type="number"
                                :min="200"
                                :max="2000"
                                :step="100"
                                v-model="controlData['custom_mega_menu_width']['value']"
                                v-if="isTopItem"
                            >
                            </cx-vui-input>

                            <div class="save-control">
                                <cx-vui-button
                                    button-style="accent-border"
                                    size="mini"
                                    @click="saveItemSettings"
                                    :loading="itemSavingState"
                                >
                                    <span slot="label"><?php _e( 'Save', 'kitify' ); ?></span>
                                </cx-vui-button>
                            </div>
                        </div>


                    </cx-vui-tabs-panel>

                    <cx-vui-tabs-panel
                        name="icon-tab"
                        label="<?php _e( 'Item Icon', 'kitify' ); ?>"
                        key="icon-tab"
                    >
                        <div class="cx-vui-tabs-panel__inner" v-if="!getItemDataState">

                            <cx-vui-select
                                name="menu_icon_type"
                                label="<?php _e( 'Icon type', 'kitify' ); ?>"
                                size="fullwidth"
                                :wrapper-css="[ 'equalwidth' ]"
                                :options-list="controlData['menu_icon_type']['options']"
                                v-model="controlData['menu_icon_type']['value']"
                            >
                            </cx-vui-select>

                            <cx-vui-iconpicker
                                name="menu_icon"
                                label="<?php _e( 'Item icon', 'kitify' ); ?>"
                                icon-base="fa"
                                icon-prefix="fa-"
                                :icons="iconSet"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                v-model="controlData['menu_icon']['value']"
                                :conditions="[
									{
										input: controlData['menu_icon_type']['value'],
										compare: 'equal',
										value: 'icon',
									}
								]"
                            ></cx-vui-iconpicker>

                            <cx-vui-wp-media
                                label="<?php _e( 'Item SVG', 'kitify' ); ?>"
                                name="menu_svg_id"
                                return-type="string"
                                :multiple="false"
                                :wrapper-css="[ 'equalwidth' ]"
                                v-model="controlData['menu_svg']['value']"
                                :conditions="[
									{
										input: controlData['menu_icon_type']['value'],
										compare: 'equal',
										value: 'svg',
									}
								]"
                            ></cx-vui-wp-media>

                            <cx-vui-colorpicker
                                name="icon_color"
                                label="<?php _e( 'Icon color', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                type="hex"
                                v-model="controlData['icon_color']['value']"
                            ></cx-vui-colorpicker>

                            <cx-vui-input
                                name="icon_size"
                                label="<?php _e( 'Icon size', 'kitify' ); ?>"
                                description="<?php _e( 'Set icon size for this item(px)', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                type="number"
                                :min="8"
                                :max="300"
                                :step="1"
                                v-model="controlData['icon_size']['value']">
                            </cx-vui-input>

                            <div class="save-control">
                                <cx-vui-button
                                    button-style="accent-border"
                                    size="mini"
                                    @click="saveItemSettings"
                                    :loading="itemSavingState"
                                >
                                    <span slot="label"><?php _e( 'Save', 'kitify' ); ?></span>
                                </cx-vui-button>
                            </div>
                        </div>

                    </cx-vui-tabs-panel>

                    <cx-vui-tabs-panel
                        name="badge-tab"
                        label="<?php _e( 'Item Badge', 'kitify' ); ?>"
                        key="badge-tab"
                    >
                        <div class="cx-vui-tabs-panel__inner" v-if="!getItemDataState">
                            <cx-vui-input
                                name="menu_badge"
                                label="<?php _e( 'Item badge', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                type="text"
                                v-model="controlData['menu_badge']['value']">
                            </cx-vui-input>

                            <cx-vui-colorpicker
                                name="badge_color"
                                label="<?php _e( 'Badge color', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                type="hex"
                                v-model="controlData['badge_color']['value']"
                            ></cx-vui-colorpicker>

                            <cx-vui-colorpicker
                                name="badge_bg_color"
                                label="<?php _e( 'Badge background color', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                type="hex"
                                v-model="controlData['badge_bg_color']['value']"
                            ></cx-vui-colorpicker>

                            <div class="save-control">
                                <cx-vui-button
                                    button-style="accent-border"
                                    size="mini"
                                    @click="saveItemSettings"
                                    :loading="itemSavingState"
                                >
                                    <span slot="label"><?php _e( 'Save', 'kitify' ); ?></span>
                                </cx-vui-button>
                            </div>
                        </div>

                    </cx-vui-tabs-panel>

                    <cx-vui-tabs-panel
                        name="advanced-tab"
                        label="<?php _e( 'Advanced', 'kitify' ); ?>"
                        key="advanced-tab"
                    >
                        <div class="cx-vui-tabs-panel__inner" v-if="!getItemDataState">
                            <cx-vui-switcher
                                name="hide_item_text"
                                label="<?php _e( 'Hide item navigation label', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                return-true="true"
                                return-false="false"
                                v-model="controlData['hide_item_text']['value']">
                            </cx-vui-switcher>

                            <cx-vui-dimensions
                                name="item_padding"
                                label="<?php _e( 'Set custom padding for this item', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                :units="[
									{
										unit: 'px',
										min: 0,
										max: 100,
										step: 1
									}
								]"
                                v-model="controlData['item_padding']['value']"
                            >
                            </cx-vui-dimensions>

                            <div class="save-control">
                                <cx-vui-button
                                    button-style="accent-border"
                                    size="mini"
                                    @click="saveItemSettings"
                                    :loading="itemSavingState"
                                >
                                    <span slot="label"><?php _e( 'Save', 'kitify' ); ?></span>
                                </cx-vui-button>
                            </div>
                        </div>

                    </cx-vui-tabs-panel>

                    <cx-vui-tabs-panel
                        name="vertical-menu-tab"
                        label="<?php _e( 'Vertical Menu Widget', 'kitify' ); ?>"
                        key="vertical-menu-tab"
                        v-if="isTopItem"
                    >
                        <div class="cx-vui-tabs-panel__inner" v-if="!getItemDataState">
                            <cx-vui-input
                                name="mega_menu_width"
                                label="<?php _e( 'Custom mega menu width', 'kitify' ); ?>"
                                description="<?php _e( 'Set custom mega menu width for this item (px)', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                type="number"
                                :min="200"
                                :max="2000"
                                :step="100"
                                v-model="controlData['mega_menu_width']['value']">
                            </cx-vui-input>

                            <cx-vui-select
                                name="vertical_mega_menu_position"
                                label="<?php _e( 'Vertical mega menu position', 'kitify' ); ?>"
                                :wrapper-css="[ 'equalwidth' ]"
                                size="fullwidth"
                                :options-list="controlData['vertical_mega_menu_position']['options']"
                                v-model="controlData['vertical_mega_menu_position']['value']">
                            </cx-vui-select>

                            <div class="save-control">
                                <cx-vui-button
                                    button-style="accent-border"
                                    size="mini"
                                    @click="saveItemSettings"
                                    :loading="itemSavingState"
                                >
                                    <span slot="label"><?php _e( 'Save', 'kitify' ); ?></span>
                                </cx-vui-button>
                            </div>
                        </div>

                    </cx-vui-tabs-panel>

                </cx-vui-tabs>

                <iframe
                    v-if="editorVisible"
                    :src="currentEditorUrl"
                    width="100%"
                    class="kitify-edit-frame"
                ></iframe>

            </div>
        </cx-vui-popup>
    </transition>
</div>