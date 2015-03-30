Criptografia = {
            charset: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',
            charset_urlsafe: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_=',

            encode: function(data, urlsafe) {
            var charset = urlsafe ? Criptografia.charset_urlsafe : Criptografia.charset;

                    var i = 0;
                    var len = data.length;
                    var output = '';
                    var c1, c2, c3;
                    var e1, e2, e3, e4;

                    while (i < len) {
                        c1 = data.charCodeAt(i++);
                        c2 = data.charCodeAt(i++);
                        c3 = data.charCodeAt(i++);

                        e1 = c1 >> 2;
                        e2 = ((c1 & 0x3) << 4) | (c2 >> 4);
                        e3 = ((c2 & 0xf) << 2) | (c3 >> 6);
                        e4 = c3 & 0x3f;

                        if (isNaN(c2)) {
                            e3 = e4 = 64;
                        }
                        else
                            if (isNaN(c3)) {
                                e4 = 64;
                            }

                        output += charset.charAt(e1);
                        output += charset.charAt(e2);
                        output += charset.charAt(e3);
                        output += charset.charAt(e4);

                    }

                    return output;

                },


                decode: function(data, urlsafe) {
                var charset = urlsafe ? Criptografia.charset_urlsafe : Criptografia.charset;

                        var i = 0;
                        var len = data.length;
                        var output = '';

                        var e1, e2, e3, e4;
                        var c1, c2, c3;

                        while (i < len) {
                            e1 = charset.indexOf(data.charAt(i++));
                            e2 = charset.indexOf(data.charAt(i++));
                            e3 = charset.indexOf(data.charAt(i++));
                            e4 = charset.indexOf(data.charAt(i++));

                            c1 = (e1 << 2) | (e2 >> 4);
                            c2 = ((e2 & 0xf) << 4) | (e3 >> 2);
                            c3 = ((e3 & 0x3) << 6) | e4;

                            output += String.fromCharCode(c1);
                            if (e3 != 64) output += String.fromCharCode(c2);
                            if (e4 != 64) output += String.fromCharCode(c3);
                        }
                    
                    return output;
                    } 
            };