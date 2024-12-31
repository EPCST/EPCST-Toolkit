<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {inertia, Link, page, router, useForm} from '@inertiajs/svelte';

  import {Button} from "$lib/components/ui/button/index.js";
  import {ChevronLeft} from "lucide-svelte";


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

<div class="flex flex-col sm:gap-4 sm:pl-14">
  <div class="bg-blue-500 text-white px-1/2 p-8 flex items-center justify-between">
    <div class="text-white">
      <h1 class="text-xl font-bold">{$page.props.subject.title}</h1>
      <Link href="{route('subjects.index')}" class="text-sm flex items-center gap-2 hover:text-blue-200">
        <ChevronLeft size="16" /> Go Back
      </Link>
    </div>
    <button type="button" data-hs-overlay="#add-student" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-green-200 hover:border-green-400 hover:text-green-400 disabled:opacity-50 disabled:pointer-events-none">
      Add Student
    </button>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3 lg:gap-5 p-4">
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3">
        <i class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-community"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500"># of Students</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">{$page.props.students.length}</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2">
        <button class="text-xs hover:text-blue-400" data-hs-overlay="#add-student">Add new Student</button>
      </div>
    </div>
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3"><i class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-post"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500">Activities / Quizzes</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">{$page.props.subject.activities.length}</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2">
        <Link class="text-xs hover:text-blue-400" href="{route('subjects.activities.index', $page.props.subject.id)}">View All Activities</Link>
      </div>
    </div>
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3"><i
        class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-calculator"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500">Exams</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">MIDTERM</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2">
        <a class="text-xs hover:text-blue-400" href="/subjects/w1lrzmxbi243ynf/exams/midterm">View Exam</a>
      </div>
    </div>
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3">
        <i class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-calendar"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500">Attendances</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">6</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2"><a class="text-xs hover:text-blue-400"
                                                     href="/subjects/w1lrzmxbi243ynf/attendances">View All
        Attendances</a></div>
    </div>
  </div>
  <Link href={route('subjects.index')}>Back</Link>
  <h1>Show Subject: {$page.props.subject.title}</h1>

  <form>
    <input type="text" bind:value={$form.title}/>
    <input type="date" bind:value={$form.due_date}/>
    <input type="text" bind:value={$form.type}/>
    <input type="number" bind:value={$form.points}/>
    <button onclick={submitForm} disabled={$form.processing}>Create Activity</button>
  </form>

  <Link class="h-7 gap-1 text-sm" href={route('subjects.activities.index', $page.props.subject.id)}>View Activities
  </Link>
  <Link class="h-7 gap-1 text-sm" href={$page.url + '/fetch'} only={['students']}>Fetch</Link>
</div>
