document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('.php-email-form');

  forms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const loading = form.querySelector('.loading');
      const errorMessage = form.querySelector('.error-message');
      const sentMessage = form.querySelector('.sent-message');

      loading.style.display = 'block';
      errorMessage.style.display = 'none';
      sentMessage.style.display = 'none';

      const formData = new FormData(form);

      fetch(form.action, {
        method: form.method,
        body: formData,
        headers: { 'Accept': 'application/json' }
      })
        .then(response => {
          loading.style.display = 'none';
          if (response.ok) {
            sentMessage.style.display = 'block';
            form.reset();
          } else {
            response.json().then(data => {
              errorMessage.textContent = data.error || 'Form submission failed.';
              errorMessage.style.display = 'block';
            }).catch(() => {
              errorMessage.textContent = 'Form submission failed.';
              errorMessage.style.display = 'block';
            });
          }
        })
        .catch(() => {
          loading.style.display = 'none';
          errorMessage.textContent = 'Could not connect to the server.';
          errorMessage.style.display = 'block';
        });
    });
  });
});
