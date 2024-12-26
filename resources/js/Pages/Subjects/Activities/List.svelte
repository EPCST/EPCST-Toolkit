<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {onMount} from 'svelte';
  import {Link, page, router} from '@inertiajs/svelte';

  // On mount make sure that we initialize Preline JS.
  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

  const { subject, activities } = $props();

</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  <div class="flex justify-between items-center">
    <h1 class="font-bold text-2xl">My Activities</h1>
  </div>

  <table>
    <thead>
    <tr>
      <th>Title</th>
      <th>Due Date</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
    {#each activities as activity}
      <tr>
        <td>{activity.title}</td>
        <td>{activity.due_date}</td>
        <td><Link href={route('subjects.activities.show', {subject: subject.id, activity: activity.id})}>View</Link></td>
      </tr>
    {/each}
    </tbody>
  </table>
</div>
