/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    
    /******************Start show hidden filter Date****************************/
    
    $('#formreport-check_spent_on').change(function() {
        if ($(this).is(':checked')) {
            $('#filter_date').css('display', 'inline-block');
            var filter_date = $("#formreport-spent_on option:selected" ).val();
            show_hidden_date(filter_date);
        } else {
            $('#filter_date').css('display', 'none');
            $('#values_spent_on_1').css('display', 'none');
            $('#values_spent_on_2').css('display', 'none');
            $('#values_spent_on').css('display', 'none');
            $('#formreport-spent_on').attr('disabled', 'disabled');
        }
    });
    $("#formreport-spent_on").change(function() {
        var filter_date = $("#formreport-spent_on option:selected" ).val();
        show_hidden_date(filter_date);
    });
    
    function show_hidden_date(filter_date) {
        var value_filter = 0;
        if (filter_date == '=' || filter_date == '>=' || filter_date == '<=') {
            value_filter = 1;
        }
        if (filter_date == '><') {
            value_filter = 2;
        }
        if (filter_date == '>t-' || filter_date == '<t-' || filter_date == '><t-' ||  filter_date == 't-') {
            value_filter = 3;
        }
        if (filter_date == 't' || filter_date == 'ld' || filter_date == 'w' ||  filter_date == 'lw' ||  filter_date == 'l2w' ||  filter_date == 'w' ||  filter_date == 'm' || filter_date == 'y' || filter_date == '!*' || filter_date == '*') {
            value_filter = 4;
        }
        switch(value_filter) {
            case 1:
                show_spent_on_1();
                break;
            case 2:
                show_spent_on_between();
                break;
            case 3:
                show_spent_on_days();
                break;
            case 4:
                hidden_spent_on();
                break;
            default:
                
        };
    }
    function show_spent_on_1(){
        $('#values_spent_on_1').css('display', 'inline-block');
        $('#values_spent_on_2').css('display', 'none');
        $('#values_spent_on').css('display', 'none');
        
        $('#formreport-values_spent_on_1').removeAttr('disabled');
        $('#formreport-values_spent_on_2').attr('disabled', 'disabled');
        $('#formreport-values_spent_on').attr('disabled', 'disabled');
    }
    
    function show_spent_on_between() {
        $('#values_spent_on_1').css('display', 'inline-block');
        $('#values_spent_on_2').css('display', 'inline-block');
        $('#values_spent_on').css('display', 'none');
        
        $('#formreport-values_spent_on_1').removeAttr('disabled');
        $('#formreport-values_spent_on_2').removeAttr('disabled');
        $('#formreport-values_spent_on').attr('disabled', 'disabled');
    }
    
    function show_spent_on_days() {
        $('#values_spent_on_1').css('display', 'none');
        $('#values_spent_on_2').css('display', 'none');
        $('#values_spent_on').css('display', 'inline-block');
        
        $('#formreport-values_spent_on_1').attr('disabled', 'disabled');
        $('#formreport-values_spent_on_2').attr('disabled', 'disabled');
        $('#formreport-values_spent_on').removeAttr('disabled');
    }
    
    function hidden_spent_on() {
        $('#values_spent_on_1').css('display', 'none');
        $('#values_spent_on_2').css('display', 'none');
        $('#values_spent_on').css('display', 'none');
        
        $('#formreport-values_spent_on_1').attr('disabled', 'disabled');
        $('#formreport-values_spent_on_2').attr('disabled', 'disabled');
        $('#formreport-values_spent_on').attr('disabled', 'disabled');
    }
    /******************End show hidden filter Date****************************/
    
    /******************Start show hidden filter User**************************/
    $('#formreport-cb_user_id').change(function() {
        if ($(this).is(':checked')) {
            $('#filter_user_id').css('display', 'inline-block');
            $('#user_id').css('display', 'inline-block');
            var filter_user = $("#formreport-filter_user_id option:selected" ).val();
            show_hidde_user(filter_user);
        } else {
            $('#filter_user_id').css('display', 'none');
            $('#user_id').css('display', 'none');
            $('#formreport-filter_user_id').attr('disabled', 'disabled');
        }
    });
    
    $("#formreport-filter_user_id").change(function() {
        var filter_user = $("#formreport-filter_user_id option:selected" ).val();
        show_hidde_user(filter_user);
    });
    
    function show_hidde_user(filter_user) {
        var value_filter = 0;
        if (filter_user == '=' || filter_user == '!') {
            value_filter = 1;
        }
        if (filter_user == '!*' || filter_user == '*') {
            value_filter = 2;
        }
        switch(value_filter) {
            case 1:
                show_filter_user();
                break;
            case 2:
                hidden_filter_user();
                break;
            default:
                
        };
    }
    
    function hidden_filter_user() {
        $('#user_id').css('display', 'none');
        $('#formreport-user_id').attr('disabled', 'disabled');
    }
    function show_filter_user() {
        $('#user_id').css('display', 'inline-block');
        $('#formreport-user_id').removeAttr('disabled');
    }
    
    /********************End show hidden filter User**************************/
    
    /******************Start show hidden filter comment***********************/
    $('#formreport-cb_comments').change(function() {
        if ($(this).is(':checked')) {
            $('#filter_cb_comments').css('display', 'inline-block');
            $('#value_comments').css('display', 'inline-block');
            var filter_comments = $("#formreport-filter_cb_comments option:selected" ).val();
            show_hidde_comments(filter_comments);
        } else {
            $('#filter_cb_comments').css('display', 'none');
            $('#value_comments').css('display', 'none');
            $('#formreport-filter_cb_comments').attr('disabled', 'disabled');
        }
    });
    
    
    $("#formreport-filter_cb_comments").change(function() {
        var filter_comments = $("#formreport-filter_cb_comments option:selected" ).val();
        show_hidde_comments(filter_comments);
    });
    
    function show_hidde_comments(filter_comments) {
        var value_filter = 0;
        if (filter_comments == '~' || filter_comments == '!~') {
            value_filter = 1;
        }
        if (filter_comments == '!*' || filter_comments == '*') {
            value_filter = 2;
        }
        switch(value_filter) {
            case 1:
                show_filter_comments();
                break;
            case 2:
                hidden_filter_comments();
                break;
            default:
                
        };
    }
    function show_filter_comments() {
        $('#formreport-comments').css('display', 'inline-block');
        $('#formreport-comments').removeAttr('disabled');
    }
    function hidden_filter_comments() {
        $('#formreport-comments').css('display', 'none');
        $('#formreport-comments').attr('disabled', 'disabled');
    }
    
    /******************End show hidden filter comment***********************/
    
    /******************Start show hidden filter Hours***********************/
    $('#formreport-cb_hours').change(function() {
        if ($(this).is(':checked')) {
            $('#filter_cb_hours').css('display', 'inline-block');
            $('#values_hours').css('display', 'inline-block');
            var filter_hours = $("#formreport-filter_cb_hours option:selected" ).val();
            show_hidde_hours(filter_hours);
        } else {
            $('#filter_cb_hours').css('display', 'none');
            $('#values_hours').css('display', 'none');
            $('#formreport-filter_cb_hours').attr('disabled', 'disabled');
        }
    });
    
    $("#formreport-filter_cb_hours").change(function() {
        var filter_hours = $("#formreport-filter_cb_hours option:selected" ).val();
        show_hidde_hours(filter_hours);
    });
    
    function show_hidde_hours(filter_hours) {
        var value_filter = 0;
        if (filter_hours == '=' || filter_hours == '>=' || filter_hours == '<=') {
            value_filter = 1;
        }
        if (filter_hours == '><') {
            value_filter = 2;
        }
        if (filter_hours == '!*' || filter_hours == '*') {
            value_filter = 3;
        }
        switch(value_filter) {
            case 1:
                show_filter_hours();
                break;
            case 2:
                show_filter_hours_between();
                break;
            case 3:
                hidden_filter_hours();
                break;
            default:
                
        };
    }
    function show_filter_hours() {
        $('#values_hours_1').css('display', 'inline-block');
        $('#values_hours_2').css('display', 'none');
        $('#values_hours_1').removeAttr('disabled');
        $('#values_hours_2').attr('disabled', 'disabled');
    }
    function show_filter_hours_between() {
        $('#values_hours_1').css('display', 'inline-block');
        $('#values_hours_2').css('display', 'inline-block');
        $('#values_hours_1').removeAttr('disabled');
        $('#values_hours_2').removeAttr('disabled');
    }
    
    function hidden_filter_hours() {
        $('#values_hours_1').css('display', 'none');
        $('#values_hours_2').css('display', 'none');
        $('#values_hours_1').attr('disabled', 'disabled');
        $('#values_hours_2').attr('disabled', 'disabled');
    }
    
    /******************End show hidden filter hours***********************/
    
    /******************Start show hidden filter Activity******************/
    
    $('#formreport-cb_activity_id').change(function() {
        if ($(this).is(':checked')) {
            $('#filter_activity_id').css('display', 'inline-block');
            $('#value_activity_id').css('display', 'inline-block');
            $('#formreport-filter_cb_hours').removeAttr('disabled');
            $('#formreport-activity_id').removeAttr('disabled');
        } else {
            $('#filter_activity_id').css('display', 'none');
            $('#value_activity_id').css('display', 'none');
            $('#formreport-filter_cb_hours').attr('disabled', 'disabled');
            $('#formreport-activity_id').attr('disabled', 'disabled');
        }
    });
    /******************End show hidden filter Activity********************/
})
