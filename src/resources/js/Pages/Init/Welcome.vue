<template>
    <div>
        <h1 class="text-center font-bold text-2xl">
            Welcome to the Tech Bench
        </h1>
        <p class="text-center m-5">Looks like this is your first time here.</p>
        <p class="text-center m-5">
            Before you can start using the Tech Bench, we need to do some basic
            setup first.
        </p>
        <p class="text-center">
            <Link :href="$route('init.step-1')" class="btn btn-primary mt-4">
                Setup Tech Bench
            </Link>
        </p>
    </div>
</template>

<script lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";

export default { layout: InitLayout };
</script>
