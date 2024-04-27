$(document).ready(function () {
  // Validate First Name
  $("#firstName").blur(function() {
    if ($(this).val() == "") {
        $(this).addClass("is-invalid");
        firstNameError = false;
    } else {
        $(this).removeClass("is-invalid");
        firstNameError = true;
    }
});

// Validate Last Name
$("#lastName").blur(function() {
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

 // Validate Gender
 $("input[name='gender']").change(function() {
  if ($("input[name='gender']:checked").val()) {
      $("#genderError").hide();
      genderError = true;
  } else {
      $("#genderError").show().text("**Please select a gender");
      genderError = false;
  }
});

    // Validate Email
    const email = document.getElementById("email");
    email.addEventListener("blur", () => {
        let regex = /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/;
        let s = email.value;
        if (regex.test(s)) {
            email.classList.remove("is-invalid");
            emailError = true;
        } else {
            email.classList.add("is-invalid");
            emailError = false;
        }
    });

    // Validate Password
    $("#passcheck").hide();
    let passwordError = true;
    $("#password").keyup(function () {
        validatePassword();
    });
    function validatePassword() {
        let passwordValue = $("#password").val();
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
      $("#languageSelect").change(function() {
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
    $("#culturalKnowledge").blur(function() {
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

    // Update the submit button validation check
    $("#submitbtn").click(function (e) {
      validateFirstName();
      validateLastName();
      validatePassword();
      validateAge();
      validateEmail();
      validateGender();
      validateLanguage();
      validateCulturalKnowledge();
      validateEducation();
      validateExperience();
      if (firstNameError && lastNameError && passwordError && ageError && emailError && genderError && languageError && culturalKnowledgeError && educationError && experienceError) {
          return true;
      } else {
          e.preventDefault();
          return false;
      }
  })