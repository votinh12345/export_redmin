/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    $("#formreport-spent_on").change(function() {
        var ($("#formreport-spent_on option:selected" ).val());
        alert($("#formreport-spent_on option:selected" ).val());
    });
})
