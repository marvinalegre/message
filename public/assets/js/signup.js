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
  button.textContent = "Sign up";
});

document.addEventListener("fx:before", () => {
  serverMessage.innerHTML = "";

  button.setAttribute("aria-busy", true);
  button.textContent = "Signing up...";
});

document.addEventListener("fx:swapped", () => {
  if (/Invalid/.test(serverMessage.textContent)) {
    username.setAttribute("aria-invalid", true);
  }
});

username.addEventListener("input", () => {
  serverMessage.innerHTML = "";

  if (username.getAttribute("aria-invalid") === "true") {
    username.setAttribute("aria-invalid", "");
  }
});

toggle.addEventListener("click", () => {
  const visible = password.type === "text";

  password.type = visible ? "password" : "text";
  eyeOff.style.display = visible ? "none" : "";
  eye.style.display = visible ? "" : "none";
});
