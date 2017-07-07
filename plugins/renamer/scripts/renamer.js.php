<?php
/*
------------------------------------------------------------------------
GLPI Plugin MantisBT
Copyright (C) 2014 by the GLPI Plugin MantisBT Development Team.

https://forge.indepnet.net/projects/renamer
------------------------------------------------------------------------

LICENSE

This file is part of GLPI Plugin Renamer project.

GLPI Plugin MantisBT is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI Plugin MantisBT is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI Plugin MantisBT. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------------

@package   GLPI Plugin Renamer
@author    Stanislas Kita (teclib')
@copyright Copyright (c) 2014 GLPI Plugin MantisBT Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/renamer
@since     2014

------------------------------------------------------------------------
*/
include ('../../../inc/includes.php');

global $CFG_GLPI;

$root_ajax = $CFG_GLPI['root_doc']."/plugins/renamer/ajax/ajax.php";
$info = __('Thank you to inform the field', 'renamer');
$noSelected = __('No selected', 'renamer');
$selected = __('Selected', 'renamer');

$JS = <<<JAVASCRIPT

function restoreLocaleFiles() {
   var lang = $("#dropdown_languageToRestore").find(":selected").text();

   $.ajax({
      type: "POST",
      url: "{$root_ajax}",
      data: "action=restoreALanguage&" + "lang=" + lang ,
      success: function (msg) {
         window.location.reload();
      }
   });
   return false; // permet de rester sur la même page à la soumission du formulaire
}

function updateOverload(id) {

   var input = $('#updateWord' + id);
   var newWord = $('#updateWord' + id).val();
   var id = id;
   var img = $("#waitLoadingOnUpdate");

   if(newWord.length == 0 || newWord == ' '){
      input.css('border-color','red');
      alert('Veuillez renseigner le nouveau mot');

   }else{
      img.css('display', 'block');
      input.css('border-color','#888888');
      $.ajax({ // fonction permettant de faire de l'ajax
         type: "POST",
         url:  "{$root_ajax}",
         data: "action=updateOverload&" +
               "newWord=" + newWord +"&" +
               "id=" + id ,
         success: function (msg) {
            window.location.reload();
         }
      });

      return false; // permet de rester sur la même page à la soumission du formulaire
   }
}


$(document).ready(function() {
    
   $("#pick_list_lang").pickList({
      mainClass:       "foobar",
      sourceListLabel: "{$noSelected}",
      targetListLabel: "{$selected}",
      addAllLabel:     ">>",
      addLabel:        ">",
      removeAllLabel:  "<<",
      removeLabel:     "<",
      sortItems:       false, //true
   });


   var currentRequest = null;
   $('#searchword').keyup(function() {

      var table = $("#tableOverloadWord");
      var word = $("#searchword").val();
      var lang = $("#dropdown_language").find(":selected").text();
      var img = $("#waitLoading");
      img.css('display', 'block');

      currentRequest = $.ajax({
         type: "POST",
         url:  "{$root_ajax}",
         data: "action=getWords&" +
               "word=" + word +"&" +
               "lang=" + lang,
         beforeSend : function() {
            if(currentRequest != null){
               currentRequest.abort();
            }
         },
         success: function (msg) {
            $("#tbody").children().remove();
            $("#tbody").append(msg);
            img.css('display', 'none');
         },
         error: function (request, status, error) {
            if(error != 'abort') {
               alert(error);
            }
         }
    });
   });
});

function overloadWord(index){

   var newWord = $('#newWord' + index).val();
   var lang = $("#dropdown_language").find(":selected").text();;
   var id =  $('#msgid' + index).val();
   var wordToOverload =  $('#msgstr' + index).val();
   var msgctxt =  $('#msgctxt' + index).val();
   var divInfo = $('#info' + index);
   var img = $('#waitLoadingOverload'+index);

   img.css('display', 'block');
   divInfo.empty();

   $.ajax({
      type: "POST",
      url:  "{$root_ajax}",
      data: "action=overloadWord&" +
            "word=" + newWord +"&" +
            "id=" + id +"&" +
            "msgctxt=" + msgctxt +"&" +
            "wordToOverload=" + wordToOverload +"&" +
            "lang=" + lang,
      success: function (msg) {
         img.css('display', 'none');
         divInfo.html(msg);
      },
      error: function () {
         img.css('display', 'none');
      }
   });
   return false; // permet de rester sur la même page à la soumission du formulaire
}


function findWord(){

   var table = $("#tableOverloadWord");
   var word = $("#word").val();
   var lang = $("#dropdown_language").find(":selected").text();
   var xhr = null;  //notre appel ajax

   xhr = $.ajax({
      type: "POST",
      url:  "{$root_ajax}",
      data: "action=getWords&" +
            "word=" + word +"&" +
            "lang=" + lang,
      beforeSend : function()    {
         if(xhr != null) { //kill de l'appel ajax car mutliple
            alert("abort");
            xhr.abort();
         }
      },
      success: function (msg) {
         $("#tbody").children().remove();
         $("#tbody").append(msg);
      },
      error: function () {
         alert('pb ajax');
      }
   });
   return false; // permet de rester sur la même page à la soumission du formulaire
}


//Function to restore all locales files
function restoreAllLocaleFiles(){
   $.ajax({ // fonction permettant de faire de l'ajax
      type: "POST",
      url: "{$root_ajax}",
      data: "action=restore",
      success: function (msg) {
         window.location.reload();
      },
      error: function () {
         alert("Ajax problem");
      }
   });
}

//Function to restore an overload word
function restoreWord(id){
   $.ajax({
      type: "POST",
      url:  "{$root_ajax}",
      data: "action=restoreWord&"+
            "id=" + id,
      success: function (msg) {
         window.location.reload();
      },
      error: function () {
         alert("Ajax problem");
      }
   });
}

JAVASCRIPT;

echo $JS;