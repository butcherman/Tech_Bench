<script setup lang="ts">
import ComponentListContainer from "./ComponentListContainer.vue";
import { useForm } from "vee-validate";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";

const props = defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    isPreview?: boolean;
    values: { [index: string]: string };
}>();

const {
    handleSubmit,
    setFieldValue,
    setFieldError,
    values,
    resetForm,
    meta,
    handleReset,
} = useForm({
    initialValues: props.values,
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent class="h-full">
        <Tabs v-bind:value="activePage" class="h-full">
            <TabList>
                <template v-for="page in workbookData.body" :key="page.page">
                    <Tab
                        :value="page.page"
                        pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0!"
                    >
                        <div
                            class="p-1"
                            :class="{ 'bg-red-100': !page.canPublish }"
                        >
                            {{ page.title }}
                        </div>
                    </Tab>
                </template>
            </TabList>
            <TabPanels class="h-full border border-slate-300 rounded-sm p-1!">
                <TabPanel
                    v-for="page in workbookData.body"
                    :key="page.page"
                    :value="page.page"
                    class="h-full relative"
                >
                    <ComponentListContainer
                        :component-list="page.container"
                        class="h-full"
                    />
                </TabPanel>
            </TabPanels>
        </Tabs>
    </form>
</template>
