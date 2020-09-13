require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

Number.prototype.neededDecimals = function (min, max) {
        var number = this.toString().replace(/0+$/, '');
        var pos = number.indexOf('.') + 1;
        var decimals = pos == 0 ? 0 : number.substring(pos).length;

        return (decimals < min ? min : (decimals > max ? max : decimals));
};

/**
 * Number.prototype.format(n, x, s, c)
 *
 * @param integer n: length of decimal
 * @param integer x: length of whole part
 * @param mixed   s: sections delimiter
 * @param mixed   c: decimal delimiter
 */
Number.prototype.format = function(decimals, dec_point, thousands_sep) {
    var number = (this + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + (Math.round(n * k) / k).toFixed(prec);
    };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
};

import Flash from './plugins/flash.js';

Vue.use(Flash);

Vue.component('flash-message', require('./components/partials/flashmessage.vue').default);

Vue.component('courses-table', require('./components/courses/table.vue').default);
Vue.component('item-table', require('./components/item/table.vue').default);
Vue.component('partner-table', require('./components/partner/table.vue').default);
Vue.component('partner-healthdata-table', require('./components/partner/healthdata/table.vue').default);
Vue.component('receipt-table', require('./components/receipt/table.vue').default);
Vue.component('receipt-line-table', require('./components/receipt/line/table.vue').default);
Vue.component('receipt-edit-address', require('./components/receipt/edit/address.vue').default);
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