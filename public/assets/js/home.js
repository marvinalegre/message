window.addEventListener("load", () => {
  window.scrollTo(0, document.body.scrollHeight);
});

const messages = document.querySelector("#messages");
const userId = location.pathname.split("/").pop();

async function pollMessages() {
  const response = await fetch(`/messages/poll?user=1`);

  if (!response.ok) {
    return;
  }

  messages.innerHTML = await response.text();
}

setInterval(pollMessages, 2000);
