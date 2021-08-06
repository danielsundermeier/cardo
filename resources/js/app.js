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

Vue.component('comments', require('./components/comment/index.vue').default);
Vue.component('courses-table', require('./components/courses/table.vue').default);
Vue.component('courses-participant-table', require('./components/courses/participant/table.vue').default);
Vue.component('courses-participation-table', require('./components/courses/participation/table.vue').default);
Vue.component('courses-date-table', require('./components/courses/date/table.vue').default);
Vue.component('courses-date-participation-table', require('./components/courses/date/participation/table.vue').default);
Vue.component('item-table', require('./components/item/table.vue').default);
Vue.component('partner-table', require('./components/partner/table.vue').default);
Vue.component('partner-healthdata-table', require('./components/partner/healthdata/table.vue').default);
Vue.component('partner-course-table', require('./components/partner/course/table.vue').default);
Vue.component('partner-history-table', require('./components/partner/history/table.vue').default);
Vue.component('partner-history-edit', require('./components/partner/history/edit.vue').default);
Vue.component('partner-staff-workingtime-show', require('./components/partner/staff/workingtime/show.vue').default);
Vue.component('partner-receipt-table', require('./components/partner/receipt/table.vue').default);
Vue.component('partner-participations-corrections-table', require('./components/partner/participations/corrections/table.vue').default);
Vue.component('receipt-table', require('./components/receipt/table.vue').default);
Vue.component('receipt-line-table', require('./components/receipt/line/table.vue').default);
Vue.component('receipt-edit-address', require('./components/receipt/edit/address.vue').default);
Vue.component('task-table', require('./components/task/table.vue').default);
Vue.component('task-category-table', require('./components/task/category/table.vue').default);
Vue.component('unit-table', require('./components/unit/table.vue').default);
Vue.component('userfileable-table', require('./components/userfileable/table.vue').default);
Vue.component('userfileable-single', require('./components/userfileable/single.vue').default);
Vue.component('user-table', require('./components/user/table.vue').default);
Vue.component('user-workingtime-table', require('./components/user/workingtime/table.vue').default);
Vue.component('workingtime-table', require('./components/workingtime/table.vue').default);


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