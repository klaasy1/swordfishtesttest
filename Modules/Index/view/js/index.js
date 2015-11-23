function index() {
    $('form').unbind('submit');
    $('form').submit(function(e) {
        e.preventDefault();
        submitForm(this);
    });
}

function checkLinks(container) {
    if (container === undefined) {
        container = 'body';
    }
    if ($(container + ' a').length > 0) {
        $(container + ' a').unbind('click');
        $(container + ' a').bind('click', function (event) {
            if ($(this).attr('ignore') != 1) {
                if ($(this).attr('href') != '#' && $(this).attr('href') !== undefined) {
                    
                    var url = $(this).attr('href');
                    var target = $(this).attr('target');
                    if (target == '_blank') {
                        $(this).unbind();
                        return;
                    }
                    event.preventDefault();
                    if (target) {
                        target = target.replace('#', '*');
                    } else {
                        target = '';
                    }
                    $.ajax({
                        url: url,
                        success: function(data) {
                            $('#dynamicContent').html(data)
                        }
                    });
                }
            }
        });
    }
}

function ajaxSetup() {
    
    $.ajaxSetup({
        beforeSend: function() {
            $("body").append("<div class='loaderContainer' style='position:relative;z-index:10000'><div id='loader' style='width:100%; position:fixed; left:0px; top:0px; display:none; height:100%; background-color: rgba(250, 250, 250, 0.5);'></div></div>");
            $("#loader").fadeIn(200,function() {
                $.fn.spin.presets.flower = 
                $("#loader").spin({
                    'lines': 10, // The number of lines to draw
                    'length': 0, // The length of each line
                    'width': 3, // The line thickness
                    'radius': 10, // The radius of the inner circle
                    'corners': 1, // Corner roundness (0..1)
                    'rotate': 27, // The rotation offset
                    'direction': 1, // 1: clockwise, -1: counterclockwise
                    'color': '#444444', // #rgb or #rrggbb or array of colors
                    'speed': 1.0, // Rounds per second
                    'trail': 30, // Afterglow percentage
                    'shadow': true, // Whether to render a shadow
                    'hwaccel': true, // Whether to use hardware acceleration
                    'className': 'spinner', // The CSS class to assign to the spinner
                    'zIndex': 2e9, // The z-index (defaults to 2000000000)
                    'top': 'auto', // Top position relative to parent in px
                    'left': 'auto' // Left position relative to parent in px
                });                
            });
        },
        complete: function() {
            $("#loader").fadeOut(200,function() {
                $(".loaderContainer").remove();
            });
            index();
            checkLinks('body');
        }
    });
    
}

function setIssueNumber(){
    $("#setIssueNumberLink").attr('href', "Issue/render/"+$('#issuesPerPage').val()+"");
}

$(document).ready(function() {
    index();
    checkLinks('body');
    ajaxSetup();
});