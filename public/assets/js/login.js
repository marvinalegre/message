const button = document.querySelector(".submit-button");
const serverMessage = document.querySelector("#server-message");
const username = document.querySelector('[name="username"]');
const password = document.querySelector('[name="password"]');
const toggle = document.querySelector("#toggle-password");
const eye = toggle.querySelector("svg:first-child");
const eyeOff = toggle.querySelector("svg:last-child");
eyeOff.style.display = "none";

document.addEventListener("fx:after", () => {
  button.setAttribute("aria-busy", false);
  button.textContent = "Log in";
});

document.addEventListener("fx:before", () => {
  serverMessage.innerHTML = "";

  button.setAttribute("aria-busy", true);
  button.textContent = "Logging in...";
});

username.addEventListener("input", () => {
  serverMessage.innerHTML = "";
});
password.addEventListener("input", () => {
  serverMessage.innerHTML = "";
});

toggle.addEventListener("click", () => {
  const visible = password.type === "text";

  password.type = visible ? "password" : "text";
  eyeOff.style.display = visible ? "none" : "";
  eye.style.display = visible ? "" : "none";
});
