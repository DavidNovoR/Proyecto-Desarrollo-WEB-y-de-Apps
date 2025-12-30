const email = document.getElementById('email');
const pass = document.getElementById('password');
const form = document.getElementById('login-form');
const emailRegex = /^[\w\d._-ñÑ]+@[\w\d._-ñÑ]+\.\w+$/;
const error = document.getElementById('login-error');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // VALIDACIONES (sin tocar)
    if (!email.value && pass.value) {
        mostrarError('Introduzca un correo electrónico');
        return;
    }
    if (!pass.value && email.value) {
        mostrarError('Introduzca una contraseña');
        return;
    }
    if (!email.value && !pass.value) {
        mostrarError('Introduzca un correo electrónico y una contraseña');
        return;
    }

    if (!emailRegex.test(email.value.trim())) {
        mostrarError("Introduce un correo electrónico válido");
        return;
    }

    error.style.display = "none";

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
            window.location.href = "../../Backend/PHP/index.php";
        }

    } catch (err) {
        mostrarError("Error de conexión con el servidor");
    }
});

function mostrarError(message) {
    error.textContent = message;
    error.style.display = 'block';
}
