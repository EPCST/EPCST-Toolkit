<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {onMount} from 'svelte';
  import {router, useForm} from '@inertiajs/svelte';

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

  import {Button, buttonVariants} from "$lib/components/ui/button/index.js";

  import * as AlertDialog from "$lib/components/ui/alert-dialog";
  import Eye from "lucide-svelte/icons/eye";
  import EllipsisVertical from "lucide-svelte/icons/ellipsis-vertical";
  import {Label} from "$lib/components/ui/label/index.js";
  import {Input} from "$lib/components/ui/input/index.js";
  import * as Dialog from "$lib/components/ui/dialog/index.js";
  import {toast} from "svelte-sonner";

  // On mount make sure that we initialize Preline JS.
  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

  let dialogOpen = $state(false);
  let confirmOpen = $state(false);

  const {subjects} = $props();

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

  const form = useForm({
    id: '',
    code: '',
    title: '',
    units_lec: 0,
    units_lab: 0,
    attendance_threshold: 5,
    dropout_threshold: 10
  });

  function editClass(section) {
    $form['id'] = section.id;
    $form['code'] = section.code;
    $form['title'] = section.title;
    $form['units_lec'] = section.units_lec;
    $form['units_lab'] = section.units_lab;
    $form['attendance_threshold'] = section.attendance_threshold;
    $form['dropout_threshold'] = section.dropout_threshold;
  }

  function remove() {
    $form.delete(route('subjects.delete', $form.id), {
      onSuccess: () => {
        toast.success("Subject removed", {
          position: 'bottom-right',
          description: 'Subject has been removed successfully'
        });
        dialogOpen = false;
      }
    });
  }
  function save() {
    $form.post(route('subjects.update', $form.id), {
      onSuccess: () => {
        toast.success("Subject updated", {
          position: 'bottom-right',
          description: 'All changes to subject has been saved successfully.'
        });
        dialogOpen = false;
      }
    });
  }
</script>

<AlertDialog.Root bind:open={confirmOpen}>
  <AlertDialog.Trigger />
  <AlertDialog.Content>
    <AlertDialog.Header>
      <AlertDialog.Title>Are you absolutely sure?</AlertDialog.Title>
      <AlertDialog.Description>
        This action cannot be undone. This will permanently delete all records (activities, quizzes, exams, etc.) related to this subject.
      </AlertDialog.Description>
    </AlertDialog.Header>
    <AlertDialog.Footer>
      <AlertDialog.Cancel>Cancel</AlertDialog.Cancel>
      <AlertDialog.Action class={buttonVariants({ variant: "destructive" })} onclick={remove}>Delete</AlertDialog.Action>
    </AlertDialog.Footer>
  </AlertDialog.Content>
</AlertDialog.Root>

<Dialog.Root bind:open={dialogOpen}>
  <Dialog.Content class="sm:max-w-[512px]">
    <Dialog.Header>
      <Dialog.Title>Edit Subject</Dialog.Title>
    </Dialog.Header>
    <div class="flex flex-col pointer-events-auto">
      <input type="hidden" bind:value={$form.id}>

      <div class="p-2 overflow-y-auto">
        <label for="code" class="block text-sm font-medium mb-2">Code</label>
        <input type="text" bind:value={$form.code} id="code" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="ABC123">
      </div>
      <div class="p-2 overflow-y-auto">
        <label for="title" class="block text-sm font-medium mb-2">Title</label>
        <input type="text" bind:value={$form.title} id="title" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="My Subject">
      </div>
      <div class="grid grid-cols-2">
        <div class="p-2 overflow-y-auto">
          <label for="units_lec" class="block text-sm font-medium mb-2">Units Lec</label>
          <input type="number" id="units_lec" bind:value={$form.units_lec} class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0">
        </div>
        <div class="p-2 overflow-y-auto">
          <label for="units_lab" class="block text-sm font-medium mb-2">Units Lab</label>
          <input type="number" id="units_lab"  bind:value={$form.units_lab} class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0">
        </div>
      </div>
      <div class="grid grid-cols-2">
        <div class="p-2 overflow-y-auto">
          <label for="attendance_threshold" class="block text-sm font-medium mb-2">Attendance Threshold</label>
          <input type="number" id="attendance_threshold" bind:value={$form.attendance_threshold} class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0">
        </div>
        <div class="p-2 overflow-y-auto">
          <label for="dropout_threshold" class="block text-sm font-medium mb-2">Dropout Threshold</label>
          <input type="number" id="dropout_threshold"  bind:value={$form.dropout_threshold} class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0">
        </div>
      </div>
    </div>
    <Dialog.Footer>
      <div class="flex justify-end items-center gap-x-2 py-3 px-2">
        <button type="button" disabled={$form.processing} onclick={save} class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
          Save Changes
        </button>
      </div>
    </Dialog.Footer>
  </Dialog.Content>

  <div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
    <div class="flex justify-between items-center">
      <h1 class="font-bold text-2xl">My Classes</h1>
      <Button class="h-7 gap-1 text-sm" onclick={(e) => {
        e.preventDefault();
        e.target.disabled = true;
        fetchSubjects(e);
      }}>Fetch Subjects
      </Button>
    </div>
    <div class="hs-accordion-group">
      {#each Object.entries(subjects) as [subject, sections]}
        <div class="hs-accordion bg-white border -mt-px">
          <button
            class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none"
            aria-expanded="true">
            <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                 height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
              <path d="M5 12h14"></path>
              <path d="M12 5v14"></path>
            </svg>
            <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                 height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
              <path d="M5 12h14"></path>
            </svg>
            {subject}
          </button>
          <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
               role="region">
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
                      <Button onclick={() => viewSubject(section['id'])} variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
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
                          <Dialog.Trigger class="w-full" onclick={() => editClass(section)}>
                            <DropdownMenuItem>Edit</DropdownMenuItem>
                          </Dialog.Trigger>
                          <DropdownMenuItem onclick={() => {
                            editClass(section);
                            confirmOpen = true;
                          }}>Remove</DropdownMenuItem>
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

</Dialog.Root>
