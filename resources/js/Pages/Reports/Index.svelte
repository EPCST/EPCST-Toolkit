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
  import {Bird, Rabbit, Turtle} from "lucide-svelte";

  function enrollmentReport() {
    router.visit(route('reports.enrollmentReport'))
  }

  function subjectLoading() {
    router.visit(route('reports.subjectLoading'))
  }

  let reportType = $state();

  const {report} = $props();

  const {
    semester,
    school_year
  } = $page.props.app.settings.academic_years.filter((a) => a.id === Number($page.props.app.settings.academic_year))[0];
</script>


<!-- Subject Loading -->
<!--<div class="flex flex-col sm:gap-4 sm:py-4 sm:pl-14">-->
<!--  <div class="bg-gray-200 rounded-sm shadow-md p-4 mb-2 flex flex-col">-->
<!--    <div class="flex justify-between">-->
<!--      <p class="text-xs">EPCST R-114 REV</p>-->
<!--      <h1 class="text-sm">EASTWOODS Professional College of Science and Technology</h1>-->
<!--      <p class="text-xs">System Generated Report</p>-->
<!--    </div>-->
<!--    <div class="flex justify-center items-center flex-col">-->
<!--      <h1 class="text-xl text-center font-bold">Subject Loading</h1>-->
<!--      <p class="text-xs">{school_year}, A.Y. {semester}</p>-->
<!--    </div>-->

<!--    <div class="mt-8 mb-4 flex justify-between">-->
<!--      <h3>Faculty: <span class="border-b px-[128px] text-left border-gray-400 uppercase">{$page.props.auth.user.first_name} {$page.props.auth.user.middle_name ? $page.props.auth.user.middle_name.charAt(0) + '.' : ''} {$page.props.auth.user.last_name}</span></h3>-->
<!--      <h3>Department: <span class="border-b px-[128px] text-left border-gray-400 uppercase">{$page.props.auth.user.department}</span></h3>-->
<!--    </div>-->
<!--    <div class="-m-1.5 overflow-x-auto">-->
<!--      <div class="p-1.5 min-w-full inline-block align-middle">-->
<!--        <div class="border rounded-lg overflow-hidden">-->
<!--          <table class="min-w-full divide-y divide-gray-200">-->
<!--            <thead class="bg-gray-50">-->
<!--            <tr>-->
<!--              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Subjects</th>-->
<!--              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Units</th>-->
<!--              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Section</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody class="divide-y divide-gray-200 border-collapse">-->
<!--            {#each report as { title, units_lec, units_lab, section }, i}-->
<!--              <tr>-->
<!--                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{title}</td>-->
<!--                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{units_lab + units_lec}</td>-->
<!--                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{section}</td>-->
<!--              </tr>-->
<!--            {/each}-->
<!--            {#each Array(Math.max(report.length, 10 - report.length)) as _, i}-->
<!--              <tr>-->
<!--                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--              </tr>-->
<!--            {/each}-->
<!--            </tbody>-->
<!--          </table>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->

<!-- Enrollment Report -->
<!--{#each report as subject}-->
<!--<div>-->
<!--  <h1 class="text-xl font-bold">{subject.title}</h1>-->
<!--  <table>-->
<!--    {#each subject.students as student}-->
<!--      <tr><td>{student.last_name}, {student.first_name}</td></tr>-->
<!--    {/each}-->
<!--  </table>-->
<!--</div>-->
<!--{/each}-->

<div class="flex sm:gap-4 sm:pl-14">
  <!--  <Card.Root class="w-full m-4">-->
  <!--    <Card.Content>-->
  <!--      <form class="grid w-full items-start gap-6 overflow-auto pt-0">-->
  <!--        <div>-->
  <!--          <label for="report" class="block text-sm font-medium mb-2 dark:text-white">Report</label>-->
  <!--          <div class="relative">-->
  <!--            <select bind:value={reportType} id="report" class="py-3 px-4 pe-16 block w-full border-gray-200 rounded-lg text-sm disabled:opacity-50 disabled:pointer-events-none">-->
  <!--              <option selected="" disabled>Select a report...</option>-->
  <!--              <option value="subjectLoading">Subject Loading</option>-->
  <!--              <option value="enrollment">Enrollment Report</option>-->
  <!--              <option value="absences">Report on Absences</option>-->
  <!--              <option value="dropouts">Report on Dropouts</option>-->
  <!--              <option value="academics">Report on Academics</option>-->
  <!--              <option value="gradeReport">Grade Report</option>-->
  <!--              <option value="completionRemoval">Completion / Removal</option>-->
  <!--            </select>-->
  <!--          </div>-->
  <!--        </div>-->
  <!--        {#if reportType === 'absences'}-->
  <!--          Absences-->
  <!--        {/if}-->
  <!--      </form>-->
  <!--    </Card.Content>-->
  <!--  </Card.Root>-->
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

        {/if}
      <div class="py-4 flex justify-end gap-x-2">
        <div class="flex-1 flex flex-wrap justify-end items-center gap-2">
          <button type="button" class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-start bg-blue-600 border border-blue-600 text-white text-sm font-medium rounded-lg shadow-sm align-middle hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-1 focus:ring-blue-300">
            Generate
          </button>
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
