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

  console.log($page);
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  asd
</div>
