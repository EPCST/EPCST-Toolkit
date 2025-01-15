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
  import {TableCell} from "$lib/components/ui/table/index.js";
  import {Eye} from "lucide-svelte";

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
</script>

<div class="flex flex-col sm:gap-4 sm:py-4 sm:pl-14">
  <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
    <div class="grid auto-rows-max items-start gap-4 md:gap-8">
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <Card.Root>
          <Card.Content>
            <Card.Description>My Classes</Card.Description>
            <Card.Title class="text-2xl">{$page.props.subjectCount}</Card.Title>
          </Card.Content>
          <Card.Footer>
            <Link href={route('subjects.index')} class="text-xs hover:text-blue-700 text-blue-600">View All Subjects</Link>
          </Card.Footer>
        </Card.Root>
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
          <Card.Title>Faculty Checklist</Card.Title>
          <Card.Description>List of reports / requirements that needs to be done by the faculty members</Card.Description>
        </Card.Header>
        <Card.Content>
          <Table.Root>
            <Table.Header>
              <Table.Row>
                <Table.Head>Student Overview</Table.Head>
                <Table.Head class="hidden sm:table-cell">
                  Last sync
                </Table.Head>
                <Table.Head class="text-right"></Table.Head>
              </Table.Row>
            </Table.Header>
            <Table.Body>
              {#each $page.props.users as user}
                <Table.Row class="bg-accent">
                  <Table.Cell>
                    <div class="font-medium">{user?.first_name} {user?.middle_name} {user?.last_name}</div>
                    <div class="text-muted-foreground hidden text-sm md:inline">
                      {user.email}
                    </div>
                  </Table.Cell>
                  <Table.Cell class="hidden sm:table-cell">
                    <Badge class="text-xs" variant="outline">
                      3 minutes ago
                    </Badge>
                  </Table.Cell>
                  <TableCell class="text-right flex justify-end items-center gap-2">
                    <Button variant="outline" size="icon" class="bg-blue-600 hover:bg-blue-700 text-white hover:text-white">
                      <Eye size={16} class="w-auto"/>
                    </Button>
                  </TableCell>
                </Table.Row>
              {/each}
            </Table.Body>
          </Table.Root>
        </Card.Content>
      </Card.Root>
    </div>
  </main>
</div>
