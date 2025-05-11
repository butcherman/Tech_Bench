<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

interface reportItem {
    name: string;
    url: string;
}

defineProps<{
    reports: {
        [key: string]: reportItem[];
    };
}>();

/**
 * Function to return the link for the selected report
 */
const reportLink = (link: reportItem): string => {
    return link.url;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <Card
            v-for="(reportList, category) in reports"
            class="w-full"
            :title="category.toString()"
        >
            <ResourceList
                label-field="name"
                :list="reportList"
                :link-fn="reportLink"
                center
            />
        </Card>
    </div>
</template>
