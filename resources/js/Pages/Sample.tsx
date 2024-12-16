import React, {useState} from "react";
import { Link } from '@inertiajs/react'
import {route} from 'ziggy-js';

const Sample = () => {
  return (
    <>
      <Link href={route('login')}>Login</Link>
    </>
  );
};

export default Sample;
