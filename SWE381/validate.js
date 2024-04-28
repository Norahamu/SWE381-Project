
    $('#signupForm').on('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);  // 'this' form element

        fetch('signupLearner.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === "duplicate_email_error") {
                alert("This email is already registered. Please use another email.");
            } else if (data === "Signup successful!") {
                alert("Signup successful!");
            } else {
                alert(data);
            }
        })
        .catch(error => {
            console.error('Error during signup:', error);
            alert("There was an error processing your request. Please try again.");
        });
    });
