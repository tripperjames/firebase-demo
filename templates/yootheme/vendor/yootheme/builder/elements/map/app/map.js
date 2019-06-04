import UIkit from 'uikit';
import {isInView} from 'uikit-util';
import MapGoogle from './map-google';
import MapLeaflet from './map-leaflet';

UIkit.component('map', {

    props: {
        map: Object
    },

    update: {

        write(data) {

            if (document.readyState === 'loading') {
                this.$emit();
                return;
            }

            if (data.created || this.map.lazyload && !isInView(this.$el, window.innerHeight / 2, 0, true)) {
                return;
            }

            if (window.L) {
                MapLeaflet(this.$el, this.map);
            } else if (window.google && window.$google_maps) {
                MapGoogle(this.$el, this.map);
            }

            data.created = true;
        },

        events: ['scroll']
    }

});
