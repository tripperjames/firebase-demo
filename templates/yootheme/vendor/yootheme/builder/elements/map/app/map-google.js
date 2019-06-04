/* global google */

import {Promise} from 'uikit-util';

let defer;

const gmapsapi = function () {

    if (!defer) {

        defer = new Promise(resolve => {
            google.load('maps', '3', {
                other_params: `key=${window.$google_maps}`,
                callback: resolve
            });
        });

    }

    return defer;
};

export default function (el, {type, center, zoom, zooming, dragging, controls, markers, styler_invert_lightness, styler_hue, styler_saturation, styler_lightness, styler_gamma, popup_max_width}) {

    gmapsapi().then(() => {

        const _center = new google.maps.LatLng(center.lat, center.lng);

        const map = new google.maps.Map(el, {
            zoom: Number(zoom),
            center: _center,
            mapTypeId: google.maps.MapTypeId[type.toUpperCase()],
            disableDefaultUI: !controls,
            scrollwheel: Boolean(zooming),
            gestureHandling: dragging ? 'auto' : 'none'
        });

        markers && markers.forEach(({lat, lng, content, show_popup}) => {

            const marker = new google.maps.Marker({map, position: new google.maps.LatLng(lat, lng)});

            if (content) {

                const popup = new google.maps.InfoWindow({content, maxWidth: (popup_max_width ? parseInt(popup_max_width, 10) : 300)});

                google.maps.event.addListener(marker, 'click', () => popup.open(map, marker));

                if (show_popup) {
                    popup.open(map, marker);
                }
            }

        });

        const styledMap = new google.maps.StyledMapType([
            {
                featureType: 'all',
                elementType: 'all',
                stylers: [
                    {invert_lightness: styler_invert_lightness},
                    {hue: styler_hue},
                    {saturation: styler_saturation},
                    {saturation: styler_saturation},
                    {lightness: styler_lightness},
                    {gamma: styler_gamma}
                ]
            },
            {
                featureType: 'poi',
                stylers: [{visibility: 'off'}]
            }
        ], {name: 'Styled'});

        map.mapTypes.set('styled_map', styledMap);

        if (type.toUpperCase() === 'ROADMAP') {
            map.setMapTypeId('styled_map');
        }

    });

}
