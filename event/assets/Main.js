import React from 'react';
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import MyFormComponent from './pages/Home';
import Blog from './pages/Blog';
import About from './pages/About';
import Contact from './pages/Contact';
import NotFound from './pages/NotFound';
import LoginForm from './pages/login';
function Main() {
  return (
    <Router>
      <Routes>
        <Route exact path="/" element={<MyFormComponent />} />
        <Route path="/blog" element={<Blog />} />
        <Route path="/about" element={<About />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/login" element={<LoginForm />} />
        <Route element={<NotFound />} />
      </Routes>
    </Router>
  );
}

export default Main;

if (document.getElementById('app')) {
  const rootElement = document.getElementById('app');
  const root = createRoot(rootElement);

  root.render(
    <StrictMode>
      <Main />
    </StrictMode>
  );
}
