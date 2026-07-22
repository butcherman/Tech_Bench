<script setup lang="ts">
import LogoImage from "@/core/components/LogoImage.vue";

// import AppLayout from "@/layouts/AppLayout.vue";
// TODO - Properly set this
</script>

<script lang="ts">
// export default { layout: AppLayout };
</script>
<template>
    <div class="flex items-center justify-center h-screen mx-1 py-2">
        <div>
            <LogoImage />
            <h1 class="text-center text-danger">Something Bad Happened</h1>
        </div>
    </div>
</template>
