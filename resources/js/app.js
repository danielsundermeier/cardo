require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

import Flash from './plugins/flash.js';

Vue.use(Flash);

Vue.component('flash-message', require('./components/partials/flashmessage.vue').default);

Vue.component('courses-table', require('./components/courses/table.vue').default);
Vue.component('item-table', require('./components/item/table.vue').default);
Vue.component('partner-table', require('./components/partner/table.vue').default);
Vue.component('unit-table', require('./components/unit/table.vue').default);


$(document).ready( function () {
    const app = new Vue({
        el: '#app',
        components: {

        },
    });

    $('#menu-toggle').click(function() {
        $('#nav, #content-container').toggleClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });


    $('.collapse', 'nav#nav').on('show.bs.collapse', function(){
        $('a[data-target="#' + $(this).attr('id') +'"] i.fas', 'nav#nav').toggleClass("fa-caret-right fa-caret-down");
    }).on('hide.bs.collapse', function(){
        $('a[data-target="#' + $(this).attr('id') +'"] i.fas', 'nav#nav').toggleClass("fa-caret-down fa-caret-right");
    });
});