// resources/js/app.tsx

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import Layout from "@/Components/Layout";
import 'preline'

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
    let page = pages[`./Pages/${name}.jsx`]
    page.default.layout = page.default.layout || ((page) => <Layout children={page} />)
    return page
  },
  setup({el, App, props}) {
    createRoot(el).render(<App {...props} />);
  },
}).then((r) => {
  if(r) {
    console.log(r);
  }
});
