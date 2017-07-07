(function () {
   var TZ_INFO;
   // to get its own URL
   var prefURL = '';
   var scripts = document.getElementsByTagName('script');
   for (i = 0; i < scripts.length; i++) {
      var pos = scripts[i].src.search("/plugins/timezones/js/tz.js");
      if ( pos >= 0 ) {
         prefURL = scripts[i].src.substr(0, pos);
      }
   }

   $.ajax({
      url: prefURL + '/plugins/timezones/ajax/tz.php',
      cache: false,
      success: function (data, textStatus, jqXHR) {
         TZ_INFO = $.parseJSON(data);
         var time_zone_name = TZ_INFO['tz'];
         prefURL += '/front/preference.php?forcetab=PluginTimezonesUser$timezonestimezones';
         $(function () {
            var eltUL = $('#c_preference ul');
            if (eltUL) {
               eltUL.append("<li><a href='" + prefURL + "'>" + time_zone_name + "</a></li>");
            }
         });
      }
   });

})();
