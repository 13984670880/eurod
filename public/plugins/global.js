// JavaScript Document
function trim(str, charlist) {
	var whitespace, l = 0,
    i = 0;
    str += '';
 
    if (!charlist) {        // default list
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } 
	else{
        charlist += '';        
		whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    }
    l = str.length;
    for (i = 0; i < l; i++) {
		if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    } 
    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);            
			break;
        }
    }
    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

function is_mail(email) 
{
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   	if(reg.test(email) == false) {return false;}
   	else {return true;}
}

function is_url(s) 
{
	var reg = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
	return reg.test(s);
}

function is_dec_lat_lon(s)
{
	var reg = /^[-+]?([0-9]+\.[0-9]{6})$/;
	return reg.test(s);
}

function is_num(s)
{
	var reg = /^[0-9]+$/;
	return reg.test(s);	
}

function is_code_postal5(s)
{
	var reg = /^[0-9]{5}$/;
	return reg.test(s);		
}

function encode_utf8( s )
{
	return unescape( encodeURIComponent( s ) );
}

function decode_utf8( s )
{
	return decodeURIComponent( escape( s ) );
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function getZendPageNumber(url)
{
    var vars = [], hash;
    var hashes = url.split('/');
	return hashes[hashes.length-1];
}