document.querySelector('.toggle-password').addEventListener('click', function () {
    const passwordField = this.previousElementSibling;
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
  
    this.textContent = type === 'password' ? '👁️' : '🙈';
  });
  
function logout() {
  localStorage.clear();

  window.location.href = "index.php";
}