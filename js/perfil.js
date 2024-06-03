function togglePasswordVisibility() {
    var passwordField = document.getElementById('password');
    var toggleButton = document.getElementById('togglePassword');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.textContent = 'Ocultar';
    } else {
        passwordField.type = 'password';
        toggleButton.textContent = 'Mostrar';
    }
}

function confirmLogout(event) {
    if (!confirm("¿Estás seguro de que quieres cerrar sesión?")) {
        event.preventDefault();
    }
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        alert('¡Perfil actualizado correctamente!');
    }
    document.getElementById('logout-link').addEventListener('click', confirmLogout);
};
