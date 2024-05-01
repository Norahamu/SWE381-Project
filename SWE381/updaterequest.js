$(document).ready(function() {
    $('.button1').click(function() {
        const button = $(this);
        const partnerId = button.data('partner-id');
        const requestId = button.data('req-id');
        const schedule = button.data('req-sch');
        const duration = button.data('req-dur');

        $.ajax({
            url: 'update_request.php',
            type: 'POST',
            data: {
                action: 'accept',
                partnerId: partnerId,
                requestId: requestId,
                schedule: schedule,
                duration: duration
            },
            success: function(response) {
            },
            error: function() {
                alert('Error processing your request');
            }
        });
    });

    $('.button2').click(function() {
        const button = $(this);
        const partnerId = button.data('partner-id');
        const requestId = button.data('req-id');

        $.ajax({
            url: 'update_request.php',
            type: 'POST',
            data: {
                action: 'decline',
                partnerId: partnerId,
                requestId: requestId
            },
            success: function(response) {
                alert('Request declined!');
            },
            error: function() {
                alert('Error processing your request');
            }
        });
    });
});