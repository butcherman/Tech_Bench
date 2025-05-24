import TableStacked from '@/Components/_Base/TableStacked.vue';
<script setup lang="ts">
import ClipboardCopy from "../_Base/ClipboardCopy.vue";
import TableStacked from "../_Base/TableStacked.vue";
import { computed } from "vue";

const props = defineProps<{
    link: fileLink;
}>();

const tableData = computed(() => {
    return {
        name: props.link.link_name,
        expires: props.link.expire,
        allow_upload: props.link.allow_upload,
        has_instructions: props.link.instructions ? true : false,
    };
});
</script>

<template>
    <div>
        <TableStacked class="w-full" :items="tableData" />
        <div v-if="!link.is_expired" class="flex mt-2">
            <strong> Public URL: </strong>
            <a class="text-blue-600" :href="link.public_href" target="_blank">
                {{ link.public_href }}
            </a>
            <ClipboardCopy :value="link.public_href" />
        </div>
    </div>
</template>
