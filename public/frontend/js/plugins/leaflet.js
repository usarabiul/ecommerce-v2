(function ($) {
    'use strict';
    // basic map
    var leafletBasic = function () {
        if ($('#map-basic').length) {
            var mymap = L.map('map-basic').setView([51.505, -0.09], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(mymap);

            L.marker([51.5, -0.09]).addTo(mymap)
                .bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();
        }
    }
    // initialize
    $(document).ready(function(){
        leafletBasic();
    });
})(jQuery);