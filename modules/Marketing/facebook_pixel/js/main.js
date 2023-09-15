function save_facebook_pixel() {
	"use strict"; 
    $.post(admin_url + 'facebook_pixel/save', {
        admin_area: $('#facebook_pixel_admin_area').val(),
        clients_area: $('#facebook_pixel_clients_area').val(),
        clients_and_admin: $('#facebook_pixel_clients_and_admin_area').val(),
    }).done(function(response) {
        window.location = admin_url + 'facebook_pixel';
    });
}

function enable_facebook_pixel() {
	"use strict"; 
    $.post(admin_url + 'facebook_pixel/enable', {}).done(function() {
        window.location = admin_url + 'facebook_pixel';
    });
}

function disable_facebook_pixel() {
	"use strict"; 
    $.post(admin_url + 'facebook_pixel/disable', {}).done(function() {
        window.location = admin_url + 'facebook_pixel';
    });
}