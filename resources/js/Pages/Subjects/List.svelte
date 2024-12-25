<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {onMount} from 'svelte';
  import {router} from '@inertiajs/svelte';

  import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem
  } from "$lib/components/ui/dropdown-menu/index.js";

  import {
    TableHeader,
    TableCell,
    TableHead,
    Root as TableRoot,
    TableRow,
    TableBody
  } from '$lib/components/ui/table/index.js';

  import { Button } from "$lib/components/ui/button/index.js";

  import Eye from "lucide-svelte/icons/eye";
  import EllipsisVertical from "lucide-svelte/icons/ellipsis-vertical";

  // On mount make sure that we initialize Preline JS.
  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

  const { subjects } = $props();

  /**
   * View a subject
   *
   * @param {number} subjectId - The ID of the subject to view
   */
  function viewSubject(subjectId) {
    router.visit(`/subjects/${subjectId}`);
  }

  function fetchSubjects(e) {
    router.get(route('subjects.fetchSubjects'), {
      onFinish: () => {
        e.target.disabled = false;
      }
    });
  }
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  <div class="flex justify-between items-center">
    <h1 class="font-bold text-2xl">My Classes</h1>
    <Button class="h-7 gap-1 text-sm" onclick={(e) => {
      e.preventDefault();
      e.target.disabled = true;
      fetchSubjects(e);
    }}>Fetch Subjects</Button>
  </div>
  <div class="hs-accordion-group">
    {#each Object.entries(subjects) as [subject, sections]}
    <div class="hs-accordion bg-white border -mt-px">
      <button
        class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none"
        aria-expanded="true">
        <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
          <path d="M5 12h14"></path>
          <path d="M12 5v14"></path>
        </svg>
        <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
          <path d="M5 12h14"></path>
        </svg>
        {subject}
      </button>
      <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region">
        <TableRoot>
          <TableHeader>
            <TableRow>
              <TableHead>Section Name</TableHead>
              <TableHead># of Students</TableHead>
              <TableHead class="text-right">Action</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {#each sections as section}
            <TableRow>
              <TableCell><b>{section['section']}</b></TableCell>
              <TableCell>{section['students'].length}</TableCell>
              <TableCell class="text-right flex justify-end items-center gap-2">
                <Button onclick={() => viewSubject(section['id'])} variant="outline" size="icon"
                        class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                  <Eye size={16} class="w-auto"/>
                </Button>
                <DropdownMenu>
                  <DropdownMenuTrigger asChild let:builder>
                    <Button
                      variant="outline"
                      size="icon"
                      class="overflow-hidden"
                      builders={[builder]}
                    >
                      <EllipsisVertical size={16} class="w-auto"/>
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem>Edit</DropdownMenuItem>
                    <DropdownMenuItem>Remove</DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
            {/each}
          </TableBody>
        </TableRoot>
      </div>
    </div>
    {/each}
  </div>
</div>
