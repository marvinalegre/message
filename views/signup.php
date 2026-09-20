<main class="container">
  <h1>Sign up</h1>

  <form
    fx-action="/signup"
    fx-method="post"
    fx-target="#server-message"
    class="auth-form"
    ext-fx-disable
  >
    <label>
      Username
      <input name="username" required ext-fx-disable-target />
      <small id="server-message"></small>
    </label>

    <label>
      Password
      <input name="password" type="password" required ext-fx-disable-target />
    </label>

    <button ext-fx-disable-target>Sign up</button>
  </form>

  <p>
    Already have an account?
    <a href="/login">Log in</a>
  </p>
</main>
