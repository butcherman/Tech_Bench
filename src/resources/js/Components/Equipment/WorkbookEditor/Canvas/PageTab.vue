<script setup lang="ts">
import { Tab } from "primevue";
import {
    deletePage,
    editElement,
} from "@/Composables/Equipment/WorkbookEditor.module";

defineProps<{
    tabData: workbookPage;
}>();
</script>

<template>
    <Tab
        :value="tabData.page"
        pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0!"
    >
        <div class="p-1" :class="{ 'bg-red-100': !tabData.canPublish }">
            <div class="text-xs">
                <span
                    class="text-warning pointer me-1"
                    v-tooltip="'Edit Page Data'"
                    @click="editElement(tabData)"
                >
                    <fa-icon icon="pencil" />
                </span>
                <span
                    class="text-danger pointer"
                    v-tooltip="'Delete Page'"
                    @click="deletePage(tabData)"
                >
                    <fa-icon icon="trash-alt" />
                </span>
            </div>
            <div>
                {{ tabData.title }}
            </div>
        </div>
    </Tab>
</template>
