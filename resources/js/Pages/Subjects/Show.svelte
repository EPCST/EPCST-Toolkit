<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import NProgress from 'nprogress';
  import LoaderCircle from "lucide-svelte/icons/loader-circle";
  import {inertia, Link, page, router, useForm} from '@inertiajs/svelte';
  import * as Table from "$lib/components/ui/table";
  import {Button} from "$lib/components/ui/button/index.js";
  import {ChevronLeft, RotateCcw, UserMinus} from "lucide-svelte";
  import {toast} from "svelte-sonner";
  import {Badge} from "$lib/components/ui/badge/index.js";
  let loading = $state(false);

  let { attendances, activities, subject, students } = $props();

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

  function fetchStudents() {
    loading = true;
    router.get($page.url + '/fetch', {}, {
      onFinish: function() {
        loading = false;
        toast.success("Fetch Successful", {
          position: 'bottom-right',
          description: 'Students has been successfully fetched from grade book.'
        });
      }
    });
  }

  function removeStudent() {

  }

  function updatePeriod(period) {
    router.visit('/settings', {
      period: period
    });
  }
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14">
  <div class="bg-blue-500 text-white px-1/2 p-8 flex items-center justify-between">
    <div class="text-white">
      <h1 class="text-xl font-bold">{subject.title}</h1>
      <Link href="{route('subjects.index')}" class="text-sm flex items-center gap-2 hover:text-blue-200">
        <ChevronLeft size="16" /> Go Back
      </Link>
    </div>
<!--    <button type="button" data-hs-overlay="#add-student" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-green-200 hover:border-green-400 hover:text-green-400 disabled:opacity-50 disabled:pointer-events-none">-->
<!--      Add Student-->
<!--    </button>-->
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3 lg:gap-5 p-4">
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3">
        <i class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-community"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500"># of Students</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">{students.length}</p>
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
          <p class="text-lg md:text-xl font-semibold text-stone-800">{activities.filter((a) => ['quiz', 'activity'].includes(a.type)).length}</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2">
        <Link class="text-xs hover:text-blue-400" href="{route('subjects.activities.index', $page.props.subject.id)}">View All Activities</Link>
      </div>
    </div>
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3">
        <i class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-calculator"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500">Exams</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">{$page.props.app.settings.period.toUpperCase()}</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2">
<!--        <a class="text-xs hover:text-blue-400 cursor-pointer" onclick={updatePeriod}>PRELIM</a>-->
<!--        <a class="text-xs hover:text-blue-400 cursor-pointer" onclick={updatePeriod}><Badge class="bg-blue-400 hover:bg-blue-700">MIDTERM</Badge></a>-->
<!--        <a class="text-xs hover:text-blue-400 cursor-pointer" onclick={updatePeriod}>FINAL</a>-->
        <Link href="{activities.some((a) => a.type === $page.props.app.settings.period) ? route('subjects.activities.show', {subject: subject.id, activity: activities.filter((a) => a.type === $page.props.app.settings.period)[0].id}) : route('subjects.activities.create', {subject: subject.id, type: $page.props.app.settings.period})}" class="text-xs hover:text-blue-400 cursor-pointer">View Exam</Link>
      </div>
    </div>
    <div class="p-4 sm:p-5 bg-white border border-stone-200 rounded-xl shadow-sm">
      <div class="sm:flex sm:gap-x-3">
        <i class="sm:order-2 mb-2 sm:mb-0 shrink-0 text-2xl text-stone-400 iconoir-calendar"></i>
        <div class="sm:order-1 grow space-y-1">
          <h2 class="sm:mb-3 text-sm text-stone-500">Attendances</h2>
          <p class="text-lg md:text-xl font-semibold text-stone-800">{attendances.length}</p>
        </div>
      </div>
      <div class="mt-1 flex items-center gap-x-2">
        <Link class="text-xs hover:text-blue-400" href={route('subjects.attendances.index', subject.id)}>View All Attendances</Link>
      </div>
    </div>
  </div>

  <div class="m-4 max-h-[512px] overflow-y-scroll bg-white shadow-md p-4 border border-stone-200">
    <div class="flex justify-between items-center w-full p-2">
      <h1 class="font-bold text-xl">My Students</h1>
      {#if students.length > 0}
        <Button size="icon" variant="outline" onclick={fetchStudents}>
          {#if $form.processing || loading}
            <LoaderCircle class="h-4 w-4 animate-spin" />
          {:else}
            <RotateCcw size="16" />
          {/if}
        </Button>
      {/if}
    </div>

    {#if students.length === 0}
      <!-- Empty State -->
      <div class="p-5 min-h-96 flex flex-col justify-center items-center text-center">
        <svg class="w-48 mx-auto mb-4" width="178" height="90" viewBox="0 0 178 90" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="27" y="50.5" width="124" height="39" rx="7.5" fill="currentColor" class="fill-white"/>
          <rect x="27" y="50.5" width="124" height="39" rx="7.5" stroke="currentColor" class="stroke-gray-50"/>
          <rect x="34.5" y="58" width="24" height="24" rx="4" fill="currentColor" class="fill-gray-50"/>
          <rect x="66.5" y="61" width="60" height="6" rx="3" fill="currentColor" class="fill-gray-50"/>
          <rect x="66.5" y="73" width="77" height="6" rx="3" fill="currentColor" class="fill-gray-50"/>
          <rect x="19.5" y="28.5" width="139" height="39" rx="7.5" fill="currentColor" class="fill-white"/>
          <rect x="19.5" y="28.5" width="139" height="39" rx="7.5" stroke="currentColor" class="stroke-gray-100"/>
          <rect x="27" y="36" width="24" height="24" rx="4" fill="currentColor" class="fill-gray-100"/>
          <rect x="59" y="39" width="60" height="6" rx="3" fill="currentColor" class="fill-gray-100"/>
          <rect x="59" y="51" width="92" height="6" rx="3" fill="currentColor" class="fill-gray-100"/>
          <g filter="url(#filter19)">
            <rect x="12" y="6" width="154" height="40" rx="8" fill="currentColor" class="fill-white" shape-rendering="crispEdges"/>
            <rect x="12.5" y="6.5" width="153" height="39" rx="7.5" stroke="currentColor" class="stroke-gray-100" shape-rendering="crispEdges"/>
            <rect x="20" y="14" width="24" height="24" rx="4" fill="currentColor" class="fill-gray-200 "/>
            <rect x="52" y="17" width="60" height="6" rx="3" fill="currentColor" class="fill-gray-200"/>
            <rect x="52" y="29" width="106" height="6" rx="3" fill="currentColor" class="fill-gray-200"/>
          </g>
          <defs>
            <filter id="filter19" x="0" y="0" width="178" height="64" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
              <feFlood flood-opacity="0" result="BackgroundImageFix"/>
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
              <feOffset dy="6"/>
              <feGaussianBlur stdDeviation="6"/>
              <feComposite in2="hardAlpha" operator="out"/>
              <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.03 0"/>
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1187_14810"/>
              <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1187_14810" result="shape"/>
            </filter>
          </defs>
        </svg>

        <div class="max-w-sm mx-auto">
          <p class="mt-2 font-medium text-gray-800">
            No Students
          </p>
          <p class="mb-5 text-sm text-gray-500">
            Fetch from the gradebook to get started.
          </p>
          <Button onclick={fetchStudents} disabled={loading} variant="outline" class="gap-2">
            {#if loading}
              <LoaderCircle class="mr-2 h-4 w-4 animate-spin" />
              Loading...
            {:else}
            <RotateCcw size="16" /> Fetch Students
            {/if}
          </Button>
        </div>
      </div>
    {:else}
      <div class="overflow-hidden min-h-[509px]">
        <table class="min-w-full">
          <thead class="border-y border-gray-200">
          <tr>
            <th scope="col" class="py-1 group text-start font-normal focus:outline-none">
              <div class="py-1 px-2.5 inline-flex items-center text-sm text-gray-500 rounded-md hover:border-gray-200">
                Student Number
              </div>
            </th>
            <th scope="col" class="py-1 group text-start font-normal focus:outline-none">
              <div class="py-1 px-2.5 inline-flex items-center text-sm text-gray-500 rounded-md hover:border-gray-200">
                Full Name
              </div>
            </th>
            <th scope="col" class="py-1 group text-start font-normal focus:outline-none">
              <div class="py-1 px-2.5 inline-flex items-center text-sm text-gray-500 rounded-md hover:border-gray-200">
                Gender
              </div>
            </th>
            <th scope="col" class="py-1 group text-start font-normal focus:outline-none">
              <div class="py-1 px-2.5 inline-flex items-center text-sm text-gray-500 rounded-md hover:border-gray-200">
                Email
              </div>
            </th>
            <th scope="col" class="py-2 px-3 text-end font-normal text-sm text-gray-500 --exclude-from-ordering">Action</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-200" id="subjects-table-rows">
          {#each students as student}
            <tr>
              <td class="p-3 whitespace-nowrap text-sm font-medium text-gray-800">
                <p>{student.student_no.toString().substring(0,2)}-{student.student_no.toString().substring(3)}</p>
              </td>
              <td class="p-3 whitespace-nowrap text-sm font-medium text-gray-800">
                <p><span class="font-bold">{student.last_name}</span>, {student.first_name} {student.middle_name}</p>
              </td>
              <td class="p-3 whitespace-nowrap text-sm font-medium text-gray-800">
                <p class="font-bold">{student.gender.toUpperCase().charAt(0)}</p>
              </td>
              <td class="p-3 whitespace-nowrap text-sm font-medium text-gray-800">
                <p>{student.email}</p>
              </td>
            </tr>
          {/each}
          </tbody>
        </table>
      </div>
    {/if}
  </div>
    <!-- End Empty State -->
<!--  <Link href={route('subjects.index')}>Back</Link>-->
<!--  <h1>Show Subject: {$page.props.subject.title}</h1>-->

<!--  <form>-->
<!--    <input type="text" bind:value={$form.title}/>-->
<!--    <input type="date" bind:value={$form.due_date}/>-->
<!--    <input type="text" bind:value={$form.type}/>-->
<!--    <input type="number" bind:value={$form.points}/>-->
<!--    <button onclick={submitForm} disabled={$form.processing}>Create Activity</button>-->
<!--  </form>-->

<!--  <Link class="h-7 gap-1 text-sm" href={route('subjects.activities.index', $page.props.subject.id)}>View Activities-->
<!--  </Link>-->
<!--  <Link class="h-7 gap-1 text-sm" href={$page.url + '/fetch'} only={['students']}>Fetch</Link>-->
</div>
