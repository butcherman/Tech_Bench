<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import FileLinkSettingsForm from "@/Forms/FileLink/FileLinkSettingsForm.vue";

const props = defineProps<{
    feature_enabled: boolean;
    default_link_life: number;
    auto_delete: boolean;
    auto_delete_days: number;
    auto_delete_override: boolean;
    allow_custom_url: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="File Link Settings">
            <FileLinkSettingsForm v-bind="props" />
        </Card>
    </div>
</template>
