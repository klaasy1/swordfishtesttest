function submitForm(form) {
    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: $(form).serialize(),
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                $.gritter.add({
                    title: 'Success!',
                    text: data.message
                });
                
                if (data.redirect) {
                    $.ajax({
                        url: data.redirect,
                        success: function(data) {
                            $("dynamicContent").html(data);
                        }
                    });
                }
            } else {
                $(data.errors).each(function(i,error) {
                    $.gritter.add({
                        title: 'Error!',
                        text: error
                    });
                });
            }
            
        },
        error: function (request, status, error) {
            alert(request.responseText);
        }
    });
}