<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<template>
    <Card class="tb-card">
        <h1 class="text-center font-bold text-2xl">
            Welcome to the Tech Bench
        </h1>
        <p class="text-center m-5">Looks like this is your first time here.</p>
        <p class="text-center m-5">
            Before you can start using the Tech Bench, we need to do some basic
            setup first.
        </p>
        <p class="text-center">
            <BaseButton :href="$route('init.step-1')" class="tb-gap">
                Setup Tech Bench
            </BaseButton>
        </p>
    </Card>
</template>
