<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {onMount} from 'svelte';
  import {Link, page, router} from '@inertiajs/svelte';
  import {ChevronLeft, EllipsisVertical, Eye, FileMinus} from "lucide-svelte";
  import {toast} from "svelte-sonner";

  // On mount make sure that we initialize Preline JS.
  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

  const { subject, activities } = $props();

  function removeActivity(id) {
    router.visit(route('subjects.activities.destroy', {subject: subject.id, activity: id}), {
      method: "delete",
      onSuccess: () => {
        toast.success(`Activity / Exam deleted successfully`, {
          position: 'bottom-right'
        });
      }
    })
  }
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14">
  <div class="bg-blue-500 text-white px-1/2 p-8 flex items-center justify-between">
    <div class="text-white">
      <h1 class="text-xl font-bold">Activities</h1>
      <Link href="{route('subjects.show', subject.id)}" class="text-sm flex items-center gap-2 hover:text-blue-200">
        <ChevronLeft size="16" /> Go Back
      </Link>
    </div>
  </div>
  <div class="flex flex-col m-4" id="report">
    <div>
      <h1 class="text-lg font-bold">Quizzes</h1>
      <p class="text-xs">Long Test and Quizzes</p>
    </div>
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="border rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Title</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Due Date</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">MISSING</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">COMPLETE</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">TOTAL</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-collapse">
              {#each activities['quiz'] as quiz}
                {@const completedStudents = quiz.students.filter((student) => student['pivot']['score']).length}
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{quiz.title}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{quiz.due_date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.students.length - completedStudents}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{completedStudents}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.students.length}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border flex gap-2">
                  <div class="hs-tooltip">
                    <Link href="{route('subjects.activities.show', {subject: subject.id, activity: quiz.id})}" type="button" class="hs-tooltip-toggle flex shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-medium rounded-lg border border-transparent bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                      <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip" data-popper-placement="top" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(1271px, -720px);">View</span>
                      <Eye size={16} class="w-auto"/>
                    </Link>
                  </div>
                  <div class="hs-tooltip">
                    <button onclick={() => removeActivity(quiz.id)} class="hs-tooltip-toggle bg-red-400 hover:bg-red-500 text-sm text-white flex justify-center items-center p-2 rounded-md size-[38px]">
                      <FileMinus size="16" />
                      <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip" data-popper-placement="top" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(1261px, -720px);">Remove Quiz</span>
                    </button>
                  </div>
                </td>
              </tr>
              {/each}
              <tr>
                <td class="hover:bg-blue-400 cursor-pointer hover:text-white text-centerpx-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border" colspan="6">
                  <Link href="{route('subjects.activities.create', {subject: subject.id, type: 'quiz'})}" class="block font-xl text-center">Add new quiz</Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="flex flex-col m-4" id="report">
    <div><h1 class="text-lg font-bold">Assignments</h1>
      <p class="text-xs">Assignments, Recitations, and Class Participation</p></div>
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="border rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Title</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Due Date</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">MISSING</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">COMPLETE</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">TOTAL</th>
              <th scope="col" class="w-4 px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-collapse">
            {#each activities['activity'] as activity}
              {@const completedStudents = activity.students.filter((student) => student['pivot']['score']).length}
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{activity.title}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{activity.due_date}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.students.length - completedStudents}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{completedStudents}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border">{subject.students.length}</td>
              <td class="px-6 py-4 whitespace-nowrap flex gap-2 text-sm text-gray-800 border-gray-200 border">
                <div class="hs-tooltip">
                  <Link href="{route('subjects.activities.show', {subject: subject.id, activity: activity.id})}" type="button" class="hs-tooltip-toggle flex shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-medium rounded-lg border border-transparent bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip" data-popper-placement="top" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(1271px, -720px);">View</span>
                    <Eye size={16} class="w-auto"/>
                  </Link>
                </div>
                <div class="hs-tooltip">
                  <button onclick={() => removeActivity(activity.id)} class="hs-tooltip-toggle bg-red-400 hover:bg-red-500 text-sm text-white flex justify-center items-center p-2 rounded-md size-[38px]">
                    <FileMinus size="16" />
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip" data-popper-placement="top" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(1261px, -720px);">Remove Activity</span>
                  </button>
                </div>
              </td>
            </tr>
            {/each}
            <tr>
              <td class="hover:bg-blue-400 cursor-pointer hover:text-white text-centerpx-6 py-4 whitespace-nowrap text-sm text-gray-800 border-gray-200 border" colspan="6">
                <Link href="{route('subjects.activities.create', {subject: subject.id, type: 'activity'})}" class="block font-xl text-center">Add new assignment</Link>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
