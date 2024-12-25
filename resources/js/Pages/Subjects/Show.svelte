<script module>
  export { default as layout } from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {inertia, Link, page, router} from '@inertiajs/svelte';

  import { Button } from "$lib/components/ui/button/index.js";
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  <Link href={route('subjects.index')}>Back</Link>
  <h1>Show Subject: {$page.props.subject.title}</h1>
  
  <Link class="h-7 gap-1 text-sm" href={$page.url + '/fetch'} only={['students']}>Fetch</Link>
</div>
