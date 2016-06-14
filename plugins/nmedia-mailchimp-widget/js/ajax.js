// JavaScript Document
jQuery(function($){
	
	$('.nm_mc_form').find('ul').css({'margin':0,'padding':0});
	$('.nm_mc_form').find('ul li').css({'list-style':'none','border':'none','margin':0,'padding':0});
});

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}



function postToMailChimp(frm)
{
	//console.log(frm);
	
	jQuery(frm).find("#nm-mc-loading").show();
	
	var data = jQuery(frm).serialize();
	data = data + '&action=nm_mailchimp_subscribe';
	
	//console.log(data);	
		
	jQuery.post(mailchimp_vars.ajaxurl, data, function(resp){
		
			jQuery(frm).find("#nm-mc-loading").hide();
			
			//console.log(resp);
			if(resp.status == 'failed'){
				jQuery(frm).find("#mc-response-area").html(resp.message);				
			}else if(resp.status == 'success' && mailchimp_vars.redirect_to != '')
			{
				window.location = mailchimp_vars.redirect_to;
			}
			else
			{
				jQuery(frm).find("#nm-mc-loading").hide();
				jQuery(frm).find("#mc-response-area").html(resp.message);
				jQuery(frm).find("input[type=text]").val("");
				jQuery(frm).find("input:checkbox").attr("checked", '');
			}
	},'json');
	
	return false;
	
}

function php_serialize(obj)
{
    var string = '';

    if (typeof(obj) == 'object') {
        if (obj instanceof Array) {
            string = 'a:';
            tmpstring = '';
            count = 0;
            for (var key in obj) {
                tmpstring += php_serialize(key);
                tmpstring += php_serialize(obj[key]);
                count++;
            }
            string += count + ':{';
            string += tmpstring;
            string += '}';
        } else if (obj instanceof Object) {
            classname = obj.toString();

            if (classname == '[object Object]') {
                classname = 'StdClass';
            }

            string = 'O:' + classname.length + ':"' + classname + '":';
            tmpstring = '';
            count = 0;
            for (var key in obj) {
                tmpstring += php_serialize(key);
                if (obj[key]) {
                    tmpstring += php_serialize(obj[key]);
                } else {
                    tmpstring += php_serialize('');
                }
                count++;
            }
            string += count + ':{' + tmpstring + '}';
        }
    } else {
        switch (typeof(obj)) {
            case 'number':
                if (obj - Math.floor(obj) != 0) {
                    string += 'd:' + obj + ';';
                } else {
                    string += 'i:' + obj + ';';
                }
                break;
            case 'string':
                string += 's:' + obj.length + ':"' + obj + '";';
                break;
            case 'boolean':
                if (obj) {
                    string += 'b:1;';
                } else {
                    string += 'b:0;';
                }
                break;
        }
    }

    return string;
}