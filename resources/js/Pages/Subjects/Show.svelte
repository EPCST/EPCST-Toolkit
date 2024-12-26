<script module>
  export { default as layout } from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {inertia, Link, page, router, useForm} from '@inertiajs/svelte';

  import { Button } from "$lib/components/ui/button/index.js";


  const form = useForm({
    title: '',
    subject_id: $page.props.subject.id,
    period: 'prelim',
    academic_year_id: 9,
    type: 'activity',
    points: 0,
    due_date: new Date()
  });

  function submitForm(e) {
    e.preventDefault();

    $form.post($page.url + '/activities/create');
  }
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  <Link href={route('subjects.index')}>Back</Link>
  <h1>Show Subject: {$page.props.subject.title}</h1>

  <form>
    <input type="text" bind:value={$form.title} />
    <input type="date" bind:value={$form.due_date} />
    <input type="text" bind:value={$form.type} />
    <input type="number" bind:value={$form.points} />
    <button onclick={submitForm} disabled={$form.processing}>Create Activity</button>
  </form>

  <Link class="h-7 gap-1 text-sm" href={route('subjects.activities.index', $page.props.subject.id)}>View Activities</Link>
  <Link class="h-7 gap-1 text-sm" href={$page.url + '/fetch'} only={['students']}>Fetch</Link>
</div>
