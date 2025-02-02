<script>
import logo from "$lib/static/ic_launcher.png";
import {inertia, Link, page, router} from '@inertiajs/svelte';
import * as Tooltip from "$lib/components/ui/tooltip/index.js";
import * as DropdownMenu from "$lib/components/ui/dropdown-menu/index.js";
import * as AlertDialog from "$lib/components/ui/alert-dialog/index.js";
import * as HoverCard from "$lib/components/ui/hover-card/index.js";
import * as Popover from "$lib/components/ui/popover";
import * as Select from "$lib/components/ui/select/index.js";

import {Book, ChartLine, UsersRound, Settings, Package, House, GraduationCap, FileSpreadsheet} from "lucide-svelte";
import {Button} from "$lib/components/ui/button/index.js";
import {Label} from "$lib/components/ui/label/index.js";
import {onMount} from "svelte";

function updateSetting(e) {
  router.post('/settings', {
    [e.target.id]: e.target.value
  }, {
    async: true
  });
}

</script>

<aside class="bg-background fixed inset-y-0 left-0 z-10 hidden w-14 flex-col border-r sm:flex">
  <nav class="flex flex-col items-center gap-4 px-2 sm:py-5">
    <AlertDialog.Root>
      <AlertDialog.Trigger asChild let:builder>
        <div {...builder} use:builder.action class="cursor-pointer text-primary-foreground group flex h-9 w-9 shrink-0 items-center justify-center gap-2 rounded-full text-lg font-semibold md:h-8 md:w-8 md:text-base">
          <img alt="EPCST Toolkit Logo" src={logo} class="h-8 w-8 transition-all group-hover:scale-110" />
          <span class="sr-only">EPCST Toolkit</span>
        </div>
      </AlertDialog.Trigger>
      <AlertDialog.Content class="gap-0">
        <div class="flex justify-end">
          <AlertDialog.Cancel>X</AlertDialog.Cancel>
        </div>
        <div class="flex flex-col justify-center items-center">
          <img src={logo} alt="EPCST Toolkit Logo" width={48} />
          <h1 class="font-bold text-xl">EPCST Toolkit</h1>
          <p class="text-muted-foreground text-sm">This system was made in an effort to make reporting easier for teachers.</p>

          <p class="text-muted-foreground text-xs mt-4">Version <b>{$page.props.app.version}</b></p>
          <p class="text-muted-foreground text-xs">
            Made by the
            <HoverCard.Root>
              <HoverCard.Trigger class="text-orange-500">CS Department</HoverCard.Trigger>
              <HoverCard.Content class="flex gap-2 flex-col">
                <p>Led by <b>Joseph G. Chua</b></p>
                <p>Many thanks to the entire CS department faculty for testing and providing feedbacks.</p>
              </HoverCard.Content>
            </HoverCard.Root>
          </p>
        </div>
      </AlertDialog.Content>
    </AlertDialog.Root>
    <Tooltip.Root openDelay="0">
      <Tooltip.Trigger asChild let:builder>
        <a
          href="{route('dashboard')}"
          class="{$page.url === '/dashboard' ? 'bg-accent text-accent-foreground' : 'text-muted-foreground'}  hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          use:builder.action
          {...builder}
          use:inertia
        >
          <House class="h-5 w-5" />
          <span class="sr-only">Dashboard</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Dashboard</Tooltip.Content>
    </Tooltip.Root>
    {#if $page.props.auth.user.role !== 'admin'}
    <Tooltip.Root openDelay="0">
      <Tooltip.Trigger asChild let:builder>
        <a
          {...builder}
          class="{$page.url.indexOf('/subjects') !== -1 ? 'bg-accent text-accent-foreground' : 'text-muted-foreground'}  hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          href={route('subjects.index')}
          use:builder.action
          use:inertia
        >
          <Book class="h-5 w-5" />
          <span class="sr-only">Subjects</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Sections</Tooltip.Content>
    </Tooltip.Root>
    {/if}

    {#if $page.props.auth.user.role !== 'admin'}
    <Tooltip.Root openDelay="0">
      <Tooltip.Trigger asChild let:builder>
        <a
          {...builder}
          class="{$page.url.indexOf('/reports') !== -1 ? 'bg-accent text-accent-foreground' : 'text-muted-foreground'}  hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          href={route('reports.index')}
          use:builder.action
          use:inertia
        >
          <FileSpreadsheet class="h-5 w-5" />
          <span class="sr-only">Reports</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Reports</Tooltip.Content>
    </Tooltip.Root>
    {/if}
<!--    <Tooltip.Root>-->
<!--      <Tooltip.Trigger asChild let:builder>-->
<!--        <a-->
<!--          href="##"-->
<!--          class="text-muted-foreground hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"-->
<!--          use:builder.action-->
<!--          {...builder}-->
<!--        >-->
<!--          <ChartLine class="h-5 w-5" />-->
<!--          <span class="sr-only">Analytics</span>-->
<!--        </a>-->
<!--      </Tooltip.Trigger>-->
<!--      <Tooltip.Content side="right">Analytics</Tooltip.Content>-->
<!--    </Tooltip.Root>-->
  </nav>
  <nav class="mt-auto flex flex-col items-center gap-4 px-2 sm:py-5">
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <Popover.Root>
          <Popover.Trigger>
            <a
              href="##"
              class="text-muted-foreground hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
              use:builder.action
              {...builder}
            >
              <GraduationCap class="h-5 w-5" />
              <span class="sr-only">Academic Year Selection</span>
            </a>
          </Popover.Trigger>
          <Popover.Content side="right" class="flex flex-col gap-2">
            <select id="academic_year" onchange={updateSetting} class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
              {#each $page.props.app.settings.academic_years as academic_year}
                <option selected={academic_year.id === Number($page.props.app.settings.academic_year)} value="{academic_year.id}">{academic_year.school_year} / {academic_year.semester}</option>
              {/each}
            </select>
            <select id="period" onchange={updateSetting} class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
              <option selected={'prelim' === $page.props.app.settings.period} value="prelim">PRELIM</option>
              <option selected={'midterm' === $page.props.app.settings.period} value="midterm">MIDTERM</option>
              <option selected={'final' === $page.props.app.settings.period} value="final">FINAL</option>
            </select>

          </Popover.Content>
        </Popover.Root>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Academic Year Selection</Tooltip.Content>
    </Tooltip.Root>
    <DropdownMenu.Root>
      <DropdownMenu.Trigger asChild let:builder>
        <Button
          variant="outline"
          size="icon"
          class="overflow-hidden rounded-full"
          builders={[builder]}
        >
          <div class="w-6 overflow-hidden rounded-full">{$page.props.auth.user.first_name.charAt(0) + $page.props.auth.user.last_name.charAt(0)}</div>
        </Button>
      </DropdownMenu.Trigger>
      <DropdownMenu.Content align="end">
        <DropdownMenu.Label>
          <p>{$page.props.auth.user.first_name} {$page.props.auth.user.last_name}</p>
          <p class="text-muted-foreground text-xs">{$page.props.auth.user.role.toUpperCase()}</p>
        </DropdownMenu.Label>
<!--        <DropdownMenu.Separator />-->
<!--        <DropdownMenu.Item>Settings</DropdownMenu.Item>-->
<!--        <DropdownMenu.Item>Support</DropdownMenu.Item>-->
        <DropdownMenu.Separator />
        <DropdownMenu.Item href={route('logout')} class="cursor-pointer">Logout</DropdownMenu.Item>
      </DropdownMenu.Content>
    </DropdownMenu.Root>
  </nav>
</aside>
