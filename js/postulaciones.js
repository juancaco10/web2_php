document.addEventListener('DOMContentLoaded', function() {
    function confirmLogout(event) {
        event.preventDefault();
        const modal = document.getElementById('modalConfirm');
        const modalHeader = document.getElementById('modalHeader');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        const modalCancelBtn = document.getElementById('modalCancelBtn');

        const showModal = (message, onConfirm) => {
            modalHeader.textContent = message;
            modal.style.display = 'block';

            modalConfirmBtn.onclick = function() {
                modal.style.display = 'none';
                onConfirm();
            };

            modalCancelBtn.onclick = function() {
                modal.style.display = 'none';
            };
        };

        showModal('¿Estás seguro de que quieres cerrar sesión?', function() {
            window.location.href = 'logout.php';
        });
    }

    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', confirmLogout);
    }
});
