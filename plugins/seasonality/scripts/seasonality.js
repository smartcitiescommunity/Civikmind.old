(function ($) {
    $.fn.seasonality = function (options) {

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
//            object.initValues = new Array();
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
                        if (option.url != undefined && (option.url.indexOf('common.tabs.php') > 0)) {
                            // Delay the execution (ajax requestcomplete event fired before dom loading)
                            setTimeout(function () {
                                var itilcategoriesIdElm = $('select[name="itilcategories_id"], input[name="itilcategories_id"]');

                                // Unbind existing on_change
                                if (tickets_id == 0 || tickets_id == undefined){
                                    itilcategoriesIdElm.off( "change" );
                                }
                                
//                                object.saveCurrentTicket();
                                object.loadUrgency(tickets_id, false);

                                // On change itilcategory
                                itilcategoriesIdElm.on('change', function () {
                                    object.loadUrgency(tickets_id, true);
                                });
                            }, 100);
                        }
                    }, this);
                
                // POST ONLY
                } else if (location.pathname.indexOf('helpdesk.public.php') > 0 || location.pathname.indexOf('tracking.injector.php') > 0 || object.isIE()){
                    var itilcategoriesIdElm = $('select[name="itilcategories_id"], input[name="itilcategories_id"]');

                    // Unbind existing on_change
                    if (tickets_id == 0 || tickets_id == undefined){
                        itilcategoriesIdElm.off( "change" );
                    }
                    
//                    object.saveCurrentTicket();
                    object.loadUrgency(tickets_id, false);
                    
                    // On change itilcategory
                    itilcategoriesIdElm.on('change', function () {
                        object.loadUrgency(tickets_id, true);
                    });
                }
            });
        }
        
//        /** 
//         * Save curent ticket
//         */
//        this.saveCurrentTicket = function (){
//            // Save current values
//            object.initValues['urgency']  = $("select[name='urgency'], input[name='urgency']").val();
//            object.initValues['impact']   = $("select[name='impact'], input[name='impact']").val()
//            object.initValues['priority'] = $("select[name='priority'], input[name='priority']").val();
//        }
//        
        /** 
         * Restore curent ticket
         */
        this.restoreCurrentTicket = function (json){
            // restore current values
            $("select[name='urgency'], input[name='urgency']").select2("val", json.default_urgency);
            $('select[name="impact"], input[name="impact"]').select2("val", json.default_impact);
            $('select[name="priority"], input[name="priority"]').select2("val", json.default_priority);
        }
        
        /** 
         * Load urgency
         * 
         * @param string tickets_id
         */
        this.loadUrgency = function (tickets_id, reload){
            var root_doc            = object.params['root_doc'];
            var itilcategoriesIdElm = $("select[name='itilcategories_id'], input[name='itilcategories_id']");
            var date                = $("input[name='date']");
            var urgence_block       = $("select[name='urgency'], input[name='urgency']");
            var type                = $("select[name='type'], input[name='type']");
            var entities_id         = $("input[name='entities_id']");
 
            if (urgence_block.length != 0) {
                $.ajax({
                    url: root_doc + '/plugins/seasonality/ajax/ticket.php',
                    type: "POST",
                    dataType: "json",
                    data: {
                        action            : 'changeUrgency',
                        itilcategories_id : (itilcategoriesIdElm.length != 0) ? itilcategoriesIdElm.val() : '0',
                        date              : (date.length != 0) ? date.val() : '0',
                        tickets_id        : tickets_id,
                        type              : (type.length != 0) ? type.val() : '1',
                        entities_id       : (entities_id.length != 0) ? entities_id.val() : '0',
                    },
                    success: function (json, opts) {
                        if ($('#seasonalities_link').length != 0) {
                            $('#seasonalities_link').remove();
                        }

                        if (!json.error) {
                            var priorityElm = $('select[name="priority"], input[name="priority"]');
                            var impactElm = $('select[name="impact"], input[name="impact"]');

                            // Update urgency
                            urgence_block.val(json.urgency_id);
                            urgence_block.select2("val", json.urgency_id);
                            var requesterText = urgence_block[0].nextSibling;
                            if (requesterText.nodeValue != null) {
                                $(requesterText).remove();
                                urgence_block.parent().append(json.urgency_name);
                            }

                            // Append seasonality link after category
                            itilcategoriesIdElm.parent().append(json.seasonalities_link);

                            // Update priority
                            $.ajax({
                                url: root_doc + '/ajax/priority.php',
                                type: "POST",
                                dataType: "html",
                                data: {
                                    urgency: json.urgency_id,
                                    impact: impactElm.val(),
                                    priority: (priorityElm.length != 0) ? priorityElm.attr('id') : '0'
                                },
                                success: function (response, opts) {
                                    $('span[id^="change_priority_"]').html(response);
                                    if ((tickets_id == 0 || tickets_id == undefined) && reload) {
                                        $('form[name="form_ticket"], form[name="helpdeskform"]').submit();
                                    }
                                }
                            });
                        } else {
                            if (!json.template) {
                                object.restoreCurrentTicket(json);
                            }
                            if ((tickets_id == 0 || tickets_id == undefined) && reload) {
                                $('form[name="form_ticket"], form[name="helpdeskform"]').submit();
                            }
                        }
                    }
                });
            }
        }

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
        }
        
        /** 
         * Is IE navigator
         */
        this.isIE = function () {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer, return version number
                return true;

            return false;
        }

        return this;
    }
}(jQuery));
