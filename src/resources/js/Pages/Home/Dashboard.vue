<template>
    <div>
        <h2>Hello First</h2>
        <h1 v-for="n in 10">Hello World - {{ n }}</h1>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
