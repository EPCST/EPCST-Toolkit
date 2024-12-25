<script module>
  export { default as layout } from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import { page } from '@inertiajs/svelte';
  
  console.log($page);
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  <h1>Show Subject: {$page.props.subject}</h1>
</div>