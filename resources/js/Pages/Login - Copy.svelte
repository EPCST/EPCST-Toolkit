<script>
  import { useForm } from "@inertiajs/svelte";
  import logo from "$lib/static/ic_launcher.png";
  import { Button } from "$lib/components/ui/button/index.js";
  import Reload from "svelte-radix/Reload.svelte";

  import * as Card from "$lib/components/ui/card/index.js";
  import { Input } from "$lib/components/ui/input/index.js";
  import { Label } from "$lib/components/ui/label/index.js";
  import * as Tabs from "$lib/components/ui/tabs/index.js";

  const form = useForm({
    username: null,
    password: null,
  });

  let { errors } = $props();

  function loginToGradebook(e) {
    e.preventDefault();
    $form.clearErrors();

    $form.post(route('login'));
  }

</script>

<header class="md:py-1 border-b border-gray-200 dark:border-neutral-700 flex flex-wrap md:justify-start md:flex-nowrap z-50 bg-white dark:bg-neutral-900">
  <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-5 items-center gap-y-2 md:gap-y-0 basis-full w-full py-2.5 px-5 md:px-8 2xl:px-5">
    <div class="md:order-1 col-span-1 md:col-span-1 flex items-center gap-x-3">
      <a class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none focus:opacity-80" href="/" aria-label="Preline">
        <img src={logo} alt="logo" width="48" />
      </a>
    </div>
  </div>
</header>

<main id="content">
  <!-- Content -->
  <div class="max-w-xl w-full mx-auto py-5 sm:py-10 px-5 md:px-8 2xl:px-5">
    <Card.Root>
      <Card.Header>
        <Card.Title>Login to Gradebook</Card.Title>
        <Card.Description>Use your login credential from the gradebook system.</Card.Description>
      </Card.Header>
      <Card.Content>
        <form>
          <div class="grid w-full items-center gap-4">
            <div class="flex flex-col space-y-1.5">
              <Label for="username">Username</Label>
              <Input id="username" bind:value={$form.username} placeholder="epcst_sample" />
              <span class="text-red-500 text-xs">{$form.errors.username}</span>
            </div>
            <div class="flex flex-col space-y-1.5">
              <Label for="password">Password</Label>
              <Input id="password" bind:value={$form.password} type="password" name="password" placeholder="••••••••" />
            </div>
          </div>
        </form>
      </Card.Content>
      <Card.Footer class="flex flex-col">
        <Button disabled={$form.processing} class="w-full">
          {#if $form.processing}
            <Reload class="mr-2 h-4 w-4 animate-spin" />
            Connecting...
          {:else}
            <Button class="w-full" onclick={loginToGradebook}>Login</Button>
          {/if}
        </Button>
      </Card.Footer>
    </Card.Root>
  </div>
</main>
