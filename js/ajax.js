$(document).ready(function() {
    $('#contactForm').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission behavior

        var formData = $(this).serialize(); // Serialize the form data
        formData += '&send_contact=true';

        $.ajax({
            method: 'POST',
            url: 'http://localhost:3000/PortFolio/PortFolio/php/contact.php',
            data: formData,
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.success) {
                    displaySuccessMessage(response.message);
                } else if (response.email_sent) {
                    console.log(response.message); // Log email sent message
                } else {
                    displayErrorMessage(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                displayErrorMessage('An error occurred. Please try again.');
            }
        });
    });

    function displaySuccessMessage(message) {
        var successMessage = $('<div class="alert alert-success" role="alert">' + message + '</div>');
        $('#responseMessage').empty().append(successMessage);
    }

    function displayErrorMessage(message) {
        var errorMessage = $('<div class="alert alert-danger" role="alert">' + message + '</div>');
        $('#responseMessage').empty().append(errorMessage);
    }
});
