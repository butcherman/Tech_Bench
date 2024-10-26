<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Details:</div>
            <TableStacked :rows="tableData.data" title-case />
            <div v-if="!link.is_expired">
                <ClipboardCopy
                    class="float-end"
                    :value="link.public_href"
                    title="Copy Public URL to Clipboard"
                />
                <strong class="me-2">Public URL:</strong>
                <a :href="link.public_href" target="_blank">
                    {{ link.public_href }}
                </a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import TableStacked from "../_Base/TableStacked.vue";
import ClipboardCopy from "../_Base/Badges/ClipboardCopy.vue";

defineProps<{
    link: fileLink;
    tableData: {
        data: fileLink;
    };
}>();
</script>
