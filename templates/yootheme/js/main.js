/* global window */

import UIkit from 'uikit';
import Header from './header';
import {isRtl, swap, ready} from 'uikit-util';

UIkit.component('header', Header);

if (isRtl) {

    const mixin = {

        init() {
            this.$props.pos = swap(this.$props.pos, 'left', 'right');
        }

    };

    UIkit.mixin(mixin, 'drop');
    UIkit.mixin(mixin, 'tooltip');
}

ready(() => {

    const {$load = [], $theme = {}} = window;

    function load(stack, config) {
        stack.length && stack.shift()(
            config, () => load(stack, config)
        );
    }

    load($load, $theme);
});
