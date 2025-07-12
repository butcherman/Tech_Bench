<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import WorkbookFooter from "./WorkbookFooter.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import { computed } from "vue";
import { isDirty, saveWorkbook } from "@/Composables/Equipment/WorkbookEditor";

const dirtyVariant = computed(() => (isDirty.value ? "warning" : "primary"));
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <template #append-title>
            <BaseBadge
                icon="save"
                :variant="dirtyVariant"
                v-tooltip="'Save Workbook'"
                @click="saveWorkbook()"
            />
        </template>
        <div class="flex flex-col h-full gap-2">
            <WorkbookHeader />
            <WorkbookBody class="grow" />
            <WorkbookFooter />
        </div>
    </Card>
</template>
