function sendAjax() {
    var date = $('#date').val();
    var options = $('#options').val();
    var chart = $("#sgraph").highcharts();
        if(chart != undefined ){
            $("#sgraph").highcharts().destroy();
        }
    $(".ui-dialog").remove();
    var btn = $("#query");
    get5AjaxData(date,options,btn);
    getRankingFromDb(date);
}

$(function () {
    var dateFormat = "DD-MM-YYYY";
    var MinDate = "07-12-2018";

    dateMin = moment(MinDate, dateFormat);
    $('#datetimepicker1').datetimepicker({
    icons: {
        time: "far fa-clock text-success",
        date: "fa fa-calendar text-success",
    },
        format: 'YYYY-MM-DD',
        timeZone: 'Asia/Bangkok',
        minDate: dateMin,
        showTodayButton: true
    });

});

function get5AjaxData(date,options,btn) {
    var qs = {date:date,options:options};
    $.ajax({
        url: "asset/ajax/graph.php",
        type: "post",
        data: qs,
        beforeSend: function(){
            btn.prop('disabled', true);
            $("#loader").fadeIn();
        },
        complete: function(){
          btn.prop('disabled', false);
          $("#loader").hide();
        },
        success: function (response) {
            $("#sgraph").append(response);
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
}

$( document ).ready(function() {
const datetimepickerinit = {
    defaultDate: moment(),
    format: 'YYYY-MM-DD'
};
$('#date').datetimepicker(datetimepickerinit);
sendAjax();
});

function getRankingFromDb(date) {
var qs = {date:date};
$.ajax({ 
    url: "asset/php/getData.php" ,
    type: "POST",
    data: qs,
})
.done(function(result) {
    console.log(result)
    var obj = jQuery.parseJSON(result);
        if(obj != '') {
        $("#tableyoutube").empty();
        $.each(obj, function(key, val) {
            var tr = "<tr>";
            tr = tr + "<td id=\"Dynamictable\">" + val["name"] + "</td>";
            tr = tr + "<td id=\"Dynamictable\">" + val["views"] + "</td>";
            tr = tr + "<td id=\"Dynamictable\">" + val["likes"] + "</td>";
            tr = tr + "<td id=\"Dynamictable\">" + val["dislike"] + "</td>";
            tr = tr + "<td id=\"Dynamictable\">" + val["comment"] + "</td>";
            tr = tr + "<td id=\"Dynamictable\">" + val["date"] + " " + val["time"] + "</td>";
            tr = tr + "</tr>";
            $('#tableyoutube').append(tr);
        });
    }
    var $rows = $('table tr');
    var lastActiveIndex = $rows.filter('.active:last').index();
    $rows.filter(':lt(' + (lastActiveIndex + 5) + ')').addClass('active');
});
}