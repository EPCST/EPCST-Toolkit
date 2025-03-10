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
  import {toast} from "svelte-sonner";
  import {onMount} from "svelte";

  const {report, month} = $props();

  // On mount make sure that we initialize Preline JS.
  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

  function getCurrentMonth(month = null) {
    const today = new Date(month);
    const monthIndex = today.getMonth(); // Get month index (0-11)
    const year = today.getFullYear();

    return `${year}-${(monthIndex + 1).toString().padStart(2, '0')}`;
  }


  let monthInput = $state(getCurrentMonth(month));

  function getReport() {
    router.get(route('reports.dropoutReport'), {
      month: monthInput
    }, {
      onSuccess: () => {
        toast.success("Dropout retrieved", {
          position: 'bottom-right',
          description: 'Dropout summary has been retrieved successfully'
        });
      }
    });
  }

  const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  const {
    semester,
    school_year
  } = $page.props.app.settings.academic_years.filter((a) => a.id === Number($page.props.app.settings.academic_year))[0];
</script>

<div class="flex flex-col m-4 sm:pl-14" id="report">
  <div class="bg-gray-200 rounded-sm shadow-md p-4 mb-2 flex flex-col">
    <div class="flex justify-between">
      <p class="text-xs">EPCST R-114 REV</p>
      <h1 class="text-sm">EASTWOODS Professional College of Science and Technology</h1>
      <p class="text-xs">System Generated Report</p>
    </div>
    <div class="flex justify-center items-center flex-col">
      <h1 class="text-xl text-center font-bold">Dropout Summary</h1>
      <p class="text-xs">{semester}, A.Y. {school_year}</p>
      <p class="text-xs mt-4">Month of <b><u>{months[month.split('-')[1] - 1]}, {month.split('-')[0]}</u></b></p>
    </div>
  </div>
  <div class="shadow p-2 mb-2 flex gap-2">
    <input type="month" bind:value={monthInput} id="month" class="py-2.5 px-3 w-full inline-flex items-center rounded-xl text-sm border border-gray-200 bg-white text-gray-500 ring-1 ring-transparent hover:border-purple-500 hover:ring-purple-500 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:border-purple-500 focus:ring-purple-500 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-500 dark:hover:ring-neutral-600 dark:focus:ring-neutral-600" />
    <button onclick="{getReport}" class="w-32 flex justify-center py-2 px-3 items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Get Summary</button>
  </div>
  <div class="-m-1.5 overflow-x-auto">
    <div class="p-1.5 min-w-full inline-block align-middle">
      {#each Object.entries(report) as [key, value], i}
        {@const { student, subjects } = value}
        <div class="hs-accordion bg-white border -mt-px">
          <button class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none" aria-expanded="true">
            <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14"></path>
              <path d="M12 5v14"></path>
            </svg>
            <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14"></path>
            </svg>
            {student.last_name}, {student.first_name} {student.middle_name}
          </button>
          <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Instructor</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Subject</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Section</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Unit</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase"># of Hours</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Date of Last Attendance</th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 border-collapse">
              {#each (subjects ?? []).sort((a, b) => {
                // First, compare by title
                const titleComparison = a.title.localeCompare(b.title);

                // If titles are not equal, return the comparison result
                if (titleComparison !== 0) {
                  return titleComparison;
                }

                // If titles are equal, compare by first_name
                return a.section.localeCompare(b.section);
              }) as subject, i}
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.teacher.first_name} {subject.teacher.middle_name} {subject.teacher.last_name}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.title}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.section}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.units_lec} ({subject.units_lab})</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.total_absences}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.last_attendance_date ?? 'Never present'}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">

                  </td>
                </tr>
              {/each}
              </tbody>
            </table>
          </div>
        </div>
      {/each}
    </div>
  </div>
</div>

<!--<div class="flex flex-col m-4 sm:pl-14" id="report">-->
<!--  <div class="bg-gray-200 rounded-sm shadow-md p-4 mb-2 flex flex-col">-->
<!--    <div class="flex justify-between">-->
<!--      <p class="text-xs">EPCST R-114 REV</p>-->
<!--      <h1 class="text-sm">EASTWOODS Professional College of Science and Technology</h1>-->
<!--      <p class="text-xs">System Generated Report</p>-->
<!--    </div>-->
<!--    <div class="flex justify-center items-center flex-col">-->
<!--      <h1 class="text-xl text-center font-bold">Report on Absences</h1>-->
<!--      <p class="text-xs">{semester}, A.Y. {school_year}</p>-->
<!--      <p class="text-sm mt-4">FROM: Jan 1, 2024 | TO: Jan 10, 2024</p>-->
<!--    </div>-->
<!--  </div>-->
<!--  <div class="mt-8 mb-4 flex justify-between">-->
<!--    <h3>Faculty: <span class="border-b px-[128px] text-left border-gray-400 uppercase">{$page.props.auth.user.first_name} {$page.props.auth.user?.middle_name ? $page.props.auth.user.middle_name.charAt(0) + '.' : ''} {$page.props.auth.user.last_name}</span></h3>-->
<!--    <h3>Department: <span class="border-b px-[128px] text-left border-gray-400 uppercase">{$page.props.auth.user.department}</span></h3>-->
<!--  </div>-->
<!--  <div class="-m-1.5 overflow-x-auto">-->
<!--    <div class="p-1.5 min-w-full inline-block align-middle">-->
<!--      <div class="border rounded-lg overflow-hidden">-->
<!--        <table class="min-w-full divide-y divide-gray-200">-->
<!--          <thead class="bg-gray-50">-->
<!--          <tr>-->
<!--            <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Subjects</th>-->
<!--            <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Units</th>-->
<!--            <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Section</th>-->
<!--          </tr>-->
<!--          </thead>-->
<!--          <tbody class="divide-y divide-gray-200 border-collapse">-->
<!--          {#each Object.entries(report) as [key, value], i}-->
<!--            {@const { student, subjects } = value}-->
<!--            <tr>-->
<!--              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{student.last_name}, {student.first_name}</td>-->
<!--              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">-->

<!--              </td>-->
<!--              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--            </tr>-->
<!--          {/each}-->
<!--          {#each Array(Math.max(Object.keys(report).length, 10 - Object.keys(report).length)) as _, i}-->
<!--            <tr>-->
<!--              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border"></td>-->
<!--            </tr>-->
<!--          {/each}-->
<!--          </tbody>-->
<!--        </table>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
