// import React from 'react'
import Layout from "../components/Layout"
import Header from '../components/Header'

import React from 'react';
import { Link } from 'react-router-dom';
export default function MyFormComponent() {


  return (
    <>
     <Layout>
     <Header/>
    <h2>home page</h2>
    <Link to="/user-list">View User List</Link>
     </Layout>
    </>
  );
}