define([],
    function() {
        "use strict";

        return {

            getCookie : function(name){
                var cookie = " " + document.cookie;
                var search = " " + name + "=";
                var setStr = null;
                var offset = 0;
                var end = 0;
                if (cookie.length > 0) {
                    offset = cookie.indexOf(search);
                    if (offset != -1) {
                        offset += search.length;
                        end = cookie.indexOf(";", offset);
                        if (end == -1) {
                            end = cookie.length;
                        }
                        setStr = decodeURI(cookie.substring(offset, end));
                    }
                }
                return(setStr);
            },

            delCookie : function(name) {
                var date = new Date(0);
                var cookie = name + "=" + "; path=/; expires=" + date.toUTCString();
                document.cookie = cookie;
            },

            parseJson : function (json) {
                var json = decodeURIComponent(json.replace(/\+/g, ' '));
                return JSON.parse(json);
            }
        }
    });