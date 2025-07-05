<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import draggableComponent from "vuedraggable";
import ElementWrapper from "./ElementWrapper.vue";
import okModal from "@/Modules/okModal";
import { ref } from "vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";

const allowedInHeader: string[] = ["text", "static"];

const workbookData = ref<workbookWrapper>({
    header: [
        {
            index: 0,
            type: "text",
            tag: "h1",
            text: "Header 1",
            class: "text-center",
        },
        {
            index: 1,
            type: "static",
            tag: "h3",
            text: "[ Customer Name ]",
            class: "text-center",
        },
    ],
    body: [
        {
            page: "0",
            title: "Page 1",
            canPublish: true,
            container: [
                {
                    index: 2,
                    type: "wrapper",
                    tag: "div",
                    class: "grid grid-cols-2 gap-2",
                    container: [
                        {
                            index: 3,
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg p-2",
                            container: [
                                {
                                    index: 4,
                                    type: "text",
                                    tag: "legend",
                                    text: "form legend",
                                },
                                {
                                    index: 5,
                                    tag: "input",
                                    class: "w-full border border-slate-300",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: null,
                                        value: null,
                                    },
                                },
                                {
                                    index: 6,
                                    tag: "input",
                                    class: "w-full border border-slate-300",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: null,
                                        value: null,
                                    },
                                },
                            ],
                        },
                        {
                            index: 7,
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg",
                            container: [
                                {
                                    index: 8,
                                    tag: "input",
                                    class: "w-full border border-slate-400 rounded-lg px-2",
                                    props: {
                                        name: "test_input",
                                        label: "This is a test input",
                                        placeholder: null,
                                        value: null,
                                    },
                                },
                            ],
                        },
                    ],
                },
            ],
        },
        {
            page: "1",
            title: "Page 2",
            canPublish: true,
            data: [],
        },
    ],
    footer: [],
});

const onHeaderDrop = (event: workbookDropEvent) => {
    console.log(event);

    if (event.added) {
        console.log("added element");

        if (!allowedInHeader.includes(event.added.element.type)) {
            console.log(event.added.element.type);
            okModal("Only Text Elements are Allowed in the Header");
            workbookData.value.header.splice(event.added.newIndex, 1);
        }
    }

    if (event.moved) {
        console.log("moved element");
    }
};
</script>

<template>
    <Card class="h-full">
        <draggableComponent
            :list="workbookData.header"
            :group="{ name: 'workbook', put: true }"
            item-key="index"
            @change="onHeaderDrop"
        >
            <template #item="{ element }">
                <div class="hover:border hover:border-green-300">
                    <component
                        :is="element.tag"
                        :class="element.class"
                        v-bind="element.props"
                        v-html="element.text"
                    />
                </div>
            </template>
        </draggableComponent>
        <div>
            <Tabs value="0">
                <TabList>
                    <template v-for="page in workbookData.body">
                        <Tab
                            :value="page.page"
                            pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-1!"
                        >
                            {{ page.title }}
                        </Tab>
                    </template>
                    <Tab
                        value="9999"
                        pt:root:class="border! border-slate-300! rounded-t-lg p-1!"
                    >
                        <fa-icon icon="plus" v-tooltip="'New Page'" />
                    </Tab>
                </TabList>
                <TabPanels>
                    <template v-for="page in workbookData.body">
                        <TabPanel :value="page.page">
                            <form>
                                <ElementWrapper
                                    :component-list="page.container"
                                />
                            </form>
                        </TabPanel>
                    </template>
                </TabPanels>
            </Tabs>
        </div>
    </Card>
</template>
