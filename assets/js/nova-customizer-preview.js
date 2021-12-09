(function($, api) {
    "use strict";

    var cssRules = {};

    function liveUpdateCSS(id, css){
        var _tmp_id = id.replace('[', '_').replace(']', '_').replace(/_*$/, '');
        _tmp_id = 'kitify_cs__' + _tmp_id;

        var styleSelector = document.getElementById( _tmp_id );
        if(styleSelector){
            styleSelector.innerHTML = css;
        }
        else{
            var tmpDiv = document.createElement("div");
            tmpDiv.innerHTML = "<style id='"+_tmp_id+"'>" + css + "</style>";
            document.getElementsByTagName("head")[0].appendChild(tmpDiv.childNodes[0])
        }
    }

    function renderCssRules(){
        var css = '';
        $.each(cssRules, function ( key, value ){
            if(value !== ''){
                css += value;
            }
        });
        liveUpdateCSS('cs_style', css);
    }

    function setCssRule( key, rules, value ){
        if(value == ''){
            cssRules[key] = '';
        }
        else{
            if(rules.length){
                var _css = '';
                rules.forEach( function ( rule ){
                    _css += rule.selector + '{' + rule.property + ':' + value + '}';
                } );
                cssRules[key] = _css;
            }
        }
        renderCssRules();
    }

    $.each(kitifyCustomizeConfigs, function ( key, css_rule ){
        api(key, function (value){
            value.bind(function ( newval ){
                setCssRule( key, css_rule, newval );
            });
        });
    });

})(jQuery, wp.customize);