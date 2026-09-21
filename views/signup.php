<main class="container">
  <h1>Sign up</h1>

  <form
    fx-action="/signup"
    fx-method="post"
    fx-target="#server-message"
    fx-swap="innerHTML"
    ext-fx-disable
    class="auth-form"
  >
    <label for="username"> Username </label>
    <input id="username" name="username" required ext-fx-disable-target />
    <small id="server-message"></small>

    <label for="password"> Password </label>
    <div class="password-wrapper">
      <input
        id="password"
        name="password"
        type="password"
        required
        ext-fx-disable-target
      />
      <button type="button" id="toggle-password" aria-label="Show password">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path
            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"
          />
          <circle cx="12" cy="12" r="3" />
        </svg>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path
            d="M10.733 5.076A10.744 10.744 0 0 1 12 5c5 0 8.27 4.5 9 7a13.16 13.16 0 0 1-1.085 2.24"
          />
          <path
            d="M6.61 6.61A13.526 13.526 0 0 0 3 12c.73 2.5 4 7 9 7a9.8 9.8 0 0 0 4.39-1.04"
          />
          <path d="M3 3l18 18" />
        </svg>
      </button>
    </div>

    <button ext-fx-disable-target>Sign up</button>
  </form>

  <p>
    Already have an account?
    <a href="/login">Log in</a>
  </p>
</main>
