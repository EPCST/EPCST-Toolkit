<script module>
  export {default as layout} from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import {onMount} from 'svelte';
  import {Link, page, router} from '@inertiajs/svelte';
  import {ChevronLeft, EllipsisVertical, Eye, FileMinus, Pencil, StickyNote, Undo2} from "lucide-svelte";

  const {subject,attendances} = $props();

  onMount(() => {
    window.HSStaticMethods.autoInit();
  });
</script>
<div class="flex flex-col sm:pl-14">
  <div class="bg-blue-500 text-white px-1/2 p-8 flex items-center justify-between">
    <div class="text-white">
      <h1 class="text-xl font-bold">Attendances</h1>
      <Link href="{route('subjects.show', subject.id)}" class="text-sm flex items-center gap-2 hover:text-blue-200">
        <ChevronLeft size="16" /> Go Back
      </Link>
    </div>
    <Link href="{route('subjects.attendances.create', subject.id)}" type="button" class="py-3 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg hover:border-green-400 hover:text-green-400 border border-green-200 disabled:opacity-50 disabled:pointer-events-none">
      Add Attendance
    </Link>
  </div>

  <div class="flex">
    <div class="flex-1 text-sm overflow-auto overflow-x-scroll">
      <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-300">
        <tr id="attendanceDates">
          <th class="p-2 border border-gray-400 sticky top-0 left-0 bg-gray-300 z-20">&ZeroWidthSpace;</th>
          {#each attendances as attendance}
          <th class="p-2 border border-gray-400 min-w-[150px] text-center hover:bg-gray-400 cursor-pointer">
            <Link href="{route('subjects.attendances.edit', {subject: subject.id, attendance: attendance.id})}">
              <div class="flex items-center justify-center gap-4">{attendance.date} <Pencil size="14" />
              </div>
            </Link>
          </th>
          {/each}
          <th class="p-2 border border-gray-400 text-center bg-gray-300">Status</th>
          <th class="p-2 border border-gray-400 text-center bg-gray-300">Total Absent</th>
          <th class="p-2 border border-gray-400 text-center bg-gray-300">Total Present</th>
        </tr>
        </thead>
        <tbody>
        {#each subject.students as student, i}
          {@const studentTotalAbsences = student.attendances.reduce((acc, cur) => acc + (cur.pivot.hours || 0), 0)}
          {@const studentTotalPresent = student.attendances.reduce((acc, cur) => acc + (cur.hours || 0), 0)}
          {@const status = student.pivot.status.toUpperCase()}

          <tr class="student-attendance">
            <td class="p-2 border border-gray-400 {studentTotalAbsences >= subject.dropout_threshold && student.pivot.status === 'dropped' ? 'bg-red-400 text-white' : 'bg-gray-200'} sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">
              <b>{student.last_name}</b>, {student.first_name}
            </td>
            {#each student.attendances.sort((a, b) => a.date.localeCompare(b.date)) as attendance}
            <td class="p-2 border border-gray-400 {student.pivot.status === 'dropped' ? 'bg-red-400 text-white' : 'bg-gray-50'}">
              <div class="flex justify-between items-center hs-tooltip">
                <p>{attendance.pivot.hours}</p>
                {#if attendance.pivot.remarks}
                  <span class="hs-tooltip-toggle">
                    <StickyNote size="16" />
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip">
                      {attendance.pivot.remarks}
                    </span>
                  </span>
                {:else if attendance.pivot.return_to_class}
                  <span class="hs-tooltip-toggle">
                    <Undo2 size="16" />
                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white" role="tooltip">
                      Return To Class
                    </span>
                  </span>
                {/if}
              </div>
            </td>
            {/each}
            <td class="border border-gray-400 bg-gray-50 text-center">
              {#if (status === 'RETURN' || status === 'ACTIVE')}
                ACTIVE
              {:else}
                {status}
              {/if}
            </td>
            <td class="border border-gray-400 bg-gray-50 text-center">
              {studentTotalAbsences}
            </td>
            <td class="border border-gray-400 bg-gray-50 text-center">{studentTotalPresent - studentTotalAbsences}</td>
          </tr>
        {/each}
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Bernabe</b>, Carl Angelo Bugay-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>3</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Bernabe</b>, Marc Aerrol Bugay-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>3</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>De Leon</b>, Jeremae Galay-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">0</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">6</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Gloria</b>, Neil John Cortez-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>3</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>3</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">6</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">0</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Owel</b>, Genfel Impas-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>3</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Reyes</b>, Joey Arangel-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>3</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">3</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Santes</b>, Clark Arvin Tumang-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip">-</div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip">-</div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">0</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">0</td>-->
<!--        </tr>-->
<!--        <tr class="student-attendance">-->
<!--          <td-->
<!--            class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10">-->
<!--            <b>Villafuerte</b>, Bea Sioson-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="p-2 border border-gray-400 bg-gray-50">-->
<!--            <div class="flex justify-between items-center hs-tooltip"><p>0</p></div>-->
<!--          </td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">ACTIVE</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">0</td>-->
<!--          <td class="border border-gray-400 bg-gray-50 text-center">6</td>-->
<!--        </tr>-->
        <tr id="attendanceFooter">
          <td class="p-2 border border-gray-400 bg-gray-200 sticky max-w-[256px] min-w-[256px] text-nowrap overflow-ellipsis overflow-hidden left-0 z-10"></td>
          {#each attendances as attendance}
            <td class="p-2 border border-gray-400 min-w-[150px] text-center">{attendance.hours}</td>
          {/each}
          <td class="border border-gray-400 bg-gray-200 text-center"></td>
          <td class="border border-gray-400 bg-gray-200 text-center">TOTAL</td>
          <td class="border border-gray-400 bg-gray-200 text-center">{attendances.reduce((acc, cur) => acc + (cur.hours || 0), 0)}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
