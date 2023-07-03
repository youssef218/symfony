import React, { useState } from 'react';

function LoginForm() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = (event) => {
    event.preventDefault();

    fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        username,
        password,
      }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Invalid username or password');
        }
        // Redirect to home page
        window.location.href = '/';
      })
      .catch((error) => {
        console.error(error);
        // Display error message
        setError('Login failed. Please check your username and password and try again.');
      });
  };

  return (
    <form onSubmit={handleSubmit}>
      {error && <div className="alert alert-danger">{error}</div>}

      <h1 className="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label htmlFor="inputUsername">Username</label>
      <input
        type="text"
        value={username}
        onChange={(event) => setUsername(event.target.value)}
        name="username"
        id="inputUsername"
        className="form-control"
        autoComplete="username"
        required
        autoFocus
      />
      <label htmlFor="inputPassword">Password</label>
      <input
        type="password"
        value={password}
        onChange={(event) => setPassword(event.target.value)}
        name="password"
        id="inputPassword"
        className="form-control"
        autoComplete="current-password"
        required
      />

      <input
        type="hidden"
        name="_csrf_token"
        value="{{ csrf_token('authenticate') }}"
      />

      {/*
        Uncomment this section and add a remember_me option below your firewall to activate remember me functionality.
        See https://symfony.com/doc/current/security/remember_me.html

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" name="_remember_me"> Remember me
            </label>
        </div>
      */}

      <button className="btn btn-lg btn-primary" type="submit">
        Sign in
      </button>
    </form>
  );
}

export default LoginForm;