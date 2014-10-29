/**
 * Created by sparx on 28/10/14.
 */


var Base = function(){
    var username = $.cookie("username");
    if(username){
        this.poster = username;
    }
};

Base.prototype.poster = "";


Base.prototype.submitForm = function($form, _callback){
    var username = $form.find('input[name=poster]').val();
    this.poster = username;
    $.cookie("username", username);

    $form.ajax(_callback);
};



Base.prototype.ajax = function(url, data, _callback) {

    var self = this;
    var result = null;
    var error = null;

    $.ajax({
        url: url,
        data: data,
        type: "POST",
        dataType: "json",

        success: function (json) {
            result = json;
        },

        error: function (xhr, status, errorThrown) {
            error = errorThrown;
        },

        complete: function (xhr, status) {
            _callback(error, result);
        }
    });

};


Base.prototype.formatDate = function(timestamp){

    var delta = ((new Date().getTime()) / 1000) - timestamp;
    var day = 60 * 60 * 24;
    var hour = 60 * 60;
    var minute = 60;

    //return delta;

    if(delta > day){
        var t = Math.floor(delta / day);
        return t > 1 ? t + " days ago" : t + " day ago";
    }else if (delta > hour) {
        var t = Math.floor(delta / hour);
        return t > 1 ? t + " hours ago" : t + " hour ago";
    }else if ( delta > minute) {
        var t = Math.floor(delta / minute);
        return t > 1 ? t + " minutes ago" : t + " minute ago";
    }else{
        var t = Math.floor(delta);
        return t > 1 ? t + " seconds ago" : t + " second ago";
    }

};