import { Link } from '@inertiajs/react'
import {route} from "ziggy-js";
import React from "react";

export default function Layout({ children }) {
  return (
    <main className="h-full">
      {children}
    </main>
  )
}
