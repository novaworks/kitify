!function(e,t){"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?module.exports=t():t()}(0,function(){var e="",t=20,i=!0,n=[],a=!1,l=!0,s=!0,r=null,o=!0,c=!0,m=null,d=!0,p=!1,u=!1,g=!0,h=!0,w=!1,f=null;function b(e){return e.replace(/<b[^>]*>(.*?)<\/b>/gi,function(e,t){return t}).replace(/class="(?!(tco-hidden|tco-display|tco-ellipsis))+.*?"|data-query-source=".*?"|dir=".*?"|rel=".*?"/gi,"")}function v(e){for(var t=e.getElementsByTagName("a"),i=t.length-1;i>=0;i--)t[i].setAttribute("target","_blank"),t[i].setAttribute("rel","noopener")}function _(e,t){for(var i=[],n=new RegExp("(^| )"+t+"( |$)"),a=e.getElementsByTagName("*"),l=0,s=a.length;l<s;l++)n.test(a[l].className)&&i.push(a[l]);return i}function y(e){if(void 0!==e&&e.innerHTML.indexOf("data-image")>=0){for(var t=e.innerHTML.match(/data-image=\"([^"]+)\"/gi),i=0;i<t.length;i++)t[i]=t[i].match(/data-image=\"([^"]+)\"/i)[1],t[i]=decodeURIComponent(t[i])+".jpg";return t}}var T={fetch:function(o){if(void 0===o.maxTweets&&(o.maxTweets=20),void 0===o.enableLinks&&(o.enableLinks=!0),void 0===o.showUser&&(o.showUser=!0),void 0===o.showTime&&(o.showTime=!0),void 0===o.dateFunction&&(o.dateFunction="default"),void 0===o.showRetweet&&(o.showRetweet=!0),void 0===o.customCallback&&(o.customCallback=null),void 0===o.showInteraction&&(o.showInteraction=!0),void 0===o.showImages&&(o.showImages=!1),void 0===o.useEmoji&&(o.useEmoji=!1),void 0===o.linksInNewWindow&&(o.linksInNewWindow=!0),void 0===o.showPermalinks&&(o.showPermalinks=!0),void 0===o.dataOnly&&(o.dataOnly=!1),a)n.push(o);else{a=!0,e=o.domId,t=o.maxTweets,i=o.enableLinks,s=o.showUser,l=o.showTime,c=o.showRetweet,r=o.dateFunction,m=o.customCallback,d=o.showInteraction,p=o.showImages,u=o.useEmoji,g=o.linksInNewWindow,h=o.showPermalinks,w=o.dataOnly;var b=document.getElementsByTagName("head")[0];null!==f&&b.removeChild(f),(f=document.createElement("script")).type="text/javascript",void 0!==o.list?f.src="https://syndication.twitter.com/timeline/list?callback=__twttrf.callback&dnt=false&list_slug="+o.list.listSlug+"&screen_name="+o.list.screenName+"&suppress_response_codes=true&lang="+(o.lang||"en")+"&rnd="+Math.random():void 0!==o.profile?f.src="https://syndication.twitter.com/timeline/profile?callback=__twttrf.callback&dnt=false&screen_name="+o.profile.screenName+"&suppress_response_codes=true&lang="+(o.lang||"en")+"&rnd="+Math.random():void 0!==o.likes?f.src="https://syndication.twitter.com/timeline/likes?callback=__twttrf.callback&dnt=false&screen_name="+o.likes.screenName+"&suppress_response_codes=true&lang="+(o.lang||"en")+"&rnd="+Math.random():void 0!==o.collection?f.src="https://syndication.twitter.com/timeline/collection?callback=__twttrf.callback&dnt=false&collection_id="+o.collection.collectionId+"&suppress_response_codes=true&lang="+(o.lang||"en")+"&rnd="+Math.random():f.src="https://cdn.syndication.twimg.com/widgets/timelines/"+o.id+"?&lang="+(o.lang||"en")+"&callback=__twttrf.callback&suppress_response_codes=true&rnd="+Math.random(),b.appendChild(f)}},callback:function(f){if(void 0===f||void 0===f.body)return a=!1,void(n.length>0&&(T.fetch(n[0]),n.splice(0,1)));u||(f.body=f.body.replace(/(<img[^c]*class="Emoji[^>]*>)|(<img[^c]*class="u-block[^>]*>)/g,"")),p||(f.body=f.body.replace(/(<img[^c]*class="NaturalImage-image[^>]*>|(<img[^c]*class="CroppedImage-image[^>]*>))/g,"")),s||(f.body=f.body.replace(/(<img[^c]*class="Avatar"[^>]*>)/g,""));var k=document.createElement("div");function C(e){var t=e.getElementsByTagName("img")[0];if(t)t.src=t.getAttribute("data-src-2x");else{var i=e.getElementsByTagName("a")[0].getAttribute("href").split("twitter.com/")[1],n=document.createElement("img");n.setAttribute("src","https://twitter.com/"+i+"/profile_image?size=bigger"),e.prepend(n)}return e}k.innerHTML=f.body,void 0===k.getElementsByClassName&&(o=!1);var E=[],x=[],N=[],A=[],B=[],I=[],M=[],L=0;if(o)for(var j=k.getElementsByClassName("timeline-Tweet");L<j.length;)j[L].getElementsByClassName("timeline-Tweet-retweetCredit").length>0?B.push(!0):B.push(!1),(!B[L]||B[L]&&c)&&(E.push(j[L].getElementsByClassName("timeline-Tweet-text")[0]),I.push(j[L].getAttribute("data-tweet-id")),s&&x.push(C(j[L].getElementsByClassName("timeline-Tweet-author")[0])),N.push(j[L].getElementsByClassName("dt-updated")[0]),M.push(j[L].getElementsByClassName("timeline-Tweet-timestamp")[0]),void 0!==j[L].getElementsByClassName("timeline-Tweet-media")[0]?A.push(j[L].getElementsByClassName("timeline-Tweet-media")[0]):A.push(void 0)),L++;else for(j=_(k,"timeline-Tweet");L<j.length;)_(j[L],"timeline-Tweet-retweetCredit").length>0?B.push(!0):B.push(!1),(!B[L]||B[L]&&c)&&(E.push(_(j[L],"timeline-Tweet-text")[0]),I.push(j[L].getAttribute("data-tweet-id")),s&&x.push(C(_(j[L],"timeline-Tweet-author")[0])),N.push(_(j[L],"dt-updated")[0]),M.push(_(j[L],"timeline-Tweet-timestamp")[0]),void 0!==_(j[L],"timeline-Tweet-media")[0]?A.push(_(j[L],"timeline-Tweet-media")[0]):A.push(void 0)),L++;E.length>t&&(E.splice(t,E.length-t),x.splice(t,x.length-t),N.splice(t,N.length-t),B.splice(t,B.length-t),A.splice(t,A.length-t),M.splice(t,M.length-t));var H=[],P=(L=E.length,0);if(w)for(;P<L;)H.push({tweet:E[P].innerHTML,tweetText:E[P].textContent,author:x[P]?x[P].innerHTML:"Unknown Author",author_data:{profile_url:x[P]?x[P].querySelector('[data-scribe="element:user_link"]').href:null,profile_image:x[P]?"https://twitter.com/"+x[P].querySelector('[data-scribe="element:screen_name"]').title.split("@")[1]+"/profile_image?size=bigger":null,profile_image_2x:x[P]?"https://twitter.com/"+x[P].querySelector('[data-scribe="element:screen_name"]').title.split("@")[1]+"/profile_image?size=original":null,screen_name:x[P]?x[P].querySelector('[data-scribe="element:screen_name"]').title:null,name:x[P]?x[P].querySelector('[data-scribe="element:name"]').title:null},time:N[P].textContent,timestring:N[P].getAttribute('aria-label'),timestamp:N[P].getAttribute("datetime").replace("+0000","Z").replace(/([\+\-])(\d\d)(\d\d)/,"$1$2:$3"),image:y(A[P])?y(A[P])[0]:void 0,images:y(A[P]),rt:B[P],tid:I[P],permalinkURL:void 0===M[P]?"":M[P].href}),P++;else for(;P<L;){if("string"!=typeof r){var R=N[P].getAttribute("datetime"),F=new Date(N[P].getAttribute("datetime").replace(/-/g,"/").replace("T"," ").split("+")[0]),S=r(F,R);if(N[P].setAttribute("aria-label",S),E[P].textContent)if(o)N[P].textContent=S;else{var q=document.createElement("p"),O=document.createTextNode(S);q.appendChild(O),q.setAttribute("aria-label",S),N[P]=q}else N[P].textContent=S}var U="";if(i?(g&&(v(E[P]),s&&v(x[P])),s&&(U+='<div class="user">'+b(x[P].innerHTML)+"</div>"),U+='<p class="tweet">'+b(E[P].innerHTML)+"</p>",l&&(U+=h?'<p class="timePosted"><a href="'+M[P]+'">'+N[P].getAttribute("aria-label")+"</a></p>":'<p class="timePosted">'+N[P].getAttribute("aria-label")+"</p>")):(E[P].textContent,s&&(U+='<p class="user">'+x[P].textContent+"</p>"),U+='<p class="tweet">'+E[P].textContent+"</p>",l&&(U+='<p class="timePosted">'+N[P].textContent+"</p>")),d&&(U+='<p class="interact"><a href="https://twitter.com/intent/tweet?in_reply_to='+I[P]+'" class="twitter_reply_icon"'+(g?' target="_blank" rel="noopener">':">")+'Reply</a><a href="https://twitter.com/intent/retweet?tweet_id='+I[P]+'" class="twitter_retweet_icon"'+(g?' target="_blank" rel="noopener">':">")+'Retweet</a><a href="https://twitter.com/intent/favorite?tweet_id='+I[P]+'" class="twitter_fav_icon"'+(g?' target="_blank" rel="noopener">':">")+"Favorite</a></p>"),p&&void 0!==A[P]&&void 0!==y(A[P]))for(var D=y(A[P]),$=0;$<D.length;$++)U+='<div class="media"><img src="'+D[$]+'" alt="Image from tweet" /></div>';p?H.push(U):!p&&E[P].textContent.length&&H.push(U),P++}!function(t){if(null===m){for(var i=t.length,n=0,a=document.getElementById(e),l="<ul>";n<i;)l+="<li>"+t[n]+"</li>",n++;l+="</ul>",a.innerHTML=l}else m(t)}(H),a=!1,n.length>0&&(T.fetch(n[0]),n.splice(0,1))}};return window.__twttrf=T,window.twitterFetcher=T,T}),[Element.prototype,Document.prototype,DocumentFragment.prototype].forEach(function(e){e.hasOwnProperty("prepend")||Object.defineProperty(e,"prepend",{configurable:!0,enumerable:!0,writable:!0,value:function(){var e=Array.prototype.slice.call(arguments),t=document.createDocumentFragment();e.forEach(function(e){var i=e instanceof Node;t.appendChild(i?e:document.createTextNode(String(e)))}),this.insertBefore(t,this.firstChild)}})});
( function( $, elementor ) {

    "use strict";

    function sanitize_name( text ){
        return text.toString().toLowerCase().replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\-]+/g, '') // Remove all non-word chars
            .replace(/\-\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, '');
    }

    function get_language(){
        var _lang = document.documentElement.lang;
        _lang = _lang.split('-');
        return _lang[0] || 'en';
    }

    var popupCenter = function(url, title, w, h){
        // Fixes dual-screen position                             Most browsers      Firefox
        var dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var systemZoom = width / window.screen.availWidth;
        var left = (width - w) / 2 / systemZoom + dualScreenLeft
        var top = (height - h) / 2 / systemZoom + dualScreenTop
        var newWindow = window.open(url, title,
            `
      scrollbars=yes,
      width=${w / systemZoom}, 
      height=${h / systemZoom}, 
      top=${top}, 
      left=${left}
      `
        )

        if (window.focus) newWindow.focus();
    }

    $( window ).on( 'elementor/frontend/init', function (){
        elementor.hooks.addAction( 'frontend/element_ready/kitify-twitter.default', function ( $scope ){

            var $feed_wrap = $scope.find('.kitify-twitter-feed');

            if ( $feed_wrap.data( 'initialized' ) ) {
                return;
            }

            $feed_wrap.data( 'initialized', true );

            var _config = $feed_wrap.data('feed_config');

            var screen_name = sanitize_name(_config.screen_name);

            if(screen_name == ''){
                $('#'+_config.uniqueid).html('Please setup `Screen Name` !');
                return;
            }

            var configProfile = {
                "profile": {
                    "screenName": screen_name
                },
                "domId": _config.uniqueid,
                "maxTweets": _config.limit || 1,
                "dataOnly": true,
                "enableLinks": true,
                "showUser": true,
                "showTime": true,
                "showImages": false,
                "customCallback": handleTweetCallback,
                "lang": get_language()
            }

            function handleTweetCallback(tweets){
                var html = '';
                for (var i = 0, lgth = tweets.length; i < lgth ; i++) {
                    var tweetObject = tweets[i];
                    html += '<div class="'+ _config.item_class +'">';
                    html += '<div class="kitify-twitter_feed__item_inner">';
                    if(_config.show_author_box == 'yes') {
                        html += '<div class="kitify-twitter_feed__author">' + tweetObject.author + '</div>';
                    }
                    html += '<div class="kitify-twitter_feed__content">' + (_config.show_link == 'yes' ? tweetObject.tweet.replace('<br><br>','<br>') : tweetObject.tweetText ) + '</div>';
                    if(_config.show_posted_date == 'yes' || _config.show_posted_date == 'yes'){
                        html += '<div class="kitify-twitter_feed__links">';
                        if(_config.show_twitter_icon == 'yes'){
                            html += '<span class="kitify-twitter_feed__logo"><svg class="svg-inline--fa fa-twitter fa-w-16" aria-hidden="true" aria-label="twitter logo" data-fa-processed="" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg></span>';
                        }
                        if(_config.show_posted_date == 'yes') {
                            html += '<a href="' + tweetObject.permalinkURL + '">' + tweetObject.timestring + '</a>';
                        }
                        html += '</div>';
                    }
                    if(_config.show_action == 'yes') {
                        html += '<div class="kitify-twitter_feed__interact">';
                        html += '<a class="kitify-twitter--reply" target="_blank" rel="noopener noreferrer" href="https://twitter.com/intent/tweet?in_reply_to=' + tweetObject.tid + '&related=' + tweetObject.author_data.name + '"><svg class="svg-inline--fa fa-w-16" viewBox="0 0 24 24" aria-label="reply" role="img" xmlns="http://www.w3.org/2000/svg"><g><path fill="currentColor" d="M14.046 2.242l-4.148-.01h-.002c-4.374 0-7.8 3.427-7.8 7.802 0 4.098 3.186 7.206 7.465 7.37v3.828c0 .108.044.286.12.403.142.225.384.347.632.347.138 0 .277-.038.402-.118.264-.168 6.473-4.14 8.088-5.506 1.902-1.61 3.04-3.97 3.043-6.312v-.017c-.006-4.367-3.43-7.787-7.8-7.788zm3.787 12.972c-1.134.96-4.862 3.405-6.772 4.643V16.67c0-.414-.335-.75-.75-.75h-.396c-3.66 0-6.318-2.476-6.318-5.886 0-3.534 2.768-6.302 6.3-6.302l4.147.01h.002c3.532 0 6.3 2.766 6.302 6.296-.003 1.91-.942 3.844-2.514 5.176z"></path></g></svg><span class="kitify-twitter--screenreader">Reply on Twitter ' + tweetObject.tid + '</span></a>';
                        html += '<a class="kitify-twitter--retweet" target="_blank" rel="noopener noreferrer" href="https://twitter.com/intent/retweet?tweet_id=' + tweetObject.tid + '&related=' + tweetObject.author_data.name + '"><svg class="svg-inline--fa fa-w-16" viewBox="0 0 24 24" aria-hidden="true" aria-label="retweet" role="img"><path fill="currentColor" d="M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.06 0s-.294.768 0 1.06l3.5 3.5c.145.147.337.22.53.22s.383-.072.53-.22l3.5-3.5c.294-.292.294-.767 0-1.06zm-10.66 3.28H7.26c-1.24 0-2.25-1.01-2.25-2.25V6.46l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.06l-3.5-3.5c-.293-.294-.768-.294-1.06 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.293 1.06 0l2.22-2.22V16.7c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.337-.75-.75-.75z"></path></svg><span class="kitify-twitter--screenreader">Retweet on Twitter ' + tweetObject.tid + '</span></a>';
                        html += '<a class="kitify-twitter--like" target="_blank" rel="noopener noreferrer" href="https://twitter.com/intent/like?tweet_id=' + tweetObject.tid + '&related=' + tweetObject.author_data.name + '"><svg class="svg-inline--fa fa-w-16" viewBox="0 0 24 24" aria-hidden="true" aria-label="like" role="img" xmlns="http://www.w3.org/2000/svg"><g><path fill="currentColor" d="M12 21.638h-.014C9.403 21.59 1.95 14.856 1.95 8.478c0-3.064 2.525-5.754 5.403-5.754 2.29 0 3.83 1.58 4.646 2.73.814-1.148 2.354-2.73 4.645-2.73 2.88 0 5.404 2.69 5.404 5.755 0 6.376-7.454 13.11-10.037 13.157H12zM7.354 4.225c-2.08 0-3.903 1.988-3.903 4.255 0 5.74 7.034 11.596 8.55 11.658 1.518-.062 8.55-5.917 8.55-11.658 0-2.267-1.823-4.255-3.903-4.255-2.528 0-3.94 2.936-3.952 2.965-.23.562-1.156.562-1.387 0-.014-.03-1.425-2.965-3.954-2.965z"></path></g></svg><span class="kitify-twitter--screenreader">Like on Twitter ' + tweetObject.tid + '</span></a>';
                        html += '</div>';
                    }
                    html += '</div>';
                    html += '</div>';
                }
                $('#'+_config.uniqueid).html(html);
                Kitify.initCarousel($scope);
            }

            twitterFetcher.fetch(configProfile);

            $scope.on('click', '.kitify-twitter_feed__interact a', function (e){
                if(window.innerWidth > 1200){
                    e.preventDefault();
                    popupCenter($(this).attr('href'), '', 550, 480);
                }
            });

        } );
    } );

}( jQuery, window.elementorFrontend ) );
