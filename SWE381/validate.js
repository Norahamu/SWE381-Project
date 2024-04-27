
document.getElementById('language-form').addEventListener('change', function(event) {
  let target = event.target;
  if (target.type === 'checkbox') {
    let selectElement = target.parentNode.nextElementSibling;
    selectElement.disabled = !target.checked;
    if (!target.checked) {
      selectElement.value = ''; 
    }
  }
});

$(document).ready(function () {
  let firstNameError = lastNameError = ageError = genderError = emailError = passwordError = languageError = culturalKnowledgeError = educationError = experienceError = priceError = false;

  // Validate First Name
  $("#fname").blur(function() {
      if ($(this).val() == "") {
          $(this).addClass("is-invalid");
          firstNameError = false;
      } else {
          $(this).removeClass("is-invalid");
          firstNameError = true;
      }
  });

  // Validate Last Name
  $("#lname").blur(function() {
      if ($(this).val() == "") {
          $(this).addClass("is-invalid");
          lastNameError = false;
      } else {
          $(this).removeClass("is-invalid");
          lastNameError = true;
      }
  });

  // Validate Age
  $("#age").blur(function() {
    let ageValue = parseInt($(this).val());
    if (!ageValue || ageValue < 18) {
        $(this).addClass("is-invalid");
        $("#ageError").show().text("**You must be at least 18 years old");
        ageError = false;
    } else {
        $(this).removeClass("is-invalid");
        $("#ageError").hide();
        ageError = true;
    }
  });

  // Validate Email
  $("#email").blur(function () {
      let regex = /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/;
      if (regex.test($(this).val())) {
          $(this).removeClass("is-invalid");
          emailError = true;
      } else {
          $(this).addClass("is-invalid");
          emailError = false;
      }
  });

    // Validate Password
    $("#passcheck").hide();
    let passwordError = true;
    $("#psw").keyup(function () {
        validatePassword();
    });
    function validatePassword() {
        let passwordValue = $("#psw").val();
        if (passwordValue.length == "") {
            $("#passcheck").show();
            $("#passcheck").html("**Password cannot be empty");
            passwordError = false;
            return false;
        }
        if (passwordValue.length < 8 || passwordValue.length > 10) {
            $("#passcheck").show();
            $("#passcheck").html("**Length of your password must be between 8 and 10");
            passwordError = false;
            return false;
        } else {
            $("#passcheck").hide();
            passwordError = true;
        }
    }

      // Validate Language and Proficiency
      $("#language-form").change(function() {
        if ($(this).val()) {
            $("#languageError").hide();
            $("#proficiency").prop('disabled', false);
            if ($("#proficiency").val() == "") {
                $("#proficiencyError").show().text("**Please select the proficiency level");
                languageError = false;
            } else {
                $("#proficiencyError").hide();
                languageError = true;
            }
        } else {
            $("#languageError").show().text("**Please select a language");
            $("#proficiency").prop('disabled', true);
            languageError = false;
        }
    });

    $("#proficiency").change(function() {
        if ($(this).val()) {
            $("#proficiencyError").hide();
            languageError = true;
        } else {
            $("#proficiencyError").show().text("**Please select the proficiency level");
            languageError = false;
        }
    });

    // Validate Cultural Knowledge
    $("#cultural-knowledge").blur(function() {
        if ($(this).val() == "") {
            $(this).addClass("is-invalid");
            culturalKnowledgeError = false;
        } else {
            $(this).removeClass("is-invalid");
            culturalKnowledgeError = true;
        }
    });

    // Validate Education
    $("#education").blur(function() {
        if ($(this).val() == "") {
            $(this).addClass("is-invalid");
            educationError = false;
        } else {
            $(this).removeClass("is-invalid");
            educationError = true;
        }
    });

    // Validate Experience
    $("#experience").blur(function() {
        if ($(this).val() == "") {
            $(this).addClass("is-invalid");
            experienceError = false;
        } else {
            $(this).removeClass("is-invalid");
            experienceError = true;
        }
    });
    $("#price").blur(function() {
      let price = parseFloat($(this).val());
      if (isNaN(price) || price < 50) {
          $(this).addClass("is-invalid");
          priceError = false;
      } else {
          $(this).removeClass("is-invalid");
          priceError = true;
      }
  });

  // Submit Button Validation
  $("#submitbtn").click(function (e) {
      if (firstNameError && lastNameError && passwordError && ageError && emailError && genderError && languageError && culturalKnowledgeError && educationError && experienceError && priceError) {
          return true;
      } else {
          e.preventDefault();
          return false;
      }
  });
});