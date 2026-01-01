document.addEventListener("DOMContentLoaded", () => {
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const user = document.getElementById('user');
    const email = document.getElementById('email');
    const pass = document.getElementById('password');
    const form = document.getElementById('register-form');
    const error = document.getElementById('register-error');
    const emailRegex = /^[\w\d._-ñÑ]+@[\w\d._-ñÑ]+\.\w+$/;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!nombre.value || !apellidos.value || !user.value || !email.value || !pass.value) {
            mostrarError('Rellene todos los campos');
            return;
        }

        if (!emailRegex.test(email.value.trim())) {
            mostrarError("Introduce un correo electrónico válido");
            return;
        }

        error.style.display = 'none';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.status === "error") {
                mostrarError(data.message);
            } else if (data.status === "success") {
                window.location.href = "/Proyecto-Desarrollo-WEB-y-de-Apps/Backend/PHP/index.php";
            }

        } catch (err) {
            mostrarError("Error de conexión con el servidor");
        }
    });

    function mostrarError(message) {
        error.textContent = message;
        error.style.display = 'block';
    }
});
