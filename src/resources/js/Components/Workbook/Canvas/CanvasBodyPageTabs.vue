<script setup lang="ts">
import draggableComponent from "vuedraggable";
import { Tab, TabList } from "primevue";
import {
    workbookData,
    addBlankPage,
    deletePage,
    editNode,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
</script>

<template>
    <TabList class="pt-1">
        <draggableComponent
            item-key="page"
            :list="workbookData.body"
            :group="{ name: 'page-list', pull: false }"
        >
            <template #item="{ element }">
                <Tab
                    :value="element.page"
                    pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0!"
                >
                    <div
                        class="p-1"
                        :class="{ 'bg-red-100': !element.canPublish }"
                    >
                        <div class="text-xs">
                            <span
                                class="text-warning pointer me-1"
                                v-tooltip="'Edit Page Data'"
                                @click="editNode(element)"
                            >
                                <fa-icon icon="pencil" />
                            </span>
                            <span
                                class="text-danger pointer"
                                v-tooltip="'Delete Page'"
                                @click="deletePage(element)"
                            >
                                <fa-icon icon="trash-alt" />
                            </span>
                        </div>
                        <div>
                            {{ element.title }}
                        </div>
                    </div>
                </Tab>
            </template>
        </draggableComponent>
        <Tab
            value="9999"
            pt:root:class="border! border-slate-300! rounded-t-lg p-1!"
            @click="addBlankPage"
        >
            <fa-icon icon="plus" v-tooltip="'New Page'" />
        </Tab>
    </TabList>
</template>
