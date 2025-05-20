<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import TipTypeForm from "@/Forms/TechTip/TipTypeForm.vue";
import { ref, useTemplateRef } from "vue";

defineProps<{
    tipTypes: tipType[];
}>();

const modal = useTemplateRef("tip-type-modal");

const activeType = ref<tipType>();

const openEditForm = (tipType: tipType): void => {
    activeType.value = tipType;
    modal.value?.show();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex justify-center">
            <Card class="tb-card">
                <p>
                    When a tech tip is created, a "Tech Tip Type" must be
                    selected. This helps to determine the purpose of the Tech
                    Tip at a quick glance. Common options are:
                </p>
                <div class="flex justify-center mt-2">
                    <ul class="list-disc">
                        <li>Tech Tip (generic kb article)</li>
                        <li>
                            Documentation (official manufacturer documentation)
                        </li>
                        <li>Software (applications for this Tech Tip)</li>
                    </ul>
                </div>
            </Card>
        </div>
        <div class="flex justify-center">
            <Card class="tb-card" title="Existing Tech Tip Types">
                <template #append-title>
                    <AddButton
                        size="small"
                        text="New Tech Tip Type"
                        pill
                        @click="modal?.show()"
                    />
                </template>
                <ResourceList :list="tipTypes" label-field="description">
                    <template #actions="{ item }">
                        <EditBadge class="mx-1" @click="openEditForm(item)" />
                        <DeleteBadge
                            class="mx-1"
                            :href="
                                $route(
                                    'admin.tech-tips.tip-types.destroy',
                                    item.tip_type_id
                                )
                            "
                            delete-method
                            confirm
                        />
                    </template>
                </ResourceList>
            </Card>
        </div>
        <Modal ref="tip-type-modal" @hidden="activeType = undefined">
            <TipTypeForm
                v-if="modal?.isOpen"
                :tip-type="activeType"
                @success="modal?.hide()"
            />
        </Modal>
    </div>
</template>

<style scoped lang="postcss">
.list-disc {
    list-style: disc !important;
}
</style>
