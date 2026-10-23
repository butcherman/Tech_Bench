<script setup lang="ts">
import { Tab, TabList } from "primevue";

const props = defineProps<{
    bodySkeleton: workbookPage[];
}>();
</script>

<template>
    <TabList>
        <template v-for="element in bodySkeleton">
            <Tab
                :value="element.page"
                pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0! group/tab"
            >
                <div class="p-1" :class="{ 'bg-red-100': !element.canPublish }">
                    <div>
                        {{ element.title }}
                    </div>
                </div>
            </Tab>
        </template>
    </TabList>
</template>
