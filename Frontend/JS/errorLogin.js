document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("login-form");
    const errorTag = document.getElementById("login-error");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const response = await fetch(form.action, {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.status === "error") {
            errorTag.textContent = data.message;
            errorTag.style.display = "block";
        } else if (data.status === "success") {
            window.location.href = "../../Backend/PHP/index.php"; 
        }
    });
});
