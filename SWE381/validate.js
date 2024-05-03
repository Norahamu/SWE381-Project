document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.inp-cbx').forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
          let selectElement = checkbox.closest('.language-selection').querySelector('.form-control');
          selectElement.disabled = !checkbox.checked;
          if (!checkbox.checked) {
              selectElement.value = '';
          }
      });
  });

  const form = document.querySelector('.php-email-form');
  form.addEventListener('submit', function(event) {
      let isValid = true;
      document.querySelectorAll('.required input, .required select, .required textarea').forEach(function(input) {
          if (!input.value) {
              isValid = false;
              input.classList.add('is-invalid'); 
              showAlert(input); 
          } else {
              input.classList.remove('is-invalid');
              removeAlert(input); 
          }
      });

      if (!isValid) {
          event.preventDefault(); 
      }
  });

  function showAlert(input) {
      const alertIcon = document.createElement('span');
      alertIcon.className = 'alert-icon';
      alertIcon.innerHTML = '&#9888;'; // Warning sign
      if (!input.parentNode.querySelector('.alert-icon')) {
          input.parentNode.appendChild(alertIcon);
      }
  }

  function removeAlert(input) {
      let alertIcon = input.parentNode.querySelector('.alert-icon');
      if (alertIcon) {
          alertIcon.remove();
      }
  }
});
/*Selector('.php-email-form').addEventListener('submit', function(event) {
  const checkboxes = document.querySelectorAll('.inp-cbx');
  const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

  if (!isChecked) {
    alert('Please select at least one language.');
    event.preventDefault(); 
  }
});*/