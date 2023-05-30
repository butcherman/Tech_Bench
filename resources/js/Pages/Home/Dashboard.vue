<template>
    <Head title="Dashboard" />
    <div>
        <h1 class="text-center">Logged In</h1>
        <AtomLoader />
        <HalfCircleLoader />
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import AtomLoader from "@/Components/Base/Loader/AtomLoader.vue";
import HalfCircleLoader from "@/Components/Base/Loader/HalfCircleLoader.vue";
</script>

<script lang="ts">
export default { layout: App };
</script>
