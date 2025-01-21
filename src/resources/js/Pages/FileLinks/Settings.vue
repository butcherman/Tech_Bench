<template>
    <div>
        <Card class="tb-card" title="File Link Settings">
            <div class="md:w-3/4 mx-auto">
                <FileLinkSettingsForm :settings="settings" />
            </div>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import FileLinkSettingsForm from "@/Forms/FileLink/FileLinkSettingsForm.vue";

defineProps<{
    settings: {
        default_link_life: number;
        auto_delete: boolean;
        auto_delete_days: number;
        auto_delete_override: boolean;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
