/* JavaScript functions */

/* get a given URL parameter */
function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

/* validation functions */

/* presence */

function hasValue(element_id) {
    var input_element=document.getElementById(element_id);
    /*trim leading and trailing spaces*/
    input_element.value = input_element.value.replace(/^\s*|\s*$/g, '');
    if (input_element.value==null || input_element.value=="") {
        var error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" should be filled.";
        return error_message;
    } else {
        return true;
    }
}

function hasFile(element_id) {
    var input_element=document.getElementById(element_id);
    /* trim leading and trailing spaces */
    if (input_element.files.length == 0) {
        var error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" no file chosen.";
        return error_message;
    } else {
        return true;
    }
}

/* numeric */
function isNumber(element_id) {
    var input_element=document.getElementById(element_id);
    var regex_pattern = /^[\-\+]?[\d\,]*\.?[\d]*$/;
    if (!regex_pattern.test(input_element.value) && input_element.value.length > 0) {
        var error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" should be numeric.";
        return error_message;
    } else {
        return true;
    }
}

/* email */
function isEmail(element_id) {
    var input_element=document.getElementById(element_id);
    var regex_pattern = /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i;
    if (!regex_pattern.test(input_element.value) && input_element.value.length > 0) {
        var error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" has invalid email.";
        return error_message;
    } else {
        return true;
    }
}

function isDBDate(element_id) {
    var isvalid;
    var error_message;
    var input_element=document.getElementById(element_id);
    var regex_pattern = /^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$/;
    if (!regex_pattern.test(input_element.value) && input_element.value.length > 0) {
        isvalid = false;
    } else {
        var date_array = input_element.value.split("-");
        var yyyy = date_array[0];
        var mm = date_array[1];
        var dd = date_array[2];
        /* At this point, yyyy holds the year, mm the month && dd the day of the date entered*/
        if (dd == 31 && (mm == 4 || mm == 6 || mm == 9 || mm == 11)) {
            isvalid = false; /* 31st of a month with 30 days */
        } else if (dd >= 30 && mm == 2) {
            isvalid = false; /* February 30th || 31st */
        } else if (mm == 2 && dd == 29 && !(yyyy % 4 == 0 && (yyyy % 100 != 0 || yyyy % 400 == 0))) {
            isvalid = false; /* February 29th outside a leap year */
        } else {
            return true; /* Valid date */
        }
    }
    if(!isvalid) {
        error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" has invalid date.";
        return error_message;
    }
}

/* equality of two input fields */
function equalValues(input_1_id, input_2_id) {
    var input_1=document.getElementById(input_1_id);
    var input_2=document.getElementById(input_2_id);
    if (input_1.value != input_2.value) {
        var error_message = "The passwords do not match.";
        return error_message;
    } else {
        return true;
    }
}

/* don't accepts sql interpreted character like ;#() to avoid injection */
/* for username field: letter, alphabet, special character _&|*! */
function acceptedAlphaNum1 (element_id) {
    var input_element=document.getElementById(element_id);
    var regex_pattern = /^[a-zA-Z0-9_&|*!]*$/;
    if (!regex_pattern.test(input_element.value) && input_element.value.length > 0) {
        var error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" has invalid character(s).";
        return error_message;
    } else {
        return true;
    }
}

/* don't accepts sql interpreted character like ;#() to avoid injection */
/* for password field: letter, alphabet, space, special character _&|*! */
function acceptedAlphaNum2 (element_id) {
    var input_element=document.getElementById(element_id);
    var regex_pattern = /^[a-zA-Z0-9 _&|*!]*$/;
    if (!regex_pattern.test(input_element.value) && input_element.value.length > 0) {
        var error_message = input_element.parentNode.parentNode.getElementsByTagName('label')[0].innerHTML+" has invalid character(s).";
        return error_message;
    } else {
        return true;
    }
}

function validateForm(form_id) {
    var error_message_div=document.getElementById("info_sect");
    var error_messages=[];
    var result;
    var has_error = false;
    var form_elements=[];

    /* presence */
    form_elements = $(".obligatory");
    for (indx=0;indx<form_elements.length;indx++) {
        result = hasValue(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }
    
	/* file required */
	form_elements = $(".file_required");
    for (indx=0;indx<form_elements.length;indx++) {
        result = hasFile(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }
    /* email */
    form_elements = $(".email");
    for (indx=0;indx<form_elements.length;indx++) {
        result = isEmail(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }

    /* fecha */
    form_elements = $(".datefield");
    for (indx=0;indx<form_elements.length;indx++) {
        result = isDBDate(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }

    /* numeric */
    form_elements = $(".numeric");
    for (indx=0;indx<form_elements.length;indx++) {
        result = isNumber(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }

    /* alpahnumeric */
    form_elements = $(".safe-alphanum1");
    for (indx=0;indx<form_elements.length;indx++) {
        result = acceptedAlphaNum1(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }

    form_elements = $(".safe-alphanum2");
    for (indx=0;indx<form_elements.length;indx++) {
        result = acceptedAlphaNum2(form_elements[indx].id);
        if( result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }

    /* equity of two inputs: password */
    form_elements = $(".compare");
    if (form_elements.length == 2) {
        result = equalValues(form_elements[0].id, form_elements[1].id);
        if (result !== true) {
            error_messages.push(result);
            has_error=true;
        }
    }

    if(has_error) {
        div_inner_html="<ul class='form_error_list'>";
        for (indx=0;indx<error_messages.length;indx++){
            div_inner_html += "<li>" + error_messages[indx] + "</li>";
        }
        div_inner_html += "</ul>";
        error_message_div.innerHTML=div_inner_html;
        return false;
    }
}

/* JQuery code */

/*
 window.location.protocol = http:
 window.location.href     = http://www.somedomain.com/account/search?filter=a#top
 window.location.host     = www.somedomain.com (includes port if there is one)
 window.location.hostname = www.somedomain.com
 window.location.port     = (empty string)
 window.location.pathname = /account/search
 window.location.search   = ?filter=a
 window.location.hash     = #top
 */

/* to redirect explicitly in javascript set the location.href */

$(document).ready(function(){

    /* resize of result div to match search input width on mobile and on resize */

    $('#ls_result').outerWidth($('#keyword').outerWidth());

    $(window).resize(function() {
        $('#ls_result').outerWidth($('#keyword').outerWidth());
    });

    /* confirm dialog box before delete. returns true if ok was pressed; false if cancel was pressed
    * if element clicked was a link, you'll be redirected to href value on true */

    /* this method won't work for content generated via AJAX. */
	$('.delete-confirmation').on('click', function () {
        return confirm('Are you sure?');
    });

    /* for AJAX generated content. */
    $(document).on("click",".delete_confirmation_ajax",function(e){
        return confirm("Are you sure?");
    });


    /* Highlight active nav anchor */

	/* for link to different page (redirection) */
    /* var url = window.location.href.split('?')[0];*/
    $('.nav a').filter(function() {
        return this.href == location.protocol + '//' + location.host + location.pathname;
    }).parent().addClass("active").siblings().removeClass('active');
	
	/* for link to section on same page */
	$('.nav a').on("click", function(){
		$(this).parent().addClass('active').siblings().removeClass('active');	
	});
	
    /* Highlight active nav anchor when in new or edit page */

    var currentUrl = location.href;
    $('.nav a').filter(function() {
        return this.href == currentUrl.substring(0,currentUrl.lastIndexOf('/')) + "/";
    }).parent().addClass("active").siblings().removeClass('active');
	
	/* make user info on nav bar inactive */
	$('.not-active').parent().removeClass('active');
	
	/* remove preceding space */
	$('textarea').on('focus', function () {
        $(this).html($(this).html().replace(/^\s*|\s*$/g, ''));
    });
    
    $('#password_confirm').on("change paste keyup", function() {
        var $confirm_span = $('#pwd-confirm-span');
        if ( $(this).val() == $('#password').val() ) {
            $confirm_span.css('color', 'green').html('Passwords match');
        } else {
            $confirm_span.css('color', 'red').html('Passwords do not match');
        }
    });

    $('#user_name').on("change paste keyup", function() {
        var $username_span = $('#username-span');
        if ( acceptedAlphaNum1($(this).attr('id')) === true ) {
            $username_span.css('color', 'green').html('Valid characters');
        } else {
            $username_span.css('color', 'red').html('Invalid character(s)');
        }
    });
	
});
 