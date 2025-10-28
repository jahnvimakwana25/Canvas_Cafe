const triggers = [
  'primary',
  'secondary',
  'success',
  'danger',
  'warning',
  'info',
  'light',
  'dark',
];

triggers.forEach((trigger) => {
  const alertElement = document.getElementById('alert-' + trigger); 

  document.getElementById(trigger).addEventListener('click', () => {
    // Show the corresponding alert by toggling the 'show' class
    alertElement.classList.add('show');

    // Hide the alert after 5 seconds
    setTimeout(() => {
      alertElement.classList.remove('show');
    }, 4000);
  });

  // Close the alert when the close button is clicked
  closeButton.addEventListener('click', () => {
    alertElement.classList.remove('show');
  });
});