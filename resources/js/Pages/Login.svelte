<script>
  import { useForm } from "@inertiajs/svelte";
  import { Button } from "$lib/components/ui/button/index.js";
  import logo from "$lib/static/ic_launcher.png";
  import Reload from "svelte-radix/Reload.svelte";

  const form = useForm({
    username: null,
    password: null,
  });

  let { errors } = $props();

  function loginToGradebook(e) {
    e.preventDefault();
    $form.clearErrors();

    $form.post(route('login.auth'));
  }

</script>

<div class="relative flex h-full items-center py-16 bg-gray-100 before:absolute before:top-0 before:start-0 before:bg-[url('https://preline.co/pro/assets/svg/component/hero-gradient.svg')] before:bg-no-repeat before:bg-center before:bg-cover before:size-full before:-z-[0]">
  <main class="w-full max-w-sm mx-auto p-6 z-40">
    <div>
      <div class="text-center mb-3">
        <div class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none focus:opacity-80">
          <img src={logo} alt="logo" width="64"/>
        </div>
      </div>

      <div class="p-5 flex flex-col bg-white shadow rounded-2xl dark:bg-neutral-900">
        <div class="text-center mb-8">
          <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Login</h1>
          <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Enter your username and password used in the grade book system</p>
        </div>

        <form>
          <div class="space-y-5">
            <div class="space-y-3">
              <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Username</label>
                <div class="relative">
                  <input bind:value={$form.username} id="username" type="text" class="py-2.5 px-3 block w-full border-gray-200 rounded-lg text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:placeholder:text-white/60 dark:focus:ring-neutral-600" placeholder="epcst_sample">
                  <p class="text-xs text-red-400">{$form.errors.username}</p>
                </div>
              </div>

              <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-800">Password</label>
                <div class="relative">
                  <input bind:value={$form.password} id="password" type="password" class="py-2.5 px-3 block w-full border-gray-200 rounded-lg text-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="••••••••">
                  <p class="text-xs text-red-400">{$form.errors.password}</p>
                </div>
              </div>

              <Button disabled={$form.processing} onclick={loginToGradebook} class="w-full">
                {#if $form.processing}
                  <Reload class="mr-2 h-4 w-4 animate-spin" />
                  Please wait
                {:else}
                  Connect
                {/if}
              </Button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>


