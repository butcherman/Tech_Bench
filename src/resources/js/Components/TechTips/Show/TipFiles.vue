<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import prettyBytes from "pretty-bytes";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { getIconFromFilename } from "@/Composables/fileIcons.module";

defineProps<{
    files: fileUpload[];
}>();

const onRowClick = (event: MouseEvent, row: fileUpload) => {
    if (event) {
        window.open(route("download", [row.file_id, row.file_name]));
    }
};
</script>

<template>
    <Card class="tb-card" title="Attachments">
        <ResourceList :list="files" hover-row center @row-clicked="onRowClick">
            <template #list-item="{ item }">
                <span
                    v-html="getIconFromFilename(item.file_name)"
                    class="me-1"
                />
                {{ item.file_name }} ({{ prettyBytes(item.file_size) }})
            </template>
        </ResourceList>
    </Card>
</template>
