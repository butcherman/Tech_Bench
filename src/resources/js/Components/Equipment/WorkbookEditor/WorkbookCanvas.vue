<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import {
    equipmentType,
    isDirty,
    resetWorkbook,
    saveWorkbook,
    workbookData,
} from "@/Composables/Equipment/WorkbookEditor.module";
import { computed } from "vue";

const dirtyVariant = computed<elementVariant>(() =>
    isDirty.value ? "warning" : "primary"
);
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
        <div>{{ workbookData }}</div>
    </Card>
</template>
