/**
 * Created by sparx on 28/10/14.
 */

var Blog = function(){
    this.init();
};

/**
 * inherit Super Class Base
 * @type {Base}
 */
Blog.prototype = new Base();
Blog.prototype.constructor = Blog;

/**
 * init all blog page elements
 */
Blog.prototype.init = function(){

    var self = this;

    self.initForm();
    self.loadMessages();
    self.initFormEvents();
    self.initMessageLoading();
    self.initTimeDisplayTimer();
};

/**
 *
 */
Blog.prototype.initForm = function(){

    var self = this;
    $('form#message').find('input[name=poster]').val(self.poster);
};

/**
 *
 */
Blog.prototype.initFormEvents = function(){

    var self = this;
    var $form = $('form#message');

    $form.bind('submit', function(event){

        clearInterval(self.messageTimer);

        event.preventDefault();
        self.submitForm($form, function(){
            self.initForm();
            self.loadMessages();
            self.initMessageLoading();
        });
        return false;
    });

};

/**
 *
 */
Blog.prototype.initMessageLoading = function(){

    var self = this;

    self.messageTimer = window.setInterval(function(){
        self.loadMessages();
    }, 10000);

};

/**
 *
 */
Blog.prototype.initTimeDisplayTimer = function(){

    var self = this;

    window.setInterval(function(){
        self.refreshTimeDisplay();
    }, 1000);
};

/**
 *
 */
Blog.prototype.loadMessages = function(){

    var self = this;
    var $lastMessage = $('div.message:last-child');
    var sec = $lastMessage.attr('data-sec');
    var usec = $lastMessage.attr('data-usec');

    if(!sec){
        sec = 0;
    }
    if(!usec){
        usec = 0;
    }

    self.ajax('/blog/getMessages', {sec: sec, usec: usec}, function(error, data) {

        if(error){
            //Todo: handle error
        }
        if (data) {
            data.forEach(function (message) {

                var $html = $('div#templates div.message').clone();

                $html.attr('id', message._id.$id);
                $html.attr('data-sec', message.created.sec);
                $html.attr('data-usec', message.created.usec);
                $html.find("span.poster").text(message.poster);
                $html.find("span.time").text(self.formatDate(message.created.sec));
                $html.find("div.messagetext p").html(message.text.replace(/(\n)+/g, '</p><p>'));

                $('div.messages').append($html);
            });
        }
    });
};

/**
 *
 */
Blog.prototype.refreshTimeDisplay = function(){

    var self = this;

    $('div.messages div.message').each(function(){
        var timestamp = $(this).attr('data-sec');
        $(this).find("span.time").text(self.formatDate(timestamp));
    });
};


