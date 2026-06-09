<script setup lang="ts">
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import Modal from "@/Components/_Base/Modal.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import ShowHeader from "@/Components/Workbook/ShowHeader.vue";
import WorkbookWrapper from "@/Components/Workbook/WorkbookWrapper.vue";
import { initWorkbook } from "@/Composables/Workbook/CustomerWorkbook.module";
import { onMounted, useTemplateRef } from "vue";
import { Deferred, router } from "@inertiajs/vue3";

const props = defineProps<{
    customer: customer;
    equipment: customerEquipment;
    workbook: customerWorkbook;
    workbookValues?: { [key: string]: string };
}>();

onMounted(() => {
    initWorkbook(props.workbook);
});

const updateModal = useTemplateRef("update-workbook-modal");

const updateWorkbook = () => {
    router.put(
        route("customers.equipment.workbook.update", [
            props.customer.slug,
            props.equipment.cust_equip_id,
        ]),
        {},
        {
            onSuccess: () => updateModal.value?.hide(),
        },
    );
};
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <ShowHeader
            :customer="customer"
            :equipment="equipment"
            :workbook="workbook"
        />
        <div class="grow">
            <Deferred data="workbook-values">
                <template #fallback>
                    <Card class="h-full">
                        <div class="flex flex-col justify-center h-full">
                            <AtomLoader text="Loading Workbook" />
                        </div>
                    </Card>
                </template>
                <WorkbookWrapper
                    v-if="workbookValues"
                    :workbook-skeleton="workbook.parsed_workbook"
                    :workbook-values="workbookValues"
                />
            </Deferred>
        </div>
        <div v-if="!workbook.up_to_date" class="flex flex-row-reverse my-1">
            <BaseBadge
                icon="exclamation-triangle"
                variant="warning"
                v-tooltip.left="'New Workbook Version Available'"
                @click="updateModal?.show()"
            />
            <Modal title="Update Workbook" ref="update-workbook-modal">
                <h3 class="text-center">
                    There is a newer version of the workbook available.
                </h3>
                <p class="text-center text-danger">
                    <strong>Warning:</strong> It is possible that some sections
                    have been removed from the workbook.
                </p>
                <p class="text-center text-danger">
                    If sections have been removed, their data will no longer be
                    accessable.
                </p>
                <div class="flex justify-center gap-2">
                    <BaseButton
                        text="Update Workbook"
                        variant="warning"
                        @click="updateWorkbook"
                    />
                    <BaseButton
                        text="Cancel Update"
                        @click="updateModal?.hide()"
                    />
                </div>
            </Modal>
        </div>
    </div>
</template>
