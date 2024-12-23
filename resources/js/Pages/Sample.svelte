<script>
  import {Link, router, useForm} from '@inertiajs/svelte'
  import { route } from 'ziggy-js';

  const form = useForm({
    name: null,
    email: null,
    password: null,
  });

  let { users } = $props();

  function submit(e) {
    e.preventDefault();

    $form.post(route('register'));
    $form.reset();
  }

  function deleteUser(userId) {
    router.delete(route('userDelete', userId));
  }
</script>

<form onsubmit={submit} class="flex-col gap-2">
  <input type="text" name="name" bind:value={$form.name} />
  {#if $form.errors.name}
    <p class="text-xs text-red-400">{$form.errors.name}</p>
  {/if}
  <input type="email" name="email" bind:value={$form.email} />
  {#if $form.errors.email}
    <p class="text-xs text-red-400">{$form.errors.email}</p>
  {/if}
  <input type="password" name="password" bind:value={$form.password} />
  {#if $form.errors.password}
    <p class="text-xs text-red-400">{$form.errors.password}</p>
  {/if}

  <button disabled={$form.processing}>Register</button>
</form>

<Link href={route('logout')}>Logout</Link>

<ul>
{#each users as user}
  <li>{user.name} - <button onclick={() => deleteUser(user.id)}>DELETE</button></li>
{/each}
</ul>
