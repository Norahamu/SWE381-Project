document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.querySelector('.php-email-form');
    const fname = document.getElementById('fname');
    const lname = document.getElementById('lname');
    const age = document.getElementById('age');
    const email = document.getElementById('email');
    const password = document.getElementById('psw');
    const gender = document.querySelector('[name="gender"]');
    const culturalKnowledge = document.getElementById('cultural-knowledge');
    const education = document.getElementById('education'); // Corrected typo
    const experience = document.getElementById('experience');
    const location = document.getElementById('location');
    const price = document.getElementById('price');
  
    // Add validation for the signup form
    signupForm.addEventListener('submit', function(event) {
      let formIsValid = validateSignUpForm();
      if (!formIsValid) {
        event.preventDefault();
      }
    });
  
    // Validate language form
    const languageForm = document.getElementById('language-form');
    const languageCheckboxes = document.querySelectorAll('.inp-cbx');
  
    languageCheckboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        let selectElement = checkbox.closest('.language-selection').querySelector('.form-control');
        selectElement.disabled = !checkbox.checked;
        if (!checkbox.checked) {
          selectElement.value = '';
        }
      });
    });
  
    languageForm.addEventListener('submit', function(event) {
      let isLanguageValid = validateLanguageForm();
      if (!isLanguageValid) {
        event.preventDefault();
      }
    });
  
    function validateSignUpForm() {
      let isValid = true;
  
      // Various field validations
      if(fname.value.trim() === '') {
        alert('First name is required.');
        isValid = false;
      }
  
      if(lname.value.trim() === '') {
        alert('Last name is required.');
        isValid = false;
      }
  
      if(age.value.trim() === '' || parseInt(age.value) < 18) {
        alert('You must be at least 18 years old.');
        isValid = false;
      }
  
      if(email.value.trim() === '') {
        alert('Email is required.');
        isValid = false;
      }
  
      if(password.value.trim() === '' || !password.value.match(/^(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,15}$/)) {
        alert('Password must be 8-15 characters long and include at least one special character.');
        isValid = false;
      }
  
      if(gender.value.trim() === '') {
        alert('Gender selection is required.');
        isValid = false;
      }
  
      if(culturalKnowledge.value.trim() === '') {
        alert('Cultural knowledge is required.');
        isValid = false;
      }
  
      if(education.value.trim() === '') {
        alert('Education is required.');
        isValid = false;
      }
  
      if(experience.value.trim() === '') {
        alert('Experience is required.');
        isValid = false;
      }
  
      if(location.value.trim() === '') {
        alert('Location is required.');
        isValid = false;
      }
  
      if(price.value.trim() === '' || parseInt(price.value) < 50) {
        alert('Price per session must be at least 50 SAR.');
        isValid = false;
      }
  
      return isValid;
    }
  
    function validateLanguageForm() {
      let isLanguageSelected = false;
      let isProficiencySelected = true;
  
      languageCheckboxes.forEach(function(checkbox) {
        const proficiencySelect = checkbox.closest('.language-selection').querySelector('.form-control');
        if (checkbox.checked) {
          isLanguageSelected = true;
          if (proficiencySelect.value === '') {
            isProficiencySelected = false;
          }
        }
      });
  
      if (!isLanguageSelected || !isProficiencySelected) {
        alert('Please select at least one language and set proficiency for all selected languages.');
        return false;
      }
  
      return true;
    }
  });