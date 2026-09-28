/* =========================================================
   General client-side validation (JavaScript)
   ========================================================= */

// Registration form validation (runs before submit).
function validateRegister() {
  const name     = document.getElementById('name').value.trim();
  const email    = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const confirm  = document.getElementById('confirm').value;
  const emailRe  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (name === '') {
    alert('Please enter your name.');
    return false;
  }
  if (!emailRe.test(email)) {
    alert('Please enter a valid email address.');
    return false;
  }
  if (password.length < 6) {
    alert('Password must be at least 6 characters long.');
    return false;
  }
  if (password !== confirm) {
    alert('Passwords do not match.');
    return false;
  }
  return true;
}
