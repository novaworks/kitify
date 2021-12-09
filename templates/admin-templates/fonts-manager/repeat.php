<cx-vui-repeater
  slot="content"
  :button-label="'<?php _e( 'New Font', 'kitify' ); ?>'"
  button-style="accent"
  button-size="mini"
  v-model="fieldsList"
  @add-new-item="addNewFont"
>
    <cx-vui-repeater-item
      v-for="( field, index ) in fieldsList"
      :title="getFontTitle( field )"
      :collapsed="isCollapsed( field )"
      :index="index"
      @clone-item="cloneFont( $event )"
      @delete-item="deleteFont( $event )"
      :key="index"
    >
        <cx-vui-input
          label="<?php _e('Title', 'kitify'); ?>"
          description="<?php _e('The title of the font as it appears in the options.', 'kitify'); ?>"
          :wrapper-css="[ 'equalwidth' ]"
          :size="'fullwidth'"
          :value="fieldsList[ index ].title"
          @input="setFontProp( index, 'title', $event )"
        ></cx-vui-input>
        <cx-vui-input
          label="<?php _e('Name', 'kitify'); ?>"
          description="<?php _e('The name of the font as it appears in the css. E.g `acumin-pro, sans-serif`', 'kitify'); ?>"
          :wrapper-css="[ 'equalwidth' ]"
          :size="'fullwidth'"
          :value="fieldsList[ index ].name"
          @input="setFontProp( index, 'name', $event )"
        ></cx-vui-input>
        <cx-vui-select
          label="<?php _e('Font type', 'kitify'); ?>"
          description=""
          :wrapper-css="[ 'equalwidth' ]"
          :size="'fullwidth'"
          :options-list="[
                {
                    value: 'upload',
                    label: '<?php _e('Upload', 'kitify'); ?>'
                },
                {
                    value: 'custom',
                    label: '<?php _e('Custom', 'kitify'); ?>'
                }
            ]"
          :value="fieldsList[ index ].type"
          @input="setFontProp( index, 'type', $event )"
        ></cx-vui-select>
        <cx-vui-input
          label="<?php _e('Font URL', 'kitify'); ?>"
          description="<?php _e('Enter Custom URL of the font', 'kitify'); ?>"
          :wrapper-css="[ 'equalwidth' ]"
          :size="'fullwidth'"
          :value="fieldsList[ index ].url"
          @input="setFontProp( index, 'url', $event )"
          :conditions="[
                {
                    'input':    fieldsList[ index ].type,
                    'compare': 'equal',
                    'value':   'custom',
                }
            ]"
        ></cx-vui-input>
        <cx-vui-component-wrapper
          :wrapper-css="[ 'fullwidth-control' ]"
          :conditions="[
                {
                    'input':    fieldsList[ index ].type,
                    'compare':  'equal',
                    'value':    'upload',
                }
            ]"
        >
            <div class="cx-vui-inner-panel">
                <cx-vui-repeater
                  :button-label="'<?php _e( 'New Variation', 'kitify' ); ?>'"
                  :button-style="'accent'"
                  :button-size="'mini'"
                  v-model="fieldsList[ index ].variations"
                  @add-new-item="addNewVariation( $event, index )"
                >
                    <cx-vui-repeater-item
                      v-for="( option, optionIndex ) in fieldsList[ index ].variations"
                      :title="getVariationTitle( option )"
                      :collapsed="isCollapsed( option )"
                      :index="optionIndex"
                      @clone-item="cloneVariation( $event, index )"
                      @delete-item="deleteVariation( $event, index )"
                      :key="index + optionIndex"
                    >
                        <cx-vui-select
                          label="<?php _e( 'Weight', 'kitify' ); ?>"
                          description=""
                          :wrapper-css="[ 'equalwidth' ]"
                          :size="'fullwidth'"
                          :options-list="[
                            {
                                value: 'normal',
                                label: '<?php _e( 'Normal', 'kitify' ); ?>'
                            },
                            {
                                value: 'bold',
                                label: '<?php _e( 'Bold', 'kitify' ); ?>'
                            },
                            {
                                value: '100',
                                label: '100'
                            },
                            {
                                value: '200',
                                label: '200'
                            },
                            {
                                value: '300',
                                label: '300'
                            },
                            {
                                value: '400',
                                label: '400'
                            },
                            {
                                value: '500',
                                label: '500'
                            },
                            {
                                value: '600',
                                label: '600'
                            },
                            {
                                value: '700',
                                label: '700'
                            },
                            {
                                value: '800',
                                label: '800'
                            },
                            {
                                value: '900',
                                label: '900'
                            }
                        ]"
                          :value="fieldsList[ index ].variations[ optionIndex ].weight"
                          @input="setVariationProp( index, optionIndex, 'weight', $event )"
                        ></cx-vui-select>
                        <cx-vui-select
                          label="<?php _e( 'Style', 'kitify' ); ?>"
                          description=""
                          :wrapper-css="[ 'equalwidth' ]"
                          :size="'fullwidth'"
                          :options-list="[
                            {
                                value: 'normal',
                                label: '<?php _e( 'Normal', 'kitify' ); ?>'
                            },
                            {
                                value: 'italic',
                                label: '<?php _e( 'Italic', 'kitify' ); ?>'
                            },
                            {
                                value: 'oblique',
                                label: '<?php _e( 'Oblique', 'kitify' ); ?>'
                            }
                          ]"
                          :value="fieldsList[ index ].variations[ optionIndex ].style"
                          @input="setVariationProp( index, optionIndex, 'style', $event )"
                        ></cx-vui-select>

                        <cx-vui-wp-media
                          label="<?php _e( 'WOFF File', 'kitify' ); ?>"
                          description="<?php _e( 'Upload the font\'s woff file or enter the URL.', 'kitify' ); ?>"
                          return-type="string"
                          :multiple="false"
                          :wrapper-css="[ 'equalwidth' ]"
                          :value="fieldsList[ index ].variations[ optionIndex ].woff"
                          @input="setVariationProp( index, optionIndex, 'woff', $event )"
                        ></cx-vui-wp-media>
                        <cx-vui-wp-media
                          label="<?php _e( 'WOFF2 File', 'kitify' ); ?>"
                          description="<?php _e( 'Upload the font\'s woff2 file or enter the URL.', 'kitify' ); ?>"
                          return-type="string"
                          :multiple="false"
                          :wrapper-css="[ 'equalwidth' ]"
                          :value="fieldsList[ index ].variations[ optionIndex ].woff2"
                          @input="setVariationProp( index, optionIndex, 'woff2', $event )"
                        ></cx-vui-wp-media>
                        <cx-vui-wp-media
                          label="<?php _e( 'TTF File', 'kitify' ); ?>"
                          description="<?php _e( 'Upload the font\'s ttf file or enter the URL.', 'kitify' ); ?>"
                          return-type="string"
                          :multiple="false"
                          :wrapper-css="[ 'equalwidth' ]"
                          :value="fieldsList[ index ].variations[ optionIndex ].ttf"
                          @input="setVariationProp( index, optionIndex, 'ttf', $event )"
                        ></cx-vui-wp-media>
                        <cx-vui-wp-media
                          label="<?php _e( 'SVG File', 'kitify' ); ?>"
                          description="<?php _e( 'Upload the font\'s ttf file or enter the SVG.', 'kitify' ); ?>"
                          return-type="string"
                          :multiple="false"
                          :wrapper-css="[ 'equalwidth' ]"
                          :value="fieldsList[ index ].variations[ optionIndex ].svg"
                          @input="setVariationProp( index, optionIndex, 'svg', $event )"
                        ></cx-vui-wp-media>

                    </cx-vui-repeater-item>
                </cx-vui-repeater>
            </div>
        </cx-vui-component-wrapper>

    </cx-vui-repeater-item>
</cx-vui-repeater>