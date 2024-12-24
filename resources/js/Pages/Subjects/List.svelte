<script module>
  export { default as layout } from '$lib/components/molecules/Layout.svelte'
</script>

<script>
  import logo from "$lib/static/ic_launcher.png";
  import { onMount } from 'svelte';
  import { router, inertia } from '@inertiajs/svelte';

  import * as Sheet from "$lib/components/ui/sheet/index.js";
  import * as Breadcrumb from "$lib/components/ui/breadcrumb/index.js";
  import * as DropdownMenu from "$lib/components/ui/dropdown-menu/index.js";
  import * as Card from '$lib/components/ui/card/index.js';
  import * as Tabs from '$lib/components/ui/tabs/index.js';
  import * as Table from '$lib/components/ui/table/index.js';
  import * as Pagination from '$lib/components/ui/pagination/index.js';

  import Sidebar from "$lib/components/molecules/Sidebar.svelte";

  onMount(() => {
    window.HSStaticMethods.autoInit();
  });

  import Eye from "lucide-svelte/icons/eye";
  import ChevronLeft from "lucide-svelte/icons/chevron-left";
  import ChevronRight from "lucide-svelte/icons/chevron-right";
  import Copy from "lucide-svelte/icons/copy";
  import CreditCard from "lucide-svelte/icons/credit-card";
  import File from "lucide-svelte/icons/file";
  import House from "lucide-svelte/icons/house";
  import ChartLine from "lucide-svelte/icons/chart-line";
  import ListFilter from "lucide-svelte/icons/list-filter";
  import EllipsisVertical from "lucide-svelte/icons/ellipsis-vertical";
  import Package from "lucide-svelte/icons/package";
  import Package2 from "lucide-svelte/icons/package-2";
  import PanelLeft from "lucide-svelte/icons/panel-left";
  import Search from "lucide-svelte/icons/search";
  import ShoppingCart from "lucide-svelte/icons/shopping-cart";
  import Truck from "lucide-svelte/icons/truck";
  import UsersRound from "lucide-svelte/icons/users-round";

  import { Badge } from "$lib/components/ui/badge/index.js";
  import { Button } from "$lib/components/ui/button/index.js";
  import { Input } from "$lib/components/ui/input/index.js";
  import { Progress } from "$lib/components/ui/progress/index.js";
  import { Separator } from "$lib/components/ui/separator/index.js";

  function viewSubject(e) {
    let subjectId = e.target.closest('tr').dataset.id;
    
    router.visit(`/subjects/${subjectId}`);
  }
</script>

<div class="flex flex-col sm:gap-4 sm:pl-14 m-4">
  <h1 class="font-bold text-2xl">My Classes</h1>
  <div class="hs-accordion-group">
    <div class="hs-accordion bg-white border -mt-px">
      <button class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none" aria-expanded="true" aria-controls="subject-1">
        <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14"></path>
          <path d="M12 5v14"></path>
        </svg>
        <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14"></path>
        </svg>
        Cybersecurity
      </button>
      <div id="subject-1" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-bordered-heading-one">
        <Table.Root>
          <Table.Header>
           <Table.Row>
            <Table.Head>Section Name</Table.Head>
            <Table.Head># of Students</Table.Head>
            <Table.Head class="text-right">Action</Table.Head>
           </Table.Row>
          </Table.Header>
          <Table.Body>
           <Table.Row data-id="1" class="hover:cursor-pointer" onclick={(e) => viewSubject(e)}>
            <Table.Cell><b>IT 201</b></Table.Cell>
            <Table.Cell>0</Table.Cell>
            <Table.Cell class="text-right flex justify-end items-center gap-2">
              <DropdownMenu.Root>
                <DropdownMenu.Trigger asChild let:builder>
                  <Button
                    variant="outline"
                    size="icon"
                    class="overflow-hidden"
                    builders={[builder]}
                  >
                  <EllipsisVertical size={16} class="w-auto" />
                  </Button>
                </DropdownMenu.Trigger>
                <DropdownMenu.Content align="end">
                  <DropdownMenu.Item>Edit</DropdownMenu.Item>
                  <DropdownMenu.Item>Remove</DropdownMenu.Item>
                </DropdownMenu.Content>
              </DropdownMenu.Root>
            </Table.Cell>
           </Table.Row>
          </Table.Body>
         </Table.Root>
      </div>
    </div>
  </div>
</div>