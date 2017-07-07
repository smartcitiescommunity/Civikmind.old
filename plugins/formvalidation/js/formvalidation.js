/*
 * -------------------------------------------------------------------------
Form Validation plugin
Copyright (C) 2016 by Raynet SAS a company of A.Raymond Network.

http://www.araymond.com
-------------------------------------------------------------------------

LICENSE

This file is part of Form Validation plugin for GLPI.

This file is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
*/

(function () {
    function glpiURLRoot() {
        var scriptName = "/plugins/formvalidation/js/formvalidation.js";
        var glpiURL = $('script[src$="' + scriptName + '"]')[0].src;
        var pos = glpiURL.search(scriptName);

        return glpiURL.substr(0, pos);
    }

    function getUpDownDOMPath(pathEltSign, pathEltValue) {
        var aEltSign = pathEltSign.split('>');
        var aEltValue = pathEltValue.split('>');
        var ret = {'up' : '', 'down' : ''};
        var found = false;
        var i = 0;
        while (aEltSign[i] === aEltValue[i]) {
            found = true;
            i++;
        }
        if (found) {
            for (var j = 0; j < i; j++) {
                ret.up = (ret.up == '' ? '' : ret.up + '>') + aEltSign[j];
            }
            ret.up = ret.up.replace(/:eq\(\d+\)/g, '');
        }
        for (; i < aEltSign.length; i++) {
            ret.down = (ret.down == '' ? '' : ret.down + '>') + aEltSign[i];
        }
        return ret;
    }

    function getObjFromSelectors(elt, fieldData, eltName) {
        var locElt = elt;
        if( elt.prop('nodeName').toLowerCase() == 'form' ) {
            locElt = elt.find('>' + fieldData.css_selector_value);
        }
        if (fieldData[eltName + '_rel'].up != '') {
            locElt = locElt.parents(fieldData[eltName + '_rel'].up);
        }
        if (fieldData[eltName + '_rel'].down != '') {
            locElt = locElt.find(fieldData[eltName + '_rel'].down);
        }
        return locElt;
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function capitalizeArray(array) {
        function capitalizeItem(item, index) {
            return array[index] = capitalizeFirstLetter(item);
        }
        array.forEach(capitalizeItem);
    }

    //------------------------------------------
    // helper function to verify a if a string 
    // is really a date
    // uses the datapicker JQuery plugin
    //------------------------------------------
    function isValidDate(d) {
        try {
            $.datepicker.parseDate($('.hasDatepicker').datepicker('option', 'dateFormat'), d)
            return true;
        } catch (e) {
            return false;
        }
    }

    //------------------------------------------
    // helper function to verify a if a string 
    // is really a time from 00:00[:00] to 23:59[:59]
    //------------------------------------------
    function isValidTime(str) {
        return /^(?:[0-1]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/.test(str);
    }

    //------------------------------------------
    // helper function to verify a if a string 
    // is really an integer
    //------------------------------------------
    function isValidInteger(str) {
        return /^\d+$/.test(str);
    }

    //------------------------------------------
    // helper function to count words in a given string 
    // returns quantity of words
    //------------------------------------------
    function countWords(str) {        
        return str.split(/\W+/).length;
    }

    //------------------------------------------
    // helper function to verify a if a string 
    // is really an IPV4 address
    // uses the datapicker JQuery plugin
    //------------------------------------------
    function isValidIPv4(ipaddress) {
        return /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress) ;            
    }

    
    //------------------------------------------
    // helper function to verify a if a string 
    // is really an IPV6 address
    // uses the datapicker JQuery plugin
    //------------------------------------------
    function isValidIPv6(ipaddress) {
        return /^((?:[0-9A-Fa-f]{1,4}))((?::[0-9A-Fa-f]{1,4}))*::((?:[0-9A-Fa-f]{1,4}))((?::[0-9A-Fa-f]{1,4}))*|((?:[0-9A-Fa-f]{1,4}))((?::[0-9A-Fa-f]{1,4})){7}$/.test(ipaddress);            
    }

    //------------------------------------------
    // helper function to verify a if a string 
    // is really an email address
    // will use the input type=email if it exists (HTML5)
    // otherwise will use a basic verification.
    //------------------------------------------
    function isValidEmail(value) {
        var input = document.createElement('input');

        input.type = 'email';
        input.value = value;

        return typeof input.checkValidity == 'function' ? input.checkValidity() : /^\S+@\S+\.\S+$/.test(value);
    }

    //------------------------------------------
    // helper function to verify a if a string 
    // is really a MAC address
    //------------------------------------------
    function isValidMacAddress(str) {
        return /^[\da-f]{2}([:-])(?:[\da-f]{2}\1){4}[\da-f]{2}$/i.test(str);
    }

    function alertCallback(msg, title, okCallback) {
        // Dialog and its properties.
        $('<div></div>').dialog({
            open: function (event, ui) { $('.ui-dialog-titlebar-close').hide(); },
            close: function (event, ui) { $(this).remove(); $('.ui-widget-overlay.ui-front').remove(); },
            resizable: true,
            modal: true,
            title: title,
            buttons: {
                'Ok': function () {
                    
                    $(this).dialog('close');                  
                    if (okCallback) okCallback();                    
                }
            }
        }).html(msg);
    }


    var ARRoot = glpiURLRoot();
    var ARL; // to store the locale strings
    var ARV; // to store the validation data
    var ARVAllTabs; // to store the complete info if needed for cross form validation references

    if (location.href.indexOf('withtemplate=1') == -1) {
        (function () {
            //debugger;

            // "/plugins/formvalidation/front/page.form.php"
            // "/plugins/rayusermanagementticket/front/rayusermanagementticket.helpdesk.public.php"
            // "/plugins/formcreator/front/formdisplay.php"
            var itemtype = location.pathname.match(/(plugin)s\/([a-z]+)(?:\/[a-z]+)+\/([a-z]+)\.form\.php$/);
            if (!itemtype) {
                itemtype = location.pathname.match(/(plugin)s\/([a-z]+)(?:\/[a-z]+)+\/([a-z]+)\.helpdesk.public\.php$/);
            }
            if (!itemtype) {
                itemtype = location.pathname.match(/(plugin)s\/(formcreator)\/front\/(form)display.php$/);
            }
            if (!itemtype) {
                itemtype = location.pathname.match(/front\/helpdesk.public.php$/);
                if (itemtype && !location.search.match(/^\?create_ticket=1$/)) {
                    itemtype = null;
                }
                if (!itemtype) {
                    itemtype = location.pathname.match(/front\/tracking.injector.php$/);
                }
                if (itemtype ) {
                    itemtype[0] = 'selfservice';
                    itemtype[1] = 'ticket';
                } 
            }
            // normal case
            if (!itemtype) {
                itemtype = location.pathname.match(/front\/([a-z]+)\.form\.php$/);
            }
            var items_id = location.search.match(/id=([0-9]+)/);
            items_id = (items_id?items_id[1]:0) ;
            if (itemtype) {
                //------------------------------------------
                // ajax call to load localized string
                $.ajax({
                    url: ARRoot + '/plugins/formvalidation/ajax/getLocales.php',
                    success: function (response, options) { /*debugger;*/ ARL = $.parseJSON(response); },
                    failure: function (response, options) { /*debugger;*/ }
                });

                //------------------------------------------
                // ajax call to load the validation data
                itemtype.shift();
                capitalizeArray(itemtype);
                $.ajax({
                    url: ARRoot + '/plugins/formvalidation/ajax/getFormValidations.php',
                    data: { itemtype: itemtype.join(''), id: items_id },
                    success: function (response, options) {
                        //debugger;
                        ARV = $.parseJSON(response);

                        $.each(ARV.forms, function (formIndex, formData) {
                            $.each(formData.fields, function (fieldIndex, fieldData) {
                                ARV.forms[formIndex].fields[fieldIndex].css_selector_mandatorysign_rel = getUpDownDOMPath(fieldData.css_selector_mandatorysign, fieldData.css_selector_value);
                                ARV.forms[formIndex].fields[fieldIndex].css_selector_errorsign_rel = getUpDownDOMPath(fieldData.css_selector_errorsign, fieldData.css_selector_value);
                            });
                        });

                        $(document).ajaxComplete(installRunMode);

                        if (ARV.config.editmode == 1 && ARV.pages_id > 0) {
                            $(installEditMode);
                        }

                        // TODO external fields
                        //else if (items_id > 0) {
                        //    //setTimeout(function () {
                        //    $(function(){
                        //        var count = $('ul[role="tablist"] li').length;
                        //        //"/ajax/common.tabs.php?_target=" + location.pathname + "&_itemtype=" + itemtype.join('') + "&_glpi_tab=-1&id=" + items_id + "&"
                        //        if (count) {
                        //            $('ul[role="tablist"]').parent('div').tabs("load", count - 1);
                        //        }
                        //    });
                        //    //}, 1000);
                        //}

                    },
                    failure: function (response, options) { /*debugger;*/ }
                });
            }


            var myPollingIntervals = {}; // an object is better than an array when using $.each()
            //------------------------------------------
            // install run mode by polling the DOM for 30
            // seconds, each time there is an completed 
            // ajax call
            //------------------------------------------
            function installRunMode(event, jqXHR, ajaxOptions) {
                setTimeout(stopPolling, 30000);
                $.each(ARV.forms, function (formIndex, formData) {
                    if (formData && !(myPollingIntervals['form' + formIndex])) {
                        myPollingIntervals['form' + formIndex] = setInterval(function () { /*debugger; */ installFormValidations(formData); }, 100);
                    }
                });
            }

            //------------------------------------------
            // will stop all current pollings, to prevent 
            // unusefull pollings
            //------------------------------------------
            function stopPolling() {
                $.each(myPollingIntervals, function (index, val) {
                    if (val) {
                        clearInterval(val);
                        delete myPollingIntervals[index] ;
                    }
                });
            }
            
            //------------------------------------------
            // install form validation 
            // is called each 100 millisecond during 30 seconds 
            // to try to find the forms
            // and calls the install field validation when for each found form
            // params:
            //  formData: is the ARV.forms[x] for one form
            //------------------------------------------
            function installFormValidations(formData) {
                var thisForm = $(formData.css_selector);
                if (thisForm.length > 0) {
                    $.each(thisForm, function (formIndex, formObj) {
                        if (formData.is_active == 1) {
                            formObj = $(formObj);
                            if (!formObj.attr('glpi-pluginformvalidation') ) {
                                formObj.attr('glpi-pluginformvalidation-formindex', formData.id);
                                formObj.attr('glpi-pluginformvalidation', true); // to prevent multiple install on already installed forms

                                formObj.submit(formData, defaultValidateForm);
                            }
                            // then will try to find all fields for this form
                            $.each(formData.fields, function (fieldIndex, fieldData) {
                                if (fieldData) {
                                    installFieldValidations(thisForm, fieldData);
                                }
                            });
                        }
                    });
                }

            }

            //------------------------------------------
            // install field validation
            // params:
            //  formObj: is JQuery, DOM form object or form selector_value,
            //  fieldData: is the ARV.forms[x].fields[y] for one field
            //------------------------------------------
            function installFieldValidations(thisForm, fieldData) {
                //var thisForm = $(formObj);
                var field = thisForm.find('>' + fieldData.css_selector_value).not("[glpi-pluginformvalidation]");
                if (field.length > 0) {
                    // add special class to fields to prevent multi validation
                    field.attr('glpi-pluginformvalidation-fieldindex', fieldData.id);
                    field.attr('glpi-pluginformvalidation-formindex', fieldData.forms_id);

                    // must verify if current field can be found in any show_mandatory_if formulas.
                    $.each(ARV.forms[thisForm.attr('glpi-pluginformvalidation-formindex')].fields, function (indexFld, fld) {
                        if (fld.is_active == 1 && fld.show_mandatory != 1 && fld.show_mandatory_if) {
                            var fldRegex = new RegExp("#" + fieldData.id + "\\b", "g");
                            if (fld.show_mandatory_if.match(fldRegex)) {
                                fld.show_mandatory_if = prepareMandatoryIfFormula(thisForm, fld.show_mandatory_if, fieldData.id);
                                thisForm.on('change keyup click input', '[glpi-pluginformvalidation-fieldindex="' + fieldData.id + '"]', { fldId: fld.id, fldData: fld }, function (EventObject) {
                                    //debugger;
                                    var thisForm = $(EventObject.delegateTarget);
                                    //var errorsignField = thisForm.find('>' + EventObject.data.fldData.css_selector_errorsign);
                                    var errorsignField = getObjFromSelectors(thisForm, EventObject.data.fldData, 'css_selector_errorsign'); //thisForm.find('>' + EventObject.data.fldData.css_selector_value)
                                        //.parents(EventObject.data.fldData.css_selector_value_rel.up)
                                        //.find(EventObject.data.fldData.css_selector_value_rel.down);
                                    //var mandatorysignField = thisForm.find('>' + EventObject.data.fldData.css_selector_mandatorysign);
                                    var mandatorysignField = getObjFromSelectors( thisForm, EventObject.data.fldData, 'css_selector_mandatorysign');
                                        //.parents(EventObject.data.fldData.css_selector_mandatorysign_rel.up)
                                        //.find(EventObject.data.fldData.css_selector_mandatorysign_rel.down);
                                    var showMandatory = eval(EventObject.data.fldData.show_mandatory_if);
                                    showHideMandatorySign(mandatorysignField, showMandatory);
                                    if (!showMandatory) {
                                        // clear any red alarm on this field
                                        if (errorsignField.hasClass('mceIframeContainer') && errorsignField[0].localName == 'iframe') {
                                            errorsignField.contents().find('body').focus();
                                        } else {
                                            errorsignField.focusin();
                                        }
                                    }
                                });
                                if (!fld.show_mandatory_if.match(/#\d+\b/g)) {
                                    //showHideMandatorySign(thisForm.find('>' + fld.css_selector_mandatorysign), eval(fld.show_mandatory_if));
                                    showHideMandatorySign(getObjFromSelectors(thisForm, fld, 'css_selector_mandatorysign'), eval(fld.show_mandatory_if)); // .find('>' + fld.css_selector_value)
                                        //.parents( fld.css_selector_mandatorysign_rel.up).find(fld.css_selector_mandatorysign_rel.down) , eval(fld.show_mandatory_if));
                                }
                            }
                        }
                    });


                    if (fieldData.is_active == 1) {
                        field.attr('glpi-pluginformvalidation', true);
                        // add special class to fields which helps disabled people to read page
                        field.attr("aria-required", "true");

                        var mandatory_sign = fieldData.show_mandatory ;
                        // force show mandatory sign depending on the eval result
                        if (fieldData.show_mandatory != 1 && fieldData.show_mandatory_if && !fieldData.show_mandatory_if.match(/#\d+\b/g)) {
                            mandatory_sign = (eval(fieldData.show_mandatory_if)?1:0);
                        }

                        // toggles the mandatory sign
                        // and stores 'initial text'
                        //showHideMandatorySign(thisForm.find('>' + fieldData.css_selector_mandatorysign), mandatory_sign);
                        showHideMandatorySign(getObjFromSelectors(thisForm, fieldData, 'css_selector_mandatorysign'), mandatory_sign);    //.find('>' + fieldData.css_selector_value)
                            //.parents(fieldData.css_selector_mandatorysign_rel.up)
                            //.find(fieldData.css_selector_mandatorysign_rel.down), mandatory_sign);
                        //showHideErrorSign(thisForm.find('>' + fieldData.css_selector_errorsign), true); // will be shown only if edit mode
                        showHideErrorSign(getObjFromSelectors(thisForm, fieldData, 'css_selector_errorsign'), true); // will be shown only if edit mode

                    }
                }
            }

            //------------------------------------------
            // show or hide the signs for mandatory fields
            // params:
            //  field: JQuery object representing the mandatorysign field,
            //  showSign: false (or 0) or true (or != 0) (default if not provided)
            //------------------------------------------
            function showHideMandatorySign(mandatorysign_field, showSign) {
                if (typeof showSign === "undefined") showSign = true; // default value when param is missing
                showSign = (showSign != 0); // to force showSign to be boolean
                if (ARV.config && ARV.config.css_mandatory) {
                    $.each(mandatorysign_field, function (index, obj) {
                        obj = $(obj);
                        if(!obj.data('initialText')) {
                            obj.data('initialText', obj[0].firstChild.textContent.replace(/\s*(:|\*)\s*/g, ''));
                            obj.data('initialHTML', obj[0].innerHTML);
                        }
                        obj[0].innerHTML = obj.data('initialText');
                        if (showSign) {
                            obj.append("<span class=\"red\">*</span>")
                            obj.css($.parseJSON(ARV.config.css_mandatory));
                        }
                        else {
                            obj[0].innerHTML = obj.data('initialHTML');
                            $.each($.parseJSON(ARV.config.css_mandatory), function (cssIndex, cssObj) {
                                if (obj) {
                                    // hide the mandatory sign
                                    obj.css(cssIndex, '');
                                }
                            });
                        }
                    });
                }
            }

            //------------------------------------------
            // prepare mandatory sign formula
            // params:
            //  thisForm: is JQuery, DOM form object,
            //  formula: is the formula in which #xx will be replaced by field.val()
            //  fieldIndex: is the field id that will be used
            //------------------------------------------
            function prepareMandatoryIfFormula(thisForm, formula, fieldIndex) {

                var field = thisForm.find('[glpi-pluginformvalidation-fieldindex="' + fieldIndex + '"]');
                if (field.length > 0) {
                    var valField = "thisForm.find('[glpi-pluginformvalidation-fieldindex=\"" + fieldIndex + "\"]')"; 

                    if (field[0].localName == 'iframe') { 
                        // this is an iFrame with mce
                        valField += '.contents().find(\'body\').text()';
                    } else if (field[0].type == 'checkbox') {
                        valField += ".first().prop('checked')";
                    } else if (field[0].type == 'radio') {
                        valField += ".filter(':checked').val()";
                    } else {
                        valField += '.val()';
                    }

                    var fieldRegex = new RegExp("#" + fieldIndex + "\\b", "g");
                    return formula.replace(fieldRegex, valField);
                } else {
                    return formula;
                }
            }

            //------------------------------------------
            // un-install field validation used only when in edit mode
            // params:
            //  formObj: is JQuery, DOM form object or form selector_value,
            //  fieldData: is the ARV.forms[x].fields[y] for one field
            //------------------------------------------
            function uninstallFieldValidations(formCss, fieldData) {
                var fields = $(formCss).find('>' + fieldData.css_selector_value + '[glpi-pluginformvalidation-fieldindex="' + fieldData.id + '"]');
                if (fields.length > 0) {
                    // remove special class to fields which helps disabled people to read page
                    fields.removeAttr("aria-required");
                    fields.removeAttr("glpi-pluginformvalidation");

                    // hides the mandatory sign
                    $.each(fields, function (index, field) {
                        //showHideMandatorySign($(formCss).find('>' + fieldData.css_selector_mandatorysign), false);
                        showHideMandatorySign(getObjFromSelectors( $(formCss), fieldData, 'css_selector_mandatorysign'), false); // .find('>' + fieldData.css_selector_value)
                            //.parents(fieldData.css_selector_mandatorysign_rel.up)
                            //.find(fieldData.css_selector_mandatorysign_rel.down), false);
                        //showHideErrorSign($(formCss).find('>' + fieldData.css_selector_errorsign), false);
                        showHideErrorSign(getObjFromSelectors( $(formCss), fieldData, 'css_selector_errorsign'), false); // .find('>' + fieldData.css_selector_value)
                            //.parents(fieldData.css_selector_errorsign_rel.up)
                            //.find(fieldData.css_selector_errorsign_rel.down), false);
                    });
                }
            }

            function showHideErrorSign(errorsign_field, showSign) {
                if (ARV.config && ARV.config.css_mandatory && ARV.config.editmode == 1) {
                    // must anyway show something to inform that field is going to be validate
                    $.each(errorsign_field, function (index, obj) {
                        obj = $(obj);
                        if (showSign) {
                            obj.css($.parseJSON(ARV.config.css_error));
                            if (obj[0].localName != 'td') {
                                obj.find('*').css($.parseJSON(ARV.config.css_error));
                            }
                        } else {
                            $.each($.parseJSON(ARV.config.css_error), function (cssIndex, cssObj) {
                                if (obj) {
                                    // hide the mandatory sign
                                    obj.css(cssIndex, '');
                                    if (obj[0].localName != 'td') {
                                        obj.find('*').css(cssIndex, '');
                                    }
                                }
                            });
                        }
                    });
                }
            }


            //------------------------------------------
            // clear previous error list
            // params:
            //  eventObject: the event passed to submit function,
            //      so is the event passed when submitting the form
            //------------------------------------------
            function clearPreviousValidationErrors(eventObject) {
                var locEltList = eventObject.data.fields;
                $.each(locEltList, function (index, obj) {
//                    var fieldErrorSign = $(eventObject.target).find('>' + obj.css_selector_errorsign);
                    var field = $(eventObject.target).find('>' + obj.css_selector_value);
                    if (field.length > 0) {
                        var fieldErrorSign = getObjFromSelectors(field, obj, 'css_selector_errorsign'); // field.parents(obj.css_selector_errorsign_rel.up).find(obj.css_selector_errorsign_rel.down);
                        if (field[0].localName == 'iframe') {
                            field.contents().find('body').trigger('click');
                        } else {
                            fieldErrorSign.trigger('focusin');
                        }
                    }
                });
            }


            //------------------------------------------
            // this function validate mandatory fields
            // using the either the default formulas 
            // or the provided ones.
            // for specific validation schemes 
            // another function must be written
            // params:
            //  eventObject: the event passed when submitting the form,
            //------------------------------------------
            function defaultValidateForm(eventObject) {
                // in case polling is not yet finished
                stopPolling();
                // clear previous error list
                clearPreviousValidationErrors(eventObject);

                // let's validate fields
                var thisForm = $(this);
                var formFormula = eventObject.data.formula || 'true';
                var locEltList = eventObject.data.fields;
                var fieldListSelector = {};
                var objErrorList = {};
                var valList = {};
                var formulaList = {};
                var errorMessage = '';

                //------------------------------------------
                // get values for all input
                // and formulas
                // and fill in form formula
                $.each(locEltList, function (index, obj) {
                    //debugger;
                    var txtField = "thisForm.find('[glpi-pluginformvalidation-fieldindex=\"" + index + "\"]')" ;
                    var field = eval(txtField); 
                    if (field.length == 0) {// not found then try alternative value
                        txtField = "thisForm.find('" + obj.css_selector_altvalue + "')"; 
                        field = eval(txtField);
                    }
                    fieldListSelector[index] = txtField;
                    if (field.length > 0) {
                        var defaultFormula = "#!=''";

                        valList[index] = txtField; 
                        if (field[0].localName == 'iframe') { 
                            // this is an iFrame with mce
                            valList[index] += '.contents().find(\'body\').text()'; 
                        } else if (field[0].localName == 'td') {
                            valList[index] += '.text()';
                        } else if (field[0].type == 'checkbox') {
                            valList[index] += ".first().prop('checked')";
                            defaultFormula = "true";
                        } else if (field[0].type == 'radio') {
                            valList[index] += ".filter(':checked').val()";
                            defaultFormula = "true";
                        } else {
                            valList[index] += '.val()';
                        }
                        if (field.attr('id') && field.attr('id').match(/^dropdown_/)) { //&& !isNaN(eval(valList[index]))
                            defaultFormula = "#>0";
                            // TODO
                            // add possibility to get label for select value of dropdown
                            // when in formula is something like ##xxx
                            // we may get the label associated with the dropdown value with
                            // eval(txtField.parent().find('.select2-chosen').text())
                        }
                        formulaList[index] = obj.formula || defaultFormula;
                        if (obj.is_active == 0) {
                            formulaList[index] = 'true';
                        }
                    } else { // field not found in current form
                        //obj.is_active = 0; // field not found in current form
                        formulaList[index] = 'true';
                    }
                    //if (obj.is_active == 1) {
                    //    formulaList[index] = obj.formula || defaultFormula;
                    //} else {
                    //    formulaList[index] = 'true';
                    //}
                    var formRegex = new RegExp("#" + index + "\\b", "g");
                    formFormula = formFormula.replace(formRegex, valList[index]);
                });

                //------------------------------------------
                // fill in field formulas
                $.each(formulaList, function (indexFormula, objFormula) {
                    $.each(valList, function (indexVal, objVal) {
                        var fieldRegex ;
                        if (indexVal == indexFormula) {
                            // regex for default field '#'
                            fieldRegex = new RegExp("#\\B", "g");
                        } else {
                            // regex for other fields
                            fieldRegex = new RegExp("#" + indexVal + "\\b", "g");
                        }
                        formulaList[indexFormula] = formulaList[indexFormula].replace(fieldRegex, objVal);
                    });
                });

                // TODO external fields
                // if any external fields are present in formula, then must add them into formulas
                //$.each(formulaList, function (indexFormula, objFormula) {
                //    var externalField = formulaList[indexFormula].match(/@[0-9]+\.[0-9]+\b/g) ;
                //    if (externalField) {
                //        // we have an external ref
                //        var extForm = externalField[0].split('.')[0].replace('@', '');
                //        var extField = externalField[0].split('.')[1];
                //        var txtField = "$('" + ARV.forms[extForm].css_selector + "').find('[glpi-pluginformvalidation-fieldindex=\"" + extField + "\"]').first().val()";
                //        var fieldRegex = new RegExp(externalField[0] + "\\b", "g");
                //        formulaList[indexFormula] = formulaList[indexFormula].replace(fieldRegex, txtField);
                //    }
                //});

                // if any #xxx has not been replaced by fieldxxx.val()
                // then will collect these in order to show them in alert dialog
                var unusedVariables = [];
                $.each(formulaList, function (indexFormula, objFormula) {
                    if (loc = objFormula.match(/(#\d+)/g)) {
                        unusedVariables = $.merge(unusedVariables, loc);
                    }
                });


                //------------------------------------------
                // formulas evaluations
                $.each(locEltList, function (index, obj) {
                    try{
                        if (!eval(formulaList[index])) {
                            // result is false: means the field is not valid
                            // then collect field text to show them
                            // or when a localized string exists, will use it
                            var field = eval(fieldListSelector[index]); 
                            objErrorList[index] = obj;
                            //var locErrorMessage = '* ' + thisForm.find('>' + obj.css_selector_mandatorysign).data('initialText');  // by default
                            var locErrorMessage = '* ' + getObjFromSelectors(thisForm, obj, 'css_selector_mandatorysign').data('initialText'); //thisForm.find('>' + obj.css_selector_value)
                                //.parents(fieldData.css_selector_mandatorysign_rel.up)
                                //.find(fieldData.css_selector_mandatorysign_rel.down).data('initialText');  // by default
                            try {
                                if (ARL.plugin_formvalidation.forms[ARV.pages_id][obj.forms_id][obj.id]) {
                                    locErrorMessage = '* ' + ARL.plugin_formvalidation.forms[ARV.pages_id][obj.forms_id][obj.id];
                                }
                            } catch (e) {
                            }
                            if (errorMessage != '') {
                                errorMessage += ",<br>";
                            }
                            errorMessage += locErrorMessage ;

                            // show the focus in 'red'!
                            var focusEvent = 'click change focusin';
                            //var fieldErrorSign = thisForm.find('>' + obj.css_selector_errorsign);
                            var fieldErrorSign = getObjFromSelectors(thisForm, obj, 'css_selector_errorsign');// thisForm.find('>' + obj.css_selector_value).parents(obj.css_selector_errorsign_rel.up).find(obj.css_selector_errorsign_rel.down);
                            var fieldFocus = fieldErrorSign; // field;
                            if (field[0].localName == 'iframe') { 
                                fieldFocus = field.contents().find('body');
                                focusEvent = 'click change focusin';
                            } else if( field[0].type == 'checkbox' ) {
                                focusEvent = 'click change focusin';
                            }

                            function removeErrorSign(eventObject) { 
                                $(this).off('click change focusin', null, removeErrorSign);
                                if (ARV.config.css_error) {
                                    $.each($.parseJSON(ARV.config.css_error), function (cssIndex, cssObj) {
                                        // hide the mandatory sign
                                        $(eventObject.data.fes).css(cssIndex, '');
                                    });
                                } else {
                                    $(eventObject.data.fes).css('background-color', '');
                                }
                            }

                            fieldFocus.one(focusEvent, null, { fes: fieldErrorSign }, removeErrorSign);
                            if (ARV.config.css_error) {
                                fieldErrorSign.css($.parseJSON(ARV.config.css_error));
                            } else {
                                fieldErrorSign.css('background-color', 'red');
                            }
                        }
                    } catch (e) {
                        // if any error in formula evaluations
                        errorMessage += '<span class=\"red\">' + ARL.plugin_formvalidation['formulaerror'] + index + '<br>Error message: ' + e.message + '<br>Unknown variables: ' + unusedVariables + '</span><br>';
                    }
                });

                //------------------------------------------
                if (errorMessage != '' || !eval(formFormula)) {
                    // cancel current event to prevent submit of form
                    eventObject.stopImmediatePropagation();
                    eventObject.preventDefault();
                    alertCallback('<b>' + ARL.plugin_formvalidation['mandatorytitle'] + '</b><br>' + errorMessage, ARL.plugin_formvalidation['alert']);
                } else {
                    // the form is going to be posted
                    // we must check if there are checkboxes and then clean wrongly added input[type=hiddden] with the same name than input[type=checkbox] when checkbox is checked
                    var checkBoxes = thisForm.find("> input[type=checkbox]");
                    $.each(checkBoxes, function (index, obj) {
                        var listWrongCheckBox = thisForm.find("> input[type=hidden][name='" + obj.name + "']");
                        if ($(obj).prop('checked')) {
                            listWrongCheckBox.remove();
                        } else {
                            listWrongCheckBox.slice(1).remove(); // to keep first one and remove others
                        }
                    });
                }

            }

            
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------------------------
// Edit part of the module
//---------------------------------------------------------------------------------------------------------------------------------------------------------

            var BORDER_WIDTH = 6;

            //------------------------------------------
            // this function retreive the path of the 
            // object in parameter and return an object
            // with .form as the selector_value for the form 
            // and .path as the focus path
            //------------------------------------------
            jQuery.fn.getPath = function ( complete ) {
                if (this.length < 1) throw 'getPath() requires at least one element.';
                if (typeof complete === "undefined") {
                    complete = false;
                }
                var path, node = (complete ? this : this.parent()); //s('td').first();
                while (node.length) {
                    var realNode = node[0], name = realNode.localName;
                    if (!name) break;
                    
                    name = name.toLowerCase();

                    if (name == 'form') {
                        //var fieldData = '';
                        if ($(realNode).attr('name')) {
                            name += '[name="' + $(realNode).attr('name') + '"]';
                        }

                        return { form: name + '[action="' + $(realNode).attr('action') + '"]', path: path };
                    }
                    var parent = node.parent();

                    var siblings = parent.children(name);
                    if (siblings.length > 1) {
                        name += ':eq(' + siblings.index(realNode) + ')';
                    }
                    path = name + (path ? '>' + path : '');
                    node = parent;
                }

                return { form: '', path: path };
            };


            function initSignOverlay(overlayName, backgroundColor) {
                $("body").append("<div id='" + overlayName + "-top'></div>");
                $("body").append("<div id='" + overlayName + "-left'></div>");
                $("body").append("<div id='" + overlayName + "-bottom'></div>");
                $("body").append("<div id='" + overlayName + "-right'></div>");

                $("#" + overlayName + "-top")
                    .css("opacity", 1)
                    .css("background", backgroundColor)
                    .css("cursor", "pointer")
                    .css("border", "none")
                    .css("z-index", 1001)
                    .css("height", BORDER_WIDTH )
                    .css("position", "absolute")
                    .hide();
                $("#" + overlayName + "-left")
                    .css("opacity", 1)
                    .css("background", backgroundColor)
                    .css("cursor", "pointer")
                    .css("border", "none")
                    .css("z-index", 1001)
                    .css("width", BORDER_WIDTH)
                    .css("position", "absolute")
                    .hide();
                $("#" + overlayName + "-bottom")
                    .css("opacity", 1)
                    .css("background", backgroundColor)
                    .css("cursor", "pointer")
                    .css("border", "none")
                    .css("z-index", 1001)
                    .css("height", BORDER_WIDTH)
                    .css("position", "absolute")
                    .hide();
                $("#" + overlayName + "-right")
                    .css("opacity", 1)
                    .css("background", backgroundColor)
                    .css("cursor", "pointer")
                    .css("border", "none")
                    .css("z-index", 1001)
                    .css("width", BORDER_WIDTH)
                    .css("position", "absolute")
                    .hide();
            }

            function showSignOverlay(overlayName, top, left, width, height) {                
                $("#" + overlayName + "-top")
                    .css("top", top)
                    .css("left", left)
                    .css("width", width)
                    .show();
                $("#" + overlayName + "-left")
                    .css("top", top)
                    .css("left", left)
                    .css("height", height)
                    .show();
                $("#" + overlayName + "-bottom")
                    .css("top", top + height - BORDER_WIDTH )
                    .css("left", left)
                    .css("width", width)
                    .show();
                $("#" + overlayName + "-right")
                    .css("top", top)
                    .css("left", left + width - BORDER_WIDTH )
                    .css("height", height)
                    .show();
            }

            function hideSignOverlay(overlayName) {
                $("#" + overlayName + "-top").hide();                    
                $("#" + overlayName + "-left").hide();
                $("#" + overlayName + "-bottom").hide();
                $("#" + overlayName + "-right").hide();
            }



            //------------------------------------------
            // install Edit mode
            //------------------------------------------
            function installEditMode() {
                var SELECT_DISABLE = -1;
                var SELECT_FIELD = 0;
                var SELECT_ERRORSIGN = 1;
                var SELECT_MANDATORYSIGN = 2;

                var selectMode = SELECT_FIELD; // by default
                var defaultEditMessage = "<div style='color:red;'>Info: 'Form Validation' edit mode is ON!</div><br>";
                var selectFieldMessage = defaultEditMessage + "<div>--&gt; Field Selection</div>";
                var selectEscapeMessage = "<br><div>Press ESC to go back to field selection</div><br>";
                var selectErrorSignMessage = selectFieldMessage + selectEscapeMessage + "<div>--&gt; Validation error area Selection</div>";
                var selectMandatorySignMessage = selectErrorSignMessage + "<div>--&gt; Mandatory sign area Selection</div>";

                // to show a message to inform that we are in edit mode
                $("body").append("<div id='message_editmode_is_on'></div>");
                $('#message_editmode_is_on').dialog({
                    dialogClass: 'message_after_redirect',
                    minHeight: 10,
                    width: 'auto',
                    height: 'auto',
                    position: {
                        my: 'right top',
                        at: 'right-20 top',
                        of: $('#page'),
                        collision: 'none'
                    },
                    autoOpen: false,
                    create: function(event, ui) {
                        $('.ui-dialog-titlebar', ui.dialog | ui).hide();
                        $('.ui-dialog-titlebar-close', ui.dialog | ui).hide();
                    },
                    show: {
                        effect: 'slide',
                        direction: 'up',
                        duration: 800
                    },
                }).html(selectFieldMessage).dialog('open');

                // overlays
                $("body").append("<div id='field-overlay'></div>");
                $("#field-overlay")
                            .css("opacity", 0.25)
                            .css("background", "blue")
                            .css("cursor", "pointer")
                            .css("position", "absolute")                            
                            .hide();

                initSignOverlay("errorsign-overlay", "red");
                initSignOverlay("mandatorysign-overlay", "green");


                $("#field-overlay").on('mouseleave', function () {
                    if (selectMode == SELECT_FIELD) {
                        $(this).hide();
                    }
                });
                $(document).keyup(function (e) {
                    if (e.which == 27) { // escape key maps to keycode `27`
                        // reset mode to SELECT_FIELD
                        restoreSelectMode();
                        hideSignOverlay("errorsign-overlay");
                        hideSignOverlay("mandatorysign-overlay");
                    }
                });
                $("#field-overlay").on('mouseup', function () {
                    if (selectMode == SELECT_FIELD) {
                        // check if field is alredy under validation
                        // if yes, then calls updateFieldInDB
                        var fieldUnderValidation = $(this).data('fieldData').jq_value_elt.attr('glpi-pluginformvalidation') ;
                        if (fieldUnderValidation) {
                            disableSelectMode();
                            updateFieldInDB(false);
                        } else {
                            setErrorSignSelectMode();
                        }
                    }
                });


                function setErrorSignSelectMode() {
                    selectMode = SELECT_ERRORSIGN;
                    $("#field-overlay").hide();
                    $('#message_editmode_is_on').html(selectErrorSignMessage);
                }

                function setMandatorySignSelectMode() {
                    selectMode = SELECT_MANDATORYSIGN; // go to select mandatory sign mode
                    $('#message_editmode_is_on').html(selectMandatorySignMessage);
                }

                function disableSelectMode() {
                    selectMode = SELECT_DISABLE;
                    $('#message_editmode_is_on').html(defaultEditMessage);
                }

                function restoreSelectMode() {
                    $("#field-overlay").hide();
                    selectMode = SELECT_FIELD;
                    $('#message_editmode_is_on').html(selectFieldMessage);
                }

                function updateFieldInDB( complete ) {
                    if( typeof complete === "undefined" ) 
                        complete = true ;

                    var fieldData = $("#field-overlay").data('fieldData');
                    var jqValueField = fieldData.jq_value_elt;

                    var jqMandatorysignField ;
                    var jqErrorsignField ;
                    var mandatorysignText ;
                    var mandatorysignPath ;
                    var errorsignPath ;

                    if( complete ) {
                        jqMandatorysignField = fieldData.jq_mandatorysign_elt;
                        jqErrorsignField = fieldData.jq_errorsign_elt;
                        mandatorysignText = jqMandatorysignField[0].firstChild.textContent.replace(/\s+:\s*/g, '');
                        mandatorysignPath = jqMandatorysignField.getPath(true);
                        errorsignPath = jqErrorsignField.getPath(true);
                    }

                    var valuePath = jqValueField.getPath();
                    var selector_value = fieldData.selector_value;

                    // must get formindex and fieldindex
                    // to check if field is already under validation or not
                    var fieldIndex = jqValueField.attr('glpi-pluginformvalidation-fieldindex');
                    var formIndex = jqValueField.attr('glpi-pluginformvalidation-formindex');

                    var fieldUnderValidation = jqValueField.attr('glpi-pluginformvalidation');

                    if (fieldUnderValidation) {
                        // field is under validation
                        // is show_mandatory 
                        if (ARV.forms[formIndex].fields[fieldIndex].show_mandatory == 1) {
                            // then hide mandatory marking
                            $.ajax({
                                url: ARRoot + '/plugins/formvalidation/ajax/setUnsetField.php',
                                method: 'POST',
                                data: { action: 'hidemandatorysign', fieldindex: fieldIndex },
                                success: function (response, options) {
                                    //debugger;
                                    var infoField = $.parseJSON(response);
                                    if (infoField) {
                                        var jqMandatorysignField = getObjFromSelectors($(ARV.forms[formIndex].css_selector), ARV.forms[formIndex].fields[fieldIndex], 'css_selector_mandatorysign');
                                            //$(ARV.forms[formIndex].css_selector + '>' + ARV.forms[formIndex].fields[fieldIndex].css_selector_value)
                                            //.parents(ARV.forms[formIndex].fields[fieldIndex].css_selector_mandatorysign_rel.up)
                                            //.find(ARV.forms[formIndex].fields[fieldIndex].css_selector_mandatorysign_rel.down);
                                        // ??? var jqErrorsignField = $(ARV.forms[formIndex].css_selector + '>' + ARV.forms[formIndex].fields[fieldIndex].css_selector_errorsign ) ;
                                        ARV.forms[formIndex].fields[fieldIndex].show_mandatory = 0;
                                        showHideMandatorySign(jqMandatorysignField, 0) ;
                                        alertCallback("Mandatory sign hidden<br>'" + ARV.forms[formIndex].fields[fieldIndex].name + "'<br>field id: " + fieldIndex, "Mandatory sign hidden", restoreSelectMode);
                                    } else {
                                        alertCallback("Mandatory sign NOT hidden<br>'" + ARV.forms[formIndex].fields[fieldIndex].name + "'<br>field id: " + fieldIndex, "Mandatory sign NOT hidden", restoreSelectMode);
                                    }
                                },
                                failure: function (response, options) { /*debugger;*/ }
                            });
                        } else {
                            // then must call ajax to de-activate it from validation
                            $.ajax({
                                url: ARRoot + '/plugins/formvalidation/ajax/setUnsetField.php',
                                method: 'POST',
                                data: { action: 'unset', fieldindex: fieldIndex },
                                success: function (response, options) {
                                    //debugger;
                                    var infoField = $.parseJSON(response);
                                    if (infoField) {
                                        ARV.forms[formIndex].fields[fieldIndex].is_active = 0;
                                        uninstallFieldValidations(ARV.forms[formIndex].css_selector, ARV.forms[formIndex].fields[fieldIndex]);
                                        alertCallback("Field de-activated<br>'" + ARV.forms[formIndex].fields[fieldIndex].name + "'<br>field id: " + fieldIndex, "Field de-activated", restoreSelectMode);
                                    } else {
                                        alertCallback("Field NOT de-activated!<br>'" + ARV.forms[formIndex].fields[fieldIndex].name + "'<br>field id: " + fieldIndex, "Field NOT de-activated!", restoreSelectMode);
                                    }
                                },
                                failure: function (response, options) { /*debugger;*/ }
                            });
                        }
                    } else {
                        // may be to add field to DB
                        // and may be also form

                        if (!formIndex) {
                            formIndex = jqValueField.parents('form').first().attr('glpi-pluginformvalidation-formindex');
                            if (!formIndex) {
                                formIndex = 0; // means the form is not under validation, will be added too
                            }
                        }
                        if (!fieldIndex) {
                            fieldIndex = 0;
                        }
                        $.ajax({
                            url: ARRoot +  '/plugins/formvalidation/ajax/setUnsetField.php',
                            method: 'POST',
                            data: {
                                action: 'set',
                                pages_id: ARV.pages_id,
                                formindex: formIndex,
                                is_createitem: (items_id == 0?1:0),
                                fieldindex: fieldIndex,
                                form_css_selector: valuePath.form,
                                css_selector_value: valuePath.path + ' ' + selector_value,
                                css_selector_errorsign:  errorsignPath.path,
                                css_selector_mandatorysign: mandatorysignPath.path,
                                name: mandatorysignText
                            },
                            success: function (response, options) {
                                //debugger;
                                var infoField = $.parseJSON(response);
                                if (infoField) {
                                    if (infoField.forms_id) {
                                        formIndex = infoField.forms_id;
                                        fieldIndex = infoField.fields_id;
                                        ARV.forms = $.extend(true, {}, ARV.forms, infoField.forms);
                                    } else {
                                        ARV.forms[formIndex].fields[fieldIndex].is_active = 1;
                                        ARV.forms[formIndex].fields[fieldIndex].show_mandatory = 1;
                                        ARV.forms[formIndex].fields[fieldIndex].css_selector_mandatorysign = mandatorysignPath.path;
                                        ARV.forms[formIndex].fields[fieldIndex].css_selector_errorsign = errorsignPath.path;
                                    }
                                    ARV.forms[formIndex].fields[fieldIndex].css_selector_mandatorysign_rel = getUpDownDOMPath(mandatorysignPath.path, ARV.forms[formIndex].fields[fieldIndex].css_selector_value);
                                    ARV.forms[formIndex].fields[fieldIndex].css_selector_errorsign_rel = getUpDownDOMPath(errorsignPath.path, ARV.forms[formIndex].fields[fieldIndex].css_selector_value);

                                    installFormValidations(ARV.forms[formIndex]); // to be sure current form is checked with newly created field
                                    if (infoField.forms_id) {
                                        alertCallback("Field activated<br>'" + mandatorysignText + "'<br>field_id: " + fieldIndex, "Field activated", restoreSelectMode);
                                    } else {
                                        alertCallback("Field re-activated<br>'" + mandatorysignText + "'<br>field_id: " + fieldIndex, "Field re-activated", restoreSelectMode);
                                    }
                                } else {
                                    alertCallback("Field NOT activated!<br>'" + mandatorysignText + "'", "Field NOT activated!", restoreSelectMode);
                                }
                            },
                            failure: function (response, options) {
                                alertCallback("Field NOT activated!<br>'" + mandatorysignText + "'", "Field NOT activated!", restoreSelectMode);
                            }
                        });

                        
                    }
                };


                $("[id^='errorsign-overlay-']").on('mouseup', function () {
                    if (selectMode == SELECT_ERRORSIGN) {
                        // we store in the data field the jquery object
                        setMandatorySignSelectMode();
                    }
                });
                $("[id^='mandatorysign-overlay-']").on('mouseup', function () {
                    hideSignOverlay("mandatorysign-overlay");
                    disableSelectMode();
                    hideSignOverlay("errorsign-overlay");
                    $("#field-overlay").hide();
                    updateFieldInDB();
                });


                function myMouseEnter(eventObject) {
                    if (selectMode == SELECT_ERRORSIGN || selectMode == SELECT_MANDATORYSIGN) {
                        var field = $(document.elementFromPoint(eventObject.clientX, eventObject.clientY)); //$(this).first();
                        //console.log(eventObject.pageX + ', ' + eventObject.pageY + ' / ' + eventObject.clientX + ', ' + eventObject.clientY);
                        if (field.length > 0) {
                            switch (selectMode) {
                                case SELECT_ERRORSIGN:
                                    //debugger;
                                    if ($.contains($("#field-overlay").data("fieldData")['jq_focus_elt'][0], this)) {
                                        field = $("#field-overlay").data("fieldData")['jq_focus_elt'];
                                    }
                                    showSignOverlay("errorsign-overlay", field.offset().top, field.offset().left, field.outerWidth(), field.outerHeight());
                                    $("#field-overlay").data("fieldData")['jq_errorsign_elt'] = field;
                                    break;
                                case SELECT_MANDATORYSIGN:
                                    showSignOverlay("mandatorysign-overlay", field.offset().top, field.offset().left, field.outerWidth(), field.outerHeight());
                                    $("#field-overlay").data("fieldData")['jq_mandatorysign_elt'] = field;
                                    break;
                            }
                            eventObject.stopImmediatePropagation();
                            eventObject.preventDefault();
                        }
                    }
                }

                $('body').on('mousemove', 'form span, form div, form td, form th, form img', myMouseEnter); // form input, form textarea, 


                //------------------------------------------
                $('body').on('mouseover', 'form div.select2-container, form input[type=radio], form input:text:visible:not(.select2-focusser), form textarea:visible, form td.mceIframeContainer iframe, form div.mce-edit-area.mce-container.mce-panel.mce-stack-layout-item.mce-last iframe,form span.form-group-checkbox, form input[type=checkbox]', function () {
                    if (selectMode==SELECT_FIELD) {
                        var field = false;

                        switch( this.localName ){
                            case 'input':
                            case 'textarea':
                                jqValueElt = $(this);
                                field = {
                                    'jq_value_elt': jqValueElt,
                                    'jq_focus_elt': jqValueElt,
                                    'selector_value': jqValueElt[0].localName + '[name="' + jqValueElt[0].name + '"]'
                                };
                                break;
                            case 'div':
                                jqValueElt = $('#' + this.id.match(/s2id_([a-z0-9_\-]*)/i)[1]).first();
                                field = {
                                    'jq_value_elt': jqValueElt,
                                    'jq_focus_elt': $(this),
                                    'selector_value': jqValueElt[0].localName + '[name="' + jqValueElt[0].name + '"]'
                                };
                                break;
                            case 'span': 
                                jqValueElt = $(this).find('> input:checkbox');
                                field = {
                                    'jq_value_elt': jqValueElt,
                                    'jq_focus_elt': $(this),
                                    'selector_value': jqValueElt[0].localName + '[name="' + jqValueElt[0].name + '"]'
                                };
                                break;
                            case 'iframe':
                                field = {
                                    'jq_value_elt': $(this),
                                    'jq_focus_elt': $(this),
                                    'selector_value': 'iframe[id^="' + /^[a-z]+/i.exec(this.id) + '"]'
                                };
                                break;
                        }

                        if (field) {
                            $("#field-overlay").data('fieldData', field);
                            $("#field-overlay")
                                .css("left", field.jq_focus_elt.offset().left)
                                .css("top", field.jq_focus_elt.offset().top)
                                .css("z-index", 1000)
                                .width(field.jq_focus_elt.outerWidth())
                                .height(field.jq_focus_elt.outerHeight())
                                .show();
                        } else {
                            $("#field-overlay").hide();
                        }
                    }
                });
            }
            
        })();
    }
})();

