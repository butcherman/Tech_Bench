<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import CanvasBody from "./CanvasBody.vue";
import CanvasHeader from "./CanvasHeader.vue";
import { dataPost } from "@/Composables/axiosWrapper.module.js";
import { ref, unref } from "vue";
import { useAppStore } from "@/Stores/AppStore.js";
import {
    equipmentType,
    isDirty,
    onSuccessfulSave,
    resetWorkbook,
    workbookData,
} from "@/Composables/Workbook/WorkbookEditor.module.js";
import { useForm } from "vee-validate";

const appStore = useAppStore();
const dirtyVariant = ref<elementVariant>("primary");

useForm({
    name: "workbook-builder",
});

const saveWorkbook = (): void => {
    dataPost(route("workbooks.store", equipmentType.value?.equip_id), {
        workbook_data: unref(workbookData),
    }).then((res) => {
        if (res && res.data.success) {
            onSuccessfulSave();
            appStore.pushFlashMsg({
                id: "new",
                type: "success",
                message: "Workbook Saved",
            });
        }
    });
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
                    @click="resetWorkbook"
                />
                <BaseBadge
                    icon="save"
                    :class="{ 'opacity-50': !isDirty }"
                    :variant="dirtyVariant"
                    v-tooltip.left="'Save Workbook'"
                    @click="saveWorkbook"
                />
            </div>
        </template>
        <div class="h-full flex flex-col gap-5">
            <CanvasHeader />
            <form class="grow" novalidate @submit.prevent>
                <CanvasBody class="h-full" />
            </form>
            <CanvasHeader is-footer />
        </div>
    </Card>
</template>
