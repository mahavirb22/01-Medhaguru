document.addEventListener('DOMContentLoaded', () => {
    const togglePasswordIcon = document.getElementById('toggle-password');

    togglePasswordIcon.addEventListener('click', () => {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
    });
});