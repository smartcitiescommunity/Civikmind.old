(function ($) {
    $.fn.surveyticket = function (options) {

        var object = this;
        init();

        /** 
         * Start the plugin
         */
        function init() {
            object.params = new Array();
            object.params['root_doc'] = '';

            if (options != undefined) {
                $.each(options, function (index, val) {
                    if (val != undefined && val != null) {
                        object.params[index] = val;
                    }
                });
            }
        }

        /** 
         * Add elements to ticket or central
         */
        this.addelements = function () {
            $(document).ready(function () {
                // Get tickets_id
                var tickets_id = object.urlParam(window.location.href, 'id');
                // CENTRAL
                if (location.pathname.indexOf('ticket.form.php') > 0 && !object.isIE()) {
                    // Launched on each complete Ajax load 
                    $(document).ajaxComplete(function (event, xhr, option) {
                        // We execute the code only if the central form display request is done 
                        if ((option.url.indexOf('common.tabs.php') > 0)) {
                            // Delay the execution (ajax requestcomplete event fired before dom loading)
                            setTimeout(function () {
                                var typeIdElm = $('select[name="type"]');
                                //if new ticket 
                                if (tickets_id == 0 || tickets_id === undefined) {
                                    object.loadSurveyTicket(typeIdElm.val(), 'central');
                                }

                            }, 1000);
                        }
                    }, this);

                    // POST ONLY
                } else if ((window.location.href.indexOf('helpdesk.public.php?create_ticket=1') > 0 ||
                              window.location.href.indexOf('tracking.injector.php') > 0)
                  || object.isIE()) {
                    if($('input[name="_from_helpdesk"]').val()){
                        var typeIdElm = $('select[name="type"]');
                        if (tickets_id == 0 || tickets_id == undefined) {
                            object.loadSurveyTicket(typeIdElm.val(), 'helpdesk');
                        }
                    } else {
                        var typeIdElm = $("select").filter(function() { return this.name == 'type'; });
                        setTimeout(function () {
                            if (tickets_id == 0 || tickets_id == undefined) {
                                object.loadSurveyTicket(typeIdElm.val(), 'central');
                            }
                        }, 1000);
                    }
                } 
            });
        };


        /** 
         * add surveyticket in the block content
         * 
         * @param string tickets_id
         */
        this.loadSurveyTicket = function (type, interface) {
            var root_doc = object.params['root_doc'];
            var descriptionIdElm = $("textarea").filter(function() { return this.name == 'content'; });
            var itilcategoriesIdElm = $("select").filter(function() { return this.name == 'itilcategories_id'; });
            if(itilcategoriesIdElm.val() === undefined){
                var itilcategoriesIdElm = $("input").filter(function() { return this.name == 'itilcategories_id'; });
            }
            var entities_id = $("input").filter(function() { return this.name == 'entities_id'; });
            $.ajax({
                url: root_doc + '/plugins/surveyticket/ajax/ticket.php',
                type: "POST",
                dataType: "json",
                data: {
                    action: 'loadSurveyTicket',
                    type: type,
                    itilcategories_id: (itilcategoriesIdElm.length != 0) ? itilcategoriesIdElm.val() : '0',
                    entities_id: (entities_id.length != 0) ? entities_id.val() : '0',
                    interface : interface,
                },
                success: function (json) {
                    if (json.response) {
                        descriptionIdElm.hide();
                        // Append surveyticket link after category
                        descriptionIdElm.parent().html(json.survey);
                    } else {
                        //no survey
                        descriptionIdElm.show();
                    }

                }
            });

        };

        /** 
         * Get url parameter
         * 
         * @param string url
         * @param string name
         */
        this.urlParam = function (url, name) {
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
            if (results != null) {
                return results[1] || 0;
            }
        };

        /** 
         * Is IE navigator
         */
        this.isIE = function () {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer, return version number
                return true;

            return false;
        };

        return this;
    }
}(jQuery));
