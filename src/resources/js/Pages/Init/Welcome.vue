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
            <BaseButton
                :href="$route('init.step-1')"
                class="btn btn-primary mt-4"
            >
                Setup Tech Bench
            </BaseButton>
        </p>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
