<script setup lang="ts">
import { ref } from "vue";
import Card from "@/Components/_Base/Card.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import ElementWrapper from "./ElementWrapper.vue";

//
const value = ref("0");

const workbookData = ref({
    header: [
        {
            type: "text",
            tag: "h1",
            text: "Header 1",
            class: "text-center",
        },
        {
            type: "text",
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
                    type: "wrapper",
                    tag: "div",
                    class: "grid grid-cols-2 gap-2",
                    container: [
                        {
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg p-2",
                            container: [
                                {
                                    type: "text",
                                    tag: "legend",
                                    text: "form legend",
                                },
                                {
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
                            type: "wrapper",
                            tag: "fieldset",
                            class: "border border-slate-300 rounded-lg",
                            container: [
                                {
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
</script>

<template>
    <Card class="h-full">
        <div>
            <!-- <template v-for="el in workbookData.header">
                <div v-html="el.html" />
            </template> -->
            <ElementWrapper :component-list="workbookData.header" />
        </div>
        <div>
            <Tabs v-model:value="value">
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
