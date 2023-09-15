$(function() {
    'use strict';
    var dateFormat = app.options.date_format;

    var meetingPickerOptions = {
        dayOfWeekStart: app.options.calendar_first_day,
        minDate: 0,
        format: dateFormat,
        closeOnDateSelect: 0,
        closeOnTimeSelect: 1,
        validateOnBlur: false
    };

    if (app.options.time_format == 24) {
        dateFormat = dateFormat + ' H:i';
    } else {
        dateFormat = dateFormat + ' g:i A';
        meetingPickerOptions.formatTime = 'g:i A';
    }

    meetingPickerOptions.format = dateFormat;

    $('.meeting-date').datetimepicker(meetingPickerOptions);
}(jQuery));