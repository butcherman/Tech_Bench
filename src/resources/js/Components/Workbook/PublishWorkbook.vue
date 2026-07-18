<script setup lang="ts">
import BaseButton from "../_Base/Buttons/BaseButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import Modal from "../_Base/Modal.vue";
import PublishWorkbookForm from "@/Forms/Customer/PublishWorkbookForm.vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { useTemplateRef } from "vue";

const props = defineProps<{
    equipment: customerEquipment;
    workbook: customerWorkbook;
}>();

const publishModal = useTemplateRef("publish-workbook-modal");
</script>

<template>
    <div>
        <BaseButton
            v-if="!props.workbook.published"
            text="Publish Workbook"
            @click="publishModal?.show()"
        />
        <div v-else class="text-center">
            <p>Workbook Published Until</p>
            <p
                class="underline text-blue-600 hover:font-bold pointer"
                @click="publishModal?.show()"
            >
                {{ props.workbook.publish_until }}
            </p>
        </div>
        <Modal ref="publish-workbook-modal">
            <PublishWorkbookForm
                :equipment="equipment"
                :publish_until="props.workbook.publish_until_raw"
                @success="publishModal?.hide()"
            />
            <div v-if="workbook.published" class="text-center">
                <DeleteButton
                    class="my-3 w-65"
                    text="Unpublish Workbook"
                    :href="
                        $route('customers.equipment.workbook.unpublish', [
                            customer.slug,
                            props.equipment.cust_equip_id,
                        ])
                    "
                    v-tooltip="'Remove Public Access for this Workbook'"
                />
            </div>
        </Modal>
    </div>
</template>
