<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import ReportLoader from "@/Components/Report/ReportLoader.vue";
import { Deferred } from "@inertiajs/vue3";

defineProps<{
    template?: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <Deferred data="template">
            <template #fallback>
                <ReportLoader />
                <h3 class="text-center">Gathering Report Data</h3>
                <h5 class="text-center">Please Wait</h5>
            </template>
            <div v-html="template" />
        </Deferred>
    </div>
</template>
