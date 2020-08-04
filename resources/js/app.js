require('./bootstrap');

window.Vue = require('vue');
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