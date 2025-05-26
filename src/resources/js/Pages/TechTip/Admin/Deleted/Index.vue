<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";

defineProps<{
    deletedTips: techTip[];
}>();

const showTipRoute = (techTip: techTip): string => {
    return route("admin.tech-tips.deleted-tips.show", techTip.tip_id);
};

const cols = [
    { label: "Tip ID", field: "tip_id" },
    { label: "Subject", field: "subject" },
    { label: "Date Deleted", field: "deleted_at" },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Deleted Tech Tips">
            <DataTable
                :columns="cols"
                :rows="deletedTips"
                :row-click-link="showTipRoute"
                striped
            />
        </Card>
    </div>
</template>
