/**
 * Created by sparx on 28/10/14.
 */


( function ($) {

    $.fn.ajax = function (_callback) {

        var form = this;
        var data = form.serialize();
        var url = form.attr('action');

        var result = null;
        var error = null;

        if(!form.attr('slock') || form.attr('slock') === 'false') {

            //blocking the form from being send again, until the current request is finished
            form.attr('slock', 'true');

            //todo validation

            $.ajax({
                url: url,
                data: data,
                type: "POST",
                dataType: "json",

                success: function (json) {
                    //todo: handle response
                    result = json;
                },

                error: function (xhr, status, errorThrown) {
                    error = errorThrown;
                    alert("Sorry, there was a problem!");
                },

                complete: function (xhr, status) {
                    form.attr('slock', 'false');
                    form[0].reset();

                    _callback && _callback(error, result);
                }
            });
        }
    };

}(jQuery));