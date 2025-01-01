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

  const { type, subject, students } = $props();

  let studentsScores = {};
  for(let [key, student] of Object.entries(students)) {
    studentsScores[key] = {
      id: student.id,
      score: '',
      remarks: ''
    }
  }

  const form = useForm({
    type: type,
    title: '',
    subject_id: subject.id,
    period: $page.props.app.settings.period,
    academic_year_id: Number($page.props.app.settings.academic_year),
    points: 0,
    due_date: '',
    studentsScores: studentsScores
  });

  function save(e) {
    e.preventDefault();

    $form.post(route('subjects.activities.store', {subject: subject.id}), {
      preserveScroll: true,
      onSuccess: () => {
        toast.success("Activity created successfully", {
          position: 'top-right'
        });
        router.get(route('subjects.activities.index', {subject: subject.id}), {}, { replace: true })
      }
    });
  }
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14">
  <div class="flex flex-col p-8">
    <div class="space-y-3 mb-4">
      <div class="grid w-full items-center gap-1.5">
        <Label for="title">Title</Label>
        <Input type="text" bind:value={$form.title} id="title" placeholder="Title" />
        {#if $form.errors.title}<small class="text-xs text-red-400">{$form.errors.title}</small>{/if}
      </div>
      <div class="grid w-full items-center gap-1.5">
        <Label for="due_date">Due Date</Label>
        <Input type="datetime-local" bind:value={$form.due_date} id="due_date" />
        {#if $form.errors.due_date}<small class="text-xs text-red-400">{$form.errors.due_date}</small>{/if}
      </div>
      <div class="grid w-full items-center gap-1.5">
        <Label for="points">Points</Label>
        <Input type="number" bind:value={$form.points} id="points" min="0" />
        {#if $form.errors.points}<small class="text-xs text-red-400">{$form.errors.points}</small>{/if}
      </div>
    </div>
    <div class="bg-gray-50 shadow-md rounded-md text-xs">
      <table class="table-auto border-collapse">
        <thead class="bg-gray-300">
        <tr id="attendanceDates">
          <th class="p-2 border border-gray-400 sticky top-0 left-0 bg-gray-300 z-20">&ZeroWidthSpace;</th>
          <th class="p-2 border border-gray-400 min-w-[150px] text-center">Scores</th>
        </tr>
        </thead>
        <tbody>
        {#each Object.entries(students) as [key, student]}
          <tr>
            <td class="p-2 border border-gray-400 bg-gray-200 sticky w-min text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">
              <b>{student.last_name}</b>, {student.first_name}
            </td>
            <td class="border border-gray-400 min-w-[150px] text-center">
              <input type="number" bind:value={$form.studentsScores[key]['score']} class="w-full focus:border-0 focus:outline-none text-xs bg-transparent border-none outline-none" placeholder="out of {$form.points}">
            </td>
            <td class="border border-gray-400 text-center">
              <input type="text" class="focus:border-0 focus:outline-none text-xs bg-transparent border-none outline-none" placeholder="Remarks..." tabindex="-1">
            </td>
          </tr>
          {/each}
        </tbody>
      </table>
    </div>
    <div class="flex justify-between mt-4">
      <Link href="{route('subjects.activities.index', subject.id)}" type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none">Back</Link>
      <button onclick={save} disabled={$form.processing} type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none">
        Save
      </button>
    </div>
  </div>
</div>
