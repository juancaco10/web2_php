function confirmLogout(event) {
    if (!confirm("¿Estás seguro de que quieres cerrar sesión?")) {
        event.preventDefault();
    }
}

window.onload = function() {
    document.getElementById('logout-link').addEventListener('click', confirmLogout);
};