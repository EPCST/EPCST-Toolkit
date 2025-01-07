<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {inertia, Link, page, router, useForm} from '@inertiajs/svelte';

  import { toast } from "svelte-sonner";

  import {Button} from "$lib/components/ui/button/index.js";
  import {ChevronLeft} from "lucide-svelte";
  import {Input} from "$lib/components/ui/input/index.js";
  import {Label} from "$lib/components/ui/label/index.js";
  import CalendarIcon from "svelte-radix/Calendar.svelte";
  import {
    DateFormatter,
    getLocalTimeZone
  } from "@internationalized/date";
  import { cn } from "$lib/utils.js";
  import { Calendar } from "$lib/components/ui/calendar/index.js";
  import * as Popover from "$lib/components/ui/popover/index.js";
  import {Textarea} from "$lib/components/ui/textarea/index.js";
  import * as Select from "$lib/components/ui/select/index.js";
  import {onMount} from "svelte";

  const form = useForm({
    date: new Date().toISOString().substring(0,10),
    hours: 0,
    attendanceData: {}
  });

  let studentAttendanceData = $state({});

  for(let student of subject.students) {
    studentAttendanceData[student.student_no] = {
      student_no: student.student_no,
      status: student.pivot.status !== 'dropped' ? 'present' : 'absent',
      remarks: student.pivot.status === 'dropped' && student.pivot.returned_at ? 'System Dropped' : '',
      hours: 0,
      return_to_class: false
    }
  }

  let { subject } = $props();
  let value;

  function updateAttendanceHours(id, status) {
    if(status === 'absent') {
      studentAttendanceData[id].hours = $form.hours;
    }
    else if(status === 'late') {
      studentAttendanceData[id].hours = $form.hours - ($form.hours - .25);
    }
    else if(status === 'present' || status === 'excused') {
      studentAttendanceData[id].hours = 0;
    }
  }

  function save() {
    $form.attendanceData = studentAttendanceData;

    $form.post(route('subjects.attendances.store', subject.id), {
      onSuccess: function() {
        toast.success("Attendance Recorded", {
          position: 'bottom-right',
          description: 'Attendance record has been successfully created'
        });
        router.get(route('subjects.attendances.index', {subject: subject.id}), {}, { replace: true })
      }
    });
  }
</script>

<!--<div class="flex flex-col sm:gap-4 sm:pl-14">-->
<!--  <div class="flex flex-col p-8">-->
<!--    <div class="space-y-3 mb-4">-->
<!--      <div class="grid w-full items-center gap-1.5">-->
<!--        <Label for="title">Title</Label>-->
<!--        <Input type="text" bind:value={$form.title} id="title" placeholder="Title" />-->
<!--        {#if $form.errors.title}<small class="text-xs text-red-400">{$form.errors.title}</small>{/if}-->
<!--      </div>-->
<!--      <div class="grid w-full items-center gap-1.5">-->
<!--        <Label for="due_date">Due Date</Label>-->
<!--        <Input type="datetime-local" bind:value={$form.due_date} id="due_date" />-->
<!--        {#if $form.errors.due_date}<small class="text-xs text-red-400">{$form.errors.due_date}</small>{/if}-->
<!--      </div>-->
<!--      <div class="grid w-full items-center gap-1.5">-->
<!--        <Label for="points">Points</Label>-->
<!--        <Input type="number" bind:value={$form.points} id="points" min="0" />-->
<!--        {#if $form.errors.points}<small class="text-xs text-red-400">{$form.errors.points}</small>{/if}-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="bg-gray-50 shadow-md rounded-md text-xs">-->
<!--      <table class="table-auto border-collapse">-->
<!--        <thead class="bg-gray-300">-->
<!--        <tr id="attendanceDates">-->
<!--          <th class="p-2 border border-gray-400 sticky top-0 left-0 bg-gray-300 z-20">&ZeroWidthSpace;</th>-->
<!--          <th class="p-2 border border-gray-400 min-w-[150px] text-center">Scores</th>-->
<!--        </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        {#each Object.entries(students) as [key, student]}-->
<!--          <tr>-->
<!--            <td class="p-2 border border-gray-400 bg-gray-200 sticky w-min text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--              <b>{student.last_name}</b>, {student.first_name}-->
<!--            </td>-->
<!--            <td class="border border-gray-400 min-w-[150px] text-center">-->
<!--              <input type="number" bind:value={$form.studentsScores[key]['score']} class="w-full focus:border-0 focus:outline-none text-xs bg-transparent border-none outline-none" placeholder="out of {$form.points}">-->
<!--            </td>-->
<!--            <td class="border border-gray-400 text-center">-->
<!--              <input type="text" class="focus:border-0 focus:outline-none text-xs bg-transparent border-none outline-none" placeholder="Remarks..." tabindex="-1">-->
<!--            </td>-->
<!--          </tr>-->
<!--          {/each}-->
<!--        </tbody>-->
<!--      </table>-->
<!--    </div>-->
<!--    <div class="flex justify-between mt-4">-->
<!--      <Link href="{route('subjects.activities.index', subject.id)}" type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none">Back</Link>-->
<!--      <button onclick={save} disabled={$form.processing} type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none">-->
<!--        Save-->
<!--      </button>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->

<div class="flex flex-col sm:pl-14 m-4">
  <fieldset class="grid gap-6 rounded-lg border p-4 mb-4">
    <legend class="-ml-1 px-1 text-sm font-medium">Settings</legend>
    <div class="space-y-3">
      <div class="relative">
        <input type="date" bind:value={$form.date} id="date" class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2" placeholder="********">
        <label for="date" class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent  origin-[0_0] peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:scale-90 peer-focus:translate-x-0.5 peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:scale-90 peer-[:not(:placeholder-shown)]:translate-x-0.5 peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">Meeting Date</label>
        <small class="mx-4 text-muted-foreground">Note: Create attendances in chronological order!</small>
        {#if $form.errors.date}
          <span class="error-msg mx-4 text-xs text-red-400" id="error-hours"><b>ERROR:</b> {$form.errors.date}</span>
        {/if}
      </div>
      <div class="relative">
        <input type="number" bind:value={$form.hours} id="hours" class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2" placeholder="********">
        <label for="hours" class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent  origin-[0_0] peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:scale-90 peer-focus:translate-x-0.5 peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:scale-90 peer-[:not(:placeholder-shown)]:translate-x-0.5 peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">Meet Hours</label>
        {#if $form.errors.hours}
          <span class="error-msg mx-4 text-xs text-red-400" id="error-hours"><b>ERROR:</b> {$form.errors.hours}</span>
        {/if}
      </div>
    </div>
  </fieldset>
  <div class="bg-gray-50 shadow-md rounded-md text-xs">
    <table class="table-auto border-collapse">
      <thead class="bg-gray-300">
      <tr id="attendanceDates">
        <th class="p-2 border border-gray-400 sticky top-0 left-0 bg-gray-300 z-20">&#8203;</th>
        <th class="p-2 border border-gray-400 min-w-[150px] text-center">Attendance</th>
      </tr>
      </thead>
      <tbody>
      {#each subject.students as student}
        {@const permanentDropped = student.pivot.status === 'dropped' && student.pivot.returned_at}
        <tr class={studentAttendanceData[student.student_no].status === 'present' ? 'bg-green-400' : studentAttendanceData[student.student_no].status === 'absent' ? 'bg-red-400' : studentAttendanceData[student.student_no].status === 'excused' ? 'bg-blue-400' : 'bg-gray-400'}>
          <td class="p-2 border border-gray-400 bg-gray-200 sticky w-min text-nowrap overflow-ellipsis overflow-hidden left-0 z-10"><b>{student.last_name}</b>, {student.first_name}</td>
          <td class="border border-gray-400 min-w-[150px] text-center text-white" tabindex="-1" contenteditable="true">
            <input disabled={permanentDropped} type="text" bind:value={studentAttendanceData[student.student_no].hours} onchange={(e) => studentAttendanceData[student.student_no].status = 'late'} tabindex="-1" class="placeholder-white focus:border-0 focus:outline-none text-xs bg-transparent border-none outline-none text-white" placeholder="0">
          </td>
          <td class="border border-gray-400 text-center">
            <select disabled={permanentDropped} bind:value={studentAttendanceData[student.student_no].status} onchange={(e) => updateAttendanceHours(student.student_no, e.target.value)} class="text-xs bg-transparent border-0 border-none">
              <option value="present" selected>P</option>
              <option value="excused">E</option>
              <option value="absent">A</option>
              <option value="late">L</option>
            </select>
          </td>
          <td class="border border-gray-400 text-center">
            <input type="text" disabled={permanentDropped} tabindex="-1" bind:value={studentAttendanceData[student.student_no].remarks} class="placeholder-white focus:border-0 focus:outline-none text-xs bg-transparent border-none outline-none text-white" placeholder="Remarks...">
          </td>
          {#if student.pivot.status === 'dropped' && !student.pivot.returned_at}
          <td class="border border-gray-400 text-center hs-tooltip">
            <div class="relative inline-block hs-tooltip-toggle">
              <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip">
                Return to Class
              </span>
              <input bind:checked={studentAttendanceData[student.student_no].return_to_class} type="checkbox" id="hs-large-switch-with-icons" class="peer relative w-[4.25rem] h-9 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-blue-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-blue-600 checked:border-blue-600 focus:checked:border-blue-600 before:inline-block before:w-8 before:h-8 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200">
              <label for="hs-large-switch-with-icons" class="sr-only">switch</label>
              <span class="peer-checked:text-white text-gray-500 size-8 absolute top-0.5 start-0.5 flex justify-center items-center pointer-events-none transition-colors ease-in-out duration-200 dark:text-neutral-500">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18"></path>
                  <path d="m6 6 12 12"></path>
                </svg>
              </span>
              <span class="peer-checked:text-blue-600 text-gray-500 size-8 absolute top-0.5 end-0.5 flex justify-center items-center pointer-events-none transition-colors ease-in-out duration-200 dark:text-neutral-500">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </span>
            </div>
          </td>
          {/if}
        </tr>
        {/each}
      </tbody>
    </table>
  </div>
  <div class="flex justify-between mt-4">
    <Link href="{route('subjects.attendances.index', subject.id)}" type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
      Cancel
    </Link>
    <button onclick={save} type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none">
      Create
    </button>
  </div>
</div>

