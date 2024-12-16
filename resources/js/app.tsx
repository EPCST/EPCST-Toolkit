// resources/js/app.tsx

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import {InertiaAppProps} from "@inertiajs/svelte/dist/components/App.svelte";
import Layout from "@/Components/Layout";
import 'preline'

createInertiaApp({
  resolve: (name: any) => {
    const pages = import.meta.glob('./Pages/**/*.tsx', { eager: true })
    let page = pages[`./Pages/${name}.tsx`]
    page.default.layout = page.default.layout || ((page: unknown) => <Layout children={page} />)
    return page
  },
  setup({el, App, props}: { el: Element; App: React.FC<InertiaAppProps>; props: InertiaAppProps }) {
    createRoot(el).render(<App {...props} />);
  },
}).then((r: any) => {
  if(r) {
    console.log(r);
  }
});
