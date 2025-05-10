function validateForm() {
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();
  const errorMessage = document.getElementById("error-message");

  errorMessage.textContent = "";

  if (!email) {
    errorMessage.textContent = "Please enter your email.";
    return false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    errorMessage.textContent = "Please enter a valid email address.";
    return false;
  }

  if (!password) {
    errorMessage.textContent = "Please enter your password.";
    return false;
  }

  return true;
}
