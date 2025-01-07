<script>
  import logo from "$lib/static/ic_launcher.png";
  import { inertia } from '@inertiajs/svelte';
  import { Toaster } from "$lib/components/ui/sonner";

  let { children } = $props();


  import * as Sheet from "$lib/components/ui/sheet/index.js";
  import * as Breadcrumb from "$lib/components/ui/breadcrumb/index.js";
  import * as DropdownMenu from "$lib/components/ui/dropdown-menu/index.js";
  import * as Card from '$lib/components/ui/card/index.js';
  import * as Tabs from '$lib/components/ui/tabs/index.js';
  import * as Table from '$lib/components/ui/table/index.js';
  import * as Pagination from '$lib/components/ui/pagination/index.js';

  import Sidebar from "$lib/components/molecules/Sidebar.svelte";

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
</script>

<div class="bg-muted/40 flex min-h-screen w-full flex-col">
  <Toaster />
  <Sidebar />

  {@render children()}
</div>
