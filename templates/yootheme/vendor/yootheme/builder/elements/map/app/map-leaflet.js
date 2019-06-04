/* global L */

export default function (el, {type, center, zoom, zooming, dragging, controls, markers, popup_max_width}) {

    const map = L.map(el, {
        zoom,
        center,
        dragging,
        zoomControl: controls,
        touchZoom: zooming,
        scrollWheelZoom: zooming,
        doubleClickZoom: zooming
    });

    if (type === 'satellite') {

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; <a href="https://www.esri.com">Esri</a> | DigitalGlobe, GeoEye, i-cubed, USDA, USGS, AEX, Getmapping, Aerogrid, IGN, IGP, swisstopo, and the GIS User Community'
        }).addTo(map);

    } else {

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

    }

    markers && markers.forEach(({lat, lng, content, show_popup}) => {

        const marker = L.marker({lat, lng}).addTo(map);

        if (content) {

            const popup = L.popup({maxWidth: (popup_max_width ? parseInt(popup_max_width, 10) : 300)}).setContent(content);

            marker.bindPopup(popup);

            if (show_popup) {
                marker.openPopup();
            }
        }

    });

}
