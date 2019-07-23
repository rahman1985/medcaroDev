"use strict";
    function fetchdata(){
        $.ajax({
            url: scripts_vars.theme_path_uri+'/fetch_details.php',
            type: 'post',
            success: function(data){
                //console.log(data);
                if(data!="" && data != undefined){
                    var result = JSON.parse(data);
                    if(result.msg != "" && result.msg != undefined) {
                        $.notify({
                            // options
                            icon: result.icon,
                            title: result.title,
                            message: result.msg,
                        },{
                            // settings
                            element: 'body',
                            position: 'fixed',
                            type: 'info',
                            allow_dismiss: true,
                            newest_on_top: false,
                            showProgressbar: false,
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1031,
                            delay: 8000,
                            timer: 1000,
                            mouse_over: 'pause',
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                            onShow: null,
                            onShown: null,
                            onClose: null,
                            onClosed: updateData,
                            icon_type: 'class',
                            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                '<span data-notify="icon"></span> ' +
                                '<span data-notify="title">{1}</span> <br>' +
                                '<span data-notify="message">{2}</span>' +                          
                            '</div>' 
                        });
                    }
                }
            },
            complete:function(data){
                setTimeout(fetchdata,10000);
            }
        });
    }
    jQuery(document).ready(function ($) {
        setTimeout(fetchdata,10000);
    });

    function updateData(){
        $.ajax({
            url: scripts_vars.theme_path_uri+'/update_details.php',
            type: 'post',
            success: function(data){},
        });
    }
