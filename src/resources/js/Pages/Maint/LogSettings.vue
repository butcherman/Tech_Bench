<template>
    <Card class="tb-card" title="Log Settings">
        <div class="w-3/4 md:w-1/2 mx-auto">
            <LogSettingsForm
                :days="days"
                :log-level="logLevel"
                :level-list="levelList"
            />
        </div>
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";
import LogSettingsForm from "@/Forms/Maintenance/LogSettingsForm.vue";

defineProps<{
    days: number;
    logLevel: string;
    levelList: logLevel[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
