<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {page, Link, router} from '@inertiajs/svelte';
  import {Label} from "$lib/components/ui/label/index.js";
  import {Textarea} from "$lib/components/ui/textarea/index.js";
  import {Input} from "$lib/components/ui/input/index.js";
  import * as Select from "$lib/components/ui/select/index.js";
  import * as Card from "$lib/components/ui/card/index.js";
  import {Bird, Calendar, ChevronLeft, ChevronRight, Rabbit, Turtle} from "lucide-svelte";
  import { DatePicker } from "bits-ui";

  function enrollmentReport() {
    router.visit(route('reports.enrollmentReport'))
  }

  function subjectLoading() {
    router.visit(route('reports.subjectLoading'))
  }

  function attendanceReport() {
    router.visit(route('reports.attendanceReport'));
  }

  let reportType = $state();

  function generateReport() {
    switch(reportType) {
      case 'subject-loading': subjectLoading(); break;
      case 'attendance-report': attendanceReport(); break;
    }
  }

  const {report} = $props();

  const {
    semester,
    school_year
  } = $page.props.app.settings.academic_years.filter((a) => a.id === Number($page.props.app.settings.academic_year))[0];
</script>

<div class="flex sm:gap-4 sm:pl-14">
  <div class="bg-white w-full shadow-md m-4">
    <form class="w-full">
      <div class="p-4 space-y-5">
        <div>
          <label for="hs-pro-dalpn" class="block mb-2 text-sm font-medium text-gray-800">Type of Report</label>
          <select bind:value={reportType} class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
            <option selected disabled>Select a report...</option>
            <option value="subject-loading">Subject Loading</option>
            <option value="enrollment-report">Enrollment Report</option>
            <option value="attendance-report">Report on Absences</option>
            <option value="academic-report">Report on Academics</option>
            <option value="dropout-report">Report on Dropouts</option>
            <option value="grade-report">Grade Report</option>
          </select>
        </div>
        {#if reportType === 'attendance-report'}
          <div>
            <label for="period" class="block mb-2 text-sm font-medium text-gray-800">Period</label>
            <select id="period" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
              <option selected disabled>Select a period...</option>
              <option value="prelim">PRELIM</option>
              <option value="midterm">MIDTERM</option>
              <option value="final">FINAL</option>
            </select>
          </div>
          <div>
            <label for="hs-pro-dalsd" class="block mb-2 text-sm font-medium text-gray-800">Week</label>
            <div class="relative">
              <input type="week" id="week" class="py-2.5 ps-3 pe-24 block w-full border-gray-200 rounded-lg text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
            </div>
          </div>
        {:else if reportType === 'academic-report' || reportType === 'grade-report'}
          <div>
            <label for="period" class="block mb-2 text-sm font-medium text-gray-800">Period</label>
            <select id="period" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
              <option selected disabled>Select a period...</option>
              <option value="prelim">PRELIM</option>
              <option value="midterm">MIDTERM</option>
              <option value="final">FINAL</option>
            </select>
          </div>
        {:else if reportType === 'dropout-report'}
          <div>
            <label for="period" class="block mb-2 text-sm font-medium text-gray-800">Month</label>
            <input type="month" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"/>
          </div>
        {:else if reportType === 'grade-report'}
          asd
        {/if}
        <div class="py-4 flex justify-end gap-x-2">
          <div class="flex-1 flex flex-wrap justify-end items-center gap-2">
            <button onclick={generateReport} type="button" class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-start bg-blue-600 border border-blue-600 text-white text-sm font-medium rounded-lg shadow-sm align-middle hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-1 focus:ring-blue-300">
              Generate
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<!--<button onclick={enrollmentReport}>Enrollment Report</button>-->
<!--<button onclick={subjectLoading}>Subject Loading</button>-->
<!--<Link href="{route('reports.attendanceReport')}">Attendance Report</Link>-->
<!--<Link href="{route('reports.academicReport')}">Academic Report</Link>-->
<!--<Link href="{route('reports.dropoutReport')}">Dropout Report</Link>-->
<!--<Link href="{route('reports.gradeReport')}">Grade Report</Link>-->
