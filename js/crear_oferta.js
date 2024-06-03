window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const modal = document.getElementById("modal");
    const modalMessage = document.getElementById("modal-message");
    const closeBtn = document.getElementsByClassName("close")[0];

    if (success === "true") {
        modalMessage.textContent = "Oferta creada exitosamente.";
        modal.style.display = "block";

        setTimeout(() => {
            window.location.href = "dashboard_admin.php";
        }, 3000);
    } else if (success === "false") {
        const error = urlParams.get('error');
        modalMessage.textContent = "Error al crear la oferta: " + error;
        modal.style.display = "block";
    }

    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
};
