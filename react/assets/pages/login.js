import React from "react";
import Layout from "../components/Layout";
import Header from "../components/Header";
import { useState } from "react";
function FormRegester() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [tele, setTele] = useState("");
  const [ville, setVille] = useState("");
  const [cin, setCin] = useState("");

  const handleSubmit = (e) => {
    e.preventDefault();

    // Create an object with the form data
    const formData = {
      email: email,
      password: password,
      tele: tele,
      ville: ville,
      cin: cin,
    };

    // Make an HTTP POST request to the Symfony backend
    fetch("/api/user", { // Update the URL to match your Symfony route
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
    .then((data) => {
        // Redirect the user to the home page
        window.location.href = "/";
        console.log(data);
        })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  return (
    <Layout>
      <Header />
      <div className="container">
        <form onSubmit={handleSubmit}>
          <label>
            Email:
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </label>
          <br />
          <label>
            Password:
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </label>
          <br />
          <label>
            Tele:
            <input
              type="text"
              value={tele}
              onChange={(e) => setTele(e.target.value)}
            />
          </label>
          <br />
          <label>
            Ville:
            <input
              type="text"
              value={ville}
              onChange={(e) => setVille(e.target.value)}
            />
          </label>
          <br />
          <label>
            Cin:
            <input
              type="text"
              value={cin}
              onChange={(e) => setCin(e.target.value)}
            />
          </label>
          <br />
          <button type="submit">Register</button>
        </form>
      </div>
    </Layout>
  );
}

export default FormRegester;