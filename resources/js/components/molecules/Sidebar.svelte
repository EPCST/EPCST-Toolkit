<script>
import logo from "$lib/static/ic_launcher.png";
import {inertia, Link, page} from '@inertiajs/svelte';
import * as Tooltip from "$lib/components/ui/tooltip/index.js";
import * as Sheet from "$lib/components/ui/sheet/index.js";
import * as Breadcrumb from "$lib/components/ui/breadcrumb/index.js";
import * as DropdownMenu from "$lib/components/ui/dropdown-menu/index.js";
import * as Card from '$lib/components/ui/card/index.js';
import * as Tabs from '$lib/components/ui/tabs/index.js';
import * as Table from '$lib/components/ui/table/index.js';
import * as Pagination from '$lib/components/ui/pagination/index.js';

import {Book, ChartLine, UsersRound, Settings, Package, House} from "lucide-svelte";
import {Button} from "$lib/components/ui/button/index.js";
</script>

<aside class="bg-background fixed inset-y-0 left-0 z-10 hidden w-14 flex-col border-r sm:flex">
  <nav class="flex flex-col items-center gap-4 px-2 sm:py-5">
    <a
      href="#"
      class="text-primary-foreground group flex h-9 w-9 shrink-0 items-center justify-center gap-2 rounded-full text-lg font-semibold md:h-8 md:w-8 md:text-base"
    >
      <img alt="EPCST Toolkit Logo" src={logo} class="h-8 w-8 transition-all group-hover:scale-110" />
      <span class="sr-only">EPCST Toolkit</span>
    </a>
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <a
          href="{route('dashboard')}"
          class="{route().current('dashboard') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground'}  hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
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
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <a
          {...builder}
          class="{route().current('sections.index') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground'}  hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          href={route('sections.index')}
          use:builder.action
          use:inertia
        >
          <Book class="h-5 w-5" />
          <span class="sr-only">Sections</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Sections</Tooltip.Content>
    </Tooltip.Root>
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <a
          href="##"
          class="text-muted-foreground hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          use:builder.action
          {...builder}
        >
          <Package class="h-5 w-5" />
          <span class="sr-only">Products</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Products</Tooltip.Content>
    </Tooltip.Root>
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <a
          href="##"
          class="text-muted-foreground hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          use:builder.action
          {...builder}
        >
          <UsersRound class="h-5 w-5" />
          <span class="sr-only">Customers</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Customers</Tooltip.Content>
    </Tooltip.Root>
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <a
          href="##"
          class="text-muted-foreground hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          use:builder.action
          {...builder}
        >
          <ChartLine class="h-5 w-5" />
          <span class="sr-only">Analytics</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Analytics</Tooltip.Content>
    </Tooltip.Root>
  </nav>
  <nav class="mt-auto flex flex-col items-center gap-4 px-2 sm:py-5">
    <Tooltip.Root>
      <Tooltip.Trigger asChild let:builder>
        <a
          href="##"
          class="text-muted-foreground hover:text-foreground flex h-9 w-9 items-center justify-center rounded-lg transition-colors md:h-8 md:w-8"
          use:builder.action
          {...builder}
        >
          <Settings class="h-5 w-5" />
          <span class="sr-only">Settings</span>
        </a>
      </Tooltip.Trigger>
      <Tooltip.Content side="right">Settings</Tooltip.Content>
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
        <DropdownMenu.Label>My Account</DropdownMenu.Label>
        <DropdownMenu.Separator />
        <DropdownMenu.Item>Settings</DropdownMenu.Item>
        <DropdownMenu.Item>Support</DropdownMenu.Item>
        <DropdownMenu.Separator />
        <DropdownMenu.Item>
          <Link href={route('logout')}>Logout</Link>
        </DropdownMenu.Item>
      </DropdownMenu.Content>
    </DropdownMenu.Root>
  </nav>
</aside>
