<template>
    <div class="grid md:grid-cols-2 gap-3 my-3">
        <Card title="Resource List">
            <ResourceList :list="resourceListData" />
        </Card>
        <Card title="Stacked Table">
            <TableStacked :items="stackedTable" class="w-full" bordered />
        </Card>
        <Card title="Data Table" class="md:col-span-2">Data Table</Card>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import Card from "../_Base/Card.vue";
import ResourceList from "../_Base/ResourceList.vue";
import TableStacked from "../_Base/TableStacked.vue";

const resourceListData = ref([
    {
        icon: "pencil",
        text: "List Item that is a link",
        href: route("about"),
    },
    {
        icon: "eye",
        text: "List Item with an icon",
    },
    {
        text: "Plain Text Item",
    },
    {
        text: "More Plain Text",
    },
]);

const stackedTable = ref({
    city: "Sacramento",
    state: "California",
    country: "United States",
    true_value: true,
    false_value: false,
});
</script>
