const button = document.querySelector("button");
const serverMessage = document.querySelector("#server-message");
const username = document.querySelector('[name="username"]');

document.addEventListener("fx:after", () => {
  button.setAttribute("aria-busy", "false");
  button.textContent = "Sign up";
});

document.addEventListener("fx:before", () => {
  serverMessage.innerHTML = "";

  button.setAttribute("aria-busy", "true");
  button.textContent = "Signing up...";
});

username.addEventListener("input", () => {
  serverMessage.innerHTML = "";
});
