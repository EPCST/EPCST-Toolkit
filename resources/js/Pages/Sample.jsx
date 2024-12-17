import React, {useState} from "react";
import {Link, useForm} from '@inertiajs/react'
import {route} from 'ziggy-js';

const Sample = ({ users }) => {

  const { data, setData, post, processing, errors, reset } = useForm({
    name: '',
    email: '',
    password: '',
  })

  function formSubmit(e) {
    e.preventDefault();
    post('/');
    reset();
  }

  function deleteUser(userId) {
    post(route('userDelete', {user: userId}));
  }

  return (
    <>
      <Link href={route('login')}>Login</Link>

      {processing && <h1 className="text-2xl font-bold">Loading...</h1>}
      <form method="POST" onSubmit={formSubmit}>
        <input type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} name="name"/>
        <input type="text" value={data.email} onChange={(e) => setData('email', e.target.value)} name="email"/>
        <input type="text" value={data.password} onChange={(e) => setData('password', e.target.value)} name="password"/>

        <button>Submit</button>
      </form>

      <ul>
        {users.map((user, index) => (
          <li onClick={() => deleteUser(user.id)} key={index}>{user.name}</li>
        ))}
      </ul>
    </>
  );
};

export default Sample;
