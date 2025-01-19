<template>
    <div class="flex flex-col place-items-center gap-3">
        <Card class="tb-card">
            <p class="text-center">
                When a tech tip is created, a "Tech Tip Type" must be selected.
                This helps to determine the purpose of the Tech Tip at a quick
                glance. Common options are:
            </p>
            <div class="flex place-content-center mt-3 border-t">
                <ul class="example-list mt-3">
                    <li>Tech Tip (generic kb article)</li>
                    <li>Documentation (official manufacturer documentation)</li>
                    <li>Software (applications for this Tech Tip)</li>
                </ul>
            </div>
        </Card>
        <Card class="tb-card" title="Current Tech Tip Types">
            <template #append-title>
                <AddButton
                    text="Add Tech Tip Type"
                    size="small"
                    pill
                    @click="addTipType"
                />
            </template>
            <ResourceList :list="tipTypes" label-field="description">
                <template #actions="{ item }">
                    <EditBadge
                        class="mx-1 pointer"
                        v-tooltip="'Edit Description'"
                        @click="editTipType(item)"
                    />
                    <DeleteBadge
                        class="mx-1"
                        v-tooltip="'Delete Tech Tip Type'"
                        confirm
                        @accepted="deleteTipType(item)"
                    />
                </template>
            </ResourceList>
        </Card>
        <Modal ref="tip-type-modal" @hidden="clearModal">
            <TechTipTypeForm
                v-if="modalShown"
                :tip-type="activeType"
                @success="tipTypeModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import TechTipTypeForm from "@/Forms/TechTips/TechTipTypeForm.vue";
import { ref, useTemplateRef } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    tipTypes: tipType[];
}>();

const tipTypeModal = useTemplateRef("tip-type-modal");
const modalShown = ref<boolean>(false);
const activeType = ref<tipType | undefined>(undefined);

/**
 * Show Tech Tip Type Form
 */
const addTipType = (): void => {
    modalShown.value = true;
    tipTypeModal.value?.show();
};

/**
 * Show Edit Tech Tip Type Form
 */
const editTipType = (type: tipType): void => {
    activeType.value = type;
    modalShown.value = true;
    tipTypeModal.value?.show();
};

/**
 * Delete Tech Tip Type after confirmation
 */
const deleteTipType = (type: tipType): void => {
    router.delete(route("admin.tech-tips.tip-types.destroy", type.tip_type_id));
};

/**
 * Reset Tech Tip Type Form
 */
const clearModal = (): void => {
    activeType.value = undefined;
    modalShown.value = false;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<style scoped>
.example-list {
    @apply list-disc;
}
</style>
