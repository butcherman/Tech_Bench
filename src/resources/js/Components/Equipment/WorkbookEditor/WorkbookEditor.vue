<script setup lang="ts">
import WorkbookCanvas from "./WorkbookCanvas.vue";
import WorkbookComponents from "./WorkbookComponents.vue";

//
</script>

<template>
    <div class="flex grow gap-2">
        <div class="basis-1/4">
            <WorkbookComponents />
        </div>
        <div class="grow">
            <WorkbookCanvas />
        </div>
    </div>
</template>
