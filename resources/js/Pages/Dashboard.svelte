<script module>
  export { default as layout } from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import { page, Link, router } from '@inertiajs/svelte';

  import * as DropdownMenu from '$lib/components/ui/dropdown-menu/index.js';

  import * as Card from '$lib/components/ui/card/index.js';
  import * as Tabs from '$lib/components/ui/tabs/index.js';
  import * as Table from '$lib/components/ui/table/index.js';
  import * as Pagination from '$lib/components/ui/pagination/index.js';

  import ChevronLeft from "lucide-svelte/icons/chevron-left";
  import ChevronRight from "lucide-svelte/icons/chevron-right";
  import Copy from "lucide-svelte/icons/copy";
  import CreditCard from "lucide-svelte/icons/credit-card";
  import File from "lucide-svelte/icons/file";
  import ListFilter from "lucide-svelte/icons/list-filter";
  import EllipsisVertical from "lucide-svelte/icons/ellipsis-vertical";
  import Truck from "lucide-svelte/icons/truck";

  import { Badge } from "$lib/components/ui/badge/index.js";
  import { Button } from "$lib/components/ui/button/index.js";
  import { Separator } from "$lib/components/ui/separator/index.js";
  import {toast} from "svelte-sonner";
  import {Eye} from "lucide-svelte";
  import {DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from "$lib/components/ui/dropdown-menu/index.js";

  function sync() {
    router.get(route('sync'), {}, {
      onSuccess: function() {
        toast.success("Sync Successful", {
          position: 'bottom-right',
          description: 'Data has been successfully synced'
        });
      }
    })
  }

  let activeAcademicYear = $derived.by(() => {
    let academicYear = $page.props.app.settings.academic_year;
    let currentSY = $page.props.app.settings.academic_years.find((el) => el.id === Number(academicYear));
    return currentSY['school_year'] + ' / ' + currentSY['semester'];
  });
</script>

<div class="flex flex-col sm:gap-4 sm:py-4 sm:pl-14">
  <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
    <div class="grid auto-rows-max items-start gap-4 md:gap-8">
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {#if $page.props.auth.user.role === 'admin'}
          <Card.Root>
            <Card.Content>
              <Card.Description>Active SY / Semester</Card.Description>
              <Card.Title class="text-2xl">{activeAcademicYear ?? ''}</Card.Title>
            </Card.Content>
          </Card.Root>
        {:else}
          <Card.Root>
            <Card.Content>
              <Card.Description>My Classes</Card.Description>
              <Card.Title class="text-2xl">{$page.props.subjectCount ?? 0}</Card.Title>
            </Card.Content>
            <Card.Footer>
              <Link href={route('subjects.index')} class="text-xs hover:text-blue-700 text-blue-600">View All Subjects</Link>
            </Card.Footer>
          </Card.Root>
        {/if}

        <Card.Root>
          <Card.Content>
            <Card.Description>Last Sync</Card.Description>
            <Card.Title class="text-2xl">{$page.props.lastSync}</Card.Title>
          </Card.Content>
          <Card.Footer>
            <button onclick="{sync}" class="py-1 rounded-md hover:bg-gray-100 px-4 border-gray-200 border text-sm">Sync</button>
          </Card.Footer>
        </Card.Root>
      </div>

      <Card.Root>
        <Card.Header class="px-7">
          <Card.Title>Reports</Card.Title>
          <Card.Description>List of reports that can be viewed</Card.Description>
        </Card.Header>
        <Card.Content>
          <Table.Root>
            <Table.Header>
              <Table.Row>
                <Table.Head>Report</Table.Head>
                <Table.Head>Frequency</Table.Head>
                <Table.Head class="text-right">Action</Table.Head>
              </Table.Row>
            </Table.Header>
            <Table.Body>
              <Table.Row>
                <Table.Cell><b>Subject Loading</b></Table.Cell>
                <Table.Cell>
                  <Badge>One Time</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Link href="{route('reports.subjectLoading')}" variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Link>
                </Table.Cell>
              </Table.Row>
              <Table.Row>
                <Table.Cell><b>Enrollment Report</b></Table.Cell>
                <Table.Cell>
                  <Badge>One Time</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Button>
                </Table.Cell>
              </Table.Row>
              <Table.Row>
                <Table.Cell><b>Report on Absences</b></Table.Cell>
                <Table.Cell>
                  <Badge>Weekly</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Button>
                </Table.Cell>
              </Table.Row>
              <Table.Row>
                <Table.Cell><b>Report on Dropouts</b></Table.Cell>
                <Table.Cell>
                  <Badge>Monthly</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Button>
                </Table.Cell>
              </Table.Row>
              <Table.Row>
                <Table.Cell><b>Report on Academics</b></Table.Cell>
                <Table.Cell>
                  <Badge>Per Period</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Button>
                </Table.Cell>
              </Table.Row>
              <Table.Row>
                <Table.Cell><b>Grading Sheet</b></Table.Cell>
                <Table.Cell>
                  <Badge>Per Period</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Button>
                </Table.Cell>
              </Table.Row>
              <Table.Row>
                <Table.Cell><b>Completion / Removal List</b></Table.Cell>
                <Table.Cell>
                  <Badge>One Time</Badge>
                </Table.Cell>
                <Table.Cell class="text-right flex justify-end items-center gap-2">
                  <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                    <Eye size={16} class="w-auto"/>
                  </Button>
                </Table.Cell>
              </Table.Row>
            </Table.Body>
          </Table.Root>
<!--          <div class="hs-accordion bg-white border -mt-px">-->
<!--            <button class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none" aria-expanded="true">-->
<!--              <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
<!--                <path d="M5 12h14"></path>-->
<!--                <path d="M12 5v14"></path>-->
<!--              </svg>-->
<!--              <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
<!--                <path d="M5 12h14"></path>-->
<!--              </svg>-->
<!--              Enrollment Report-->
<!--            </button>-->
<!--            <div class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region">-->
<!--              Hello World-->
<!--            </div>-->
<!--          </div>-->
        </Card.Content>
      </Card.Root>
    </div>
  </main>
</div>
