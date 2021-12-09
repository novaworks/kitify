Vue.component( 'kitify-fonts-manager', {
    template: '#kitify-x-tmpl-fonts-manager',
    props: {
        value: {
            type: Array,
            default: function() {
                return [];
            },
        },
    },
    data: function() {
        console.log(this.value);
        return {
            fieldsList: this.value
        };
    },
    watch: {
        fieldsList: {
            handler: function( val ) {
                console.log(val);
                this.$emit( 'input', val );
            },
            deep: true,
        },
    },

    methods: {

        getFontTitle: function( field ) {
            var result = field.title;
            return result;
        },

        getVariationTitle: function( option ) {
            var result = option.weight + ' ' + option.style;
            return result;
        },

        addNewFont: function( ) {

            var field = {
                title: '',
                name: '',
                type: 'custom',
                url:  '',
                variations: [],
            };

            this.fieldsList.push( field );

        },
        cloneFont: function( index ) {
            var field    = JSON.parse( JSON.stringify( this.fieldsList[index] ) ),
                newField = {
                    title:                 field.title + ' Copy',
                    name:                  field.name,
                    type:                  field.type,
                    url:                   field.url,
                    variations:            field.variations,
                };

            this.fieldsList.splice( index + 1, 0, newField );
        },
        deleteFont: function( index ) {
            this.fieldsList.splice( index, 1 );
        },
        setFontProp: function( index, key, value ) {
            var field = this.fieldsList[ index ];
            field[ key ] = value;
            this.fieldsList.splice( index, 1, field );
        },

        cloneVariation: function( optionIndex, fieldIndex ) {

            var field     = this.fieldsList[ fieldIndex ],
                option    = field.variations[ optionIndex ],
                newOption = {
                    weight: option.weight,
                    style: option.style,
                    woff: option.woff,
                    woff2: option.woff2,
                    ttf: option.ttf,
                    svg: option.svg,
                };

            field.variations.splice( optionIndex + 1, 0, newOption );

            this.fieldsList.splice( fieldIndex, 1, field );

        },
        deleteVariation: function( optionIndex, fieldIndex ) {
            this.fieldsList[ fieldIndex ].variations.splice( optionIndex, 1 );
        },
        addNewVariation: function( $event, index ) {

            var option = {
                weight: '',
                style: '',
                woff: '',
                woff2: '',
                ttf: '',
                svg: '',
            };

            if ( ! this.fieldsList[ index ].variations ) {
                this.fieldsList[ index ].variations = [];
            }

            this.fieldsList[ index ].variations.push( option );

        },
        setVariationProp: function( fieldIndex, optionIndex, key, value ) {
            var field  = this.fieldsList[ fieldIndex ],
                option = field.variations[ optionIndex ];

            option[ key ] = value;

            field.variations.splice( optionIndex, 1, option );
            this.fieldsList.splice( fieldIndex, 1, field );
        },

        isCollapsed: function( object ) {
            if ( undefined === object.collapsed || true === object.collapsed ) {
                return true;
            } else {
                return false;
            }
        },
    },
} );
