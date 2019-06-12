/**
 * Created by eddy on 27/3/2016.
 */
$(document).ready(function () {
    var calendarBG = $("#bg").calendarPicker({
        monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        dayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        useWheel: true,
        callbackDelay: 500,
        years: 1,
        months: 6,
        days: 9,
        showDayArrows: true,
        callback: function (cal) {
            $.ajax({
                Type: 'GET',
                url: 'http://localhost/calendar/php/data.php',
                data: {fulldate: cal.currentDate},
                success: function (datos) {
                    var datos = jQuery.parseJSON(datos);
                    var html = '<table class="table table-bordered table-hover">' +
                        '<tr class="table-head">' +
                        ' <td colspan="3">Partidos</td>' +
                        '</tr>';
                    for (var i = 0; i < datos[0]['count']; i++) {
                        var html = html + '<tr>' +
                            '<td style="width: 50px"><i class="fa fa-play-circle fa-2x"></i></td>' +
                            '<td style="width: 50px; padding-top: 10px">' + datos[i]['start_date'] + '</td>' +
                            '<td style="padding-top: 5px">  ' + datos[i]['home'] + '   <b>' + datos[i]['home_score'] + ' ' +
                            '<img class="fa-3x img-circle img-thumbnail" style="height: 30px; height: 30px" src="' + datos[i]['logo_home'] + '">  ' +
                            ': <img class="fa-3x img-circle img-thumbnail" style="height: 30px; height: 30px" src="' + datos[i]['logo'] + '">  ' +
                            ' ' + datos[i]['away_score'] + '</b> ' + datos[i]['away'] + '' +
                            '</td></tr>';
                    }
                    var html = html + '</table>';
                    $('#home').html(html);
                    $('#profile').html(html);
                    $('#messages').html(html);
                },
                error: function () {
                    alert("Error leyendo fichero json");
                }
            });
        }
    });
});