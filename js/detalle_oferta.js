document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalConfirm');
    const modalHeader = document.getElementById('modalHeader');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const modalCancelBtn = document.getElementById('modalCancelBtn');

    const showModal = (message, onConfirm, successMessage) => {
        modalHeader.textContent = message;
        modal.style.display = 'block';

        modalConfirmBtn.onclick = function() {
            modal.style.display = 'none';
            onConfirm(successMessage);
        };

        modalCancelBtn.onclick = function() {
            modal.style.display = 'none';
        };
    };

    const eliminarPostulacionBtn = document.getElementById('eliminarPostulacion');
    if (eliminarPostulacionBtn) {
        eliminarPostulacionBtn.addEventListener('click', function() {
            const ofertaId = this.getAttribute('data-id');
            showModal('¿Estás seguro de que deseas eliminar tu postulación?', function(successMessage) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'eliminar_postulacion.php?id=' + ofertaId, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(successMessage);
                        location.reload();
                    }
                };
                xhr.send();
            }, 'Postulación eliminada correctamente');
        });
    }

    const postularmeBtn = document.getElementById('postularme');
    if (postularmeBtn) {
        postularmeBtn.addEventListener('click', function() {
            const ofertaId = this.getAttribute('data-id');
            showModal('¿Estás seguro de que deseas postularte a esta oferta?', function(successMessage) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'postularse_ajax.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(successMessage);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                };
                xhr.send('id=' + ofertaId);
            }, 'Postulación exitosa');
        });
    }

    function confirmLogout(event) {
        event.preventDefault();
        showModal('¿Estás seguro de que quieres cerrar sesión?', function(successMessage) {
            window.location.href = 'logout.php';
        }, 'Sesión cerrada correctamente');
    }

    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', confirmLogout);
    }
});
