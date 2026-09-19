document.addEventListener("fx:after", (evt) => {
  const button = document.querySelector("button");
  button.setAttribute("aria-busy", "false");
  button.textContent = "Sign up";

  const username = document.querySelector('[name="username"]');
  username.setAttribute("aria-invalid", "true");

  const redirect = evt.detail.cfg.response.headers.get("FX-Redirect");
  if (redirect) {
    window.location.href = redirect;
  }
});

document.addEventListener("fx:before", () => {
  const serverMessage = document.querySelector("#server-message");
  serverMessage.innerHTML = "";

  const username = document.querySelector('[name="username"]');
  username.setAttribute("aria-invalid", "");

  const button = document.querySelector("button");
  button.setAttribute("aria-busy", "true");
  button.textContent = "Signing up...";
});

// fixi disable elements extension
document.addEventListener("fx:init", (evt) => {
  if (evt.target.matches("[ext-fx-disable]")) {
    evt.target.addEventListener("fx:before", () => {
      const disableTargets = document.querySelectorAll(
        "[ext-fx-disable-target]",
      );
      disableTargets.forEach((target) => {
        target.disabled = true;
      });
      evt.target.addEventListener("fx:after", (afterEvt) => {
        if (afterEvt.target == evt.target) {
          disableTargets.forEach((target) => {
            target.disabled = false;
          });
        }
      });
    });
  }
});
