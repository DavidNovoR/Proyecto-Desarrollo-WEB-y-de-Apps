const toggle = document.getElementById("togglePassword");
const password = document.getElementById("password");

toggle.addEventListener("click", () => {
    const visible = password.type === "text";
    password.type = visible ? "password" : "text";
    toggle.src = visible
    ? "../img/icons/visibility_off.svg"
    : "../img/icons/visibility_on.svg";
});