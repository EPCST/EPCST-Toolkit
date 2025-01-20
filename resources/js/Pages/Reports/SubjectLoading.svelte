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
  import {onMount} from "svelte";

  const {report, teachers} = $props();

  // On mount make sure that we initialize Preline JS.
  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

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
      <h1 class="text-xl text-center font-bold">Subject Loading Summary</h1>
      <p class="text-xs">{semester}, A.Y. {school_year}</p>
    </div>
  </div>
  <div class="-m-1.5 overflow-x-auto">
    <div class="p-1.5 min-w-full inline-block align-middle">
      {#each (teachers ?? []).sort((a, b) => a.id - b.id) as teacher}
      <div class="hs-accordion bg-white border -mt-px">
        <button class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none" aria-expanded="true">
          <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
          </svg>
          <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
          </svg>
          {teacher.last_name}, {teacher.first_name} {teacher.middle_name}
        </button>
        <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region">
          <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Subjects</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Units</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Section</th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 border-collapse">
              {#each (report[teacher.id] ?? []).sort((a, b) => {
                // First, compare by title
                const titleComparison = a.title.localeCompare(b.title);

                // If titles are not equal, return the comparison result
                if (titleComparison !== 0) {
                  return titleComparison;
                }

                // If titles are equal, compare by first_name
                return a.section.localeCompare(b.section);
              }) as { title, units_lec, units_lab, section }, i}
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{title}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">
                    {#if !units_lec && !units_lab}0{/if}
                    {#if units_lec}{units_lec}{/if}
                    {#if units_lab}({units_lab}){/if}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{section}</td>
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
