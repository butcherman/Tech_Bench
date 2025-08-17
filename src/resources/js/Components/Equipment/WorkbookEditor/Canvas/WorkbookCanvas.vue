<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import CanvasBody from "./CanvasBody.vue";
import CanvasHeader from "./CanvasHeader.vue";
import Card from "@/Components/_Base/Card.vue";
import { computed } from "vue";
import {
    equipmentType,
    isDirty,
    resetWorkbook,
} from "@/Composables/Equipment/WorkbookEditor.module";

const dirtyVariant = computed<elementVariant>(() =>
    isDirty.value ? "warning" : "primary"
);

/**
 * Save the WB to the Database
 */
const saveWorkbook = () => {
    console.log("save");
};
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <template #append-title>
            <div class="flex gap-1 text-sm">
                <a
                    v-if="equipmentType"
                    :href="$route('workbooks.show', equipmentType?.equip_id)"
                    target="_blank"
                >
                    <BaseBadge
                        icon="eye"
                        variant="help"
                        class="h-full"
                        v-tooltip.left="'Preview'"
                    />
                </a>
                <BaseBadge
                    icon="rotate-left"
                    variant="danger"
                    :class="{ 'opacity-50': !isDirty }"
                    :disabled="!isDirty"
                    v-tooltip.left="'Undo Changes Since Last Save'"
                    @click="resetWorkbook()"
                />
                <BaseBadge
                    icon="save"
                    :class="{ 'opacity-50': !isDirty }"
                    :variant="dirtyVariant"
                    v-tooltip.left="'Save Workbook'"
                    @click="saveWorkbook()"
                />
            </div>
        </template>
        <div class="h-full flex flex-col gap-5">
            <CanvasHeader />
            <CanvasBody class="grow" />
            <CanvasHeader is-footer />
        </div>
    </Card>
</template>
