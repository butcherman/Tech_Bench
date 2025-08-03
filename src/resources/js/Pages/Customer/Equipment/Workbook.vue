<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import Modal from "@/Components/_Base/Modal.vue";
import PublishWorkbookForm from "@/Forms/Customer/PublishWorkbookForm.vue";
import ToggleSwitch from "@/Components/_Base/ToggleSwitch.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { computed, ref, useTemplateRef } from "vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    equipment: customerEquipment;
    values?: { [index: string]: string };
    workbook: customerWorkbook;
}>();

// TODO - Register to Broadcast Channel and monitor changes in value

const wbData = computed(() => JSON.parse(props.workbook.wb_data));
const wbValues = ref({ ...props.values });
const isPublished = ref(props.workbook.published);
const modal = useTemplateRef("publish-modal");

// Default expiration date for publish wb is 90 days from today
let date = new Date();
date.setDate(date.getDate() + 90);

/**
 * Enable or Disable the public workbook link
 */
const publishWorkbook = () => {
    if (isPublished.value) {
        modal.value?.show();
    } else {
        let formData = useForm({ expires: null });
        formData.post(
            route("customers.equipment.workbook.store", [
                customer.value.slug,
                props.equipment.cust_equip_id,
            ])
        );
    }
};
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="mb-2 flex">
            <div>
                <BaseButton
                    icon="arrow-left"
                    text="Back to Tech Bench"
                    :href="
                        $route('customers.equipment.show', [
                            customer.slug,
                            equipment.cust_equip_id,
                        ])
                    "
                />
            </div>
            <div class="text-center grow">
                <p>Public Workbook Link</p>
                <a
                    :href="$route('customer-workbook.show', workbook.wb_hash)"
                    target="_blank"
                    class="text-blue-600"
                >
                    {{ $route("customer-workbook.show", workbook.wb_hash) }}
                </a>
                <ClipboardCopy
                    class="ms-1"
                    :value="$route('customer-workbook.show', workbook.wb_hash)"
                />
            </div>
            <div>
                <ToggleSwitch
                    v-model="isPublished"
                    id="publish"
                    name="publish"
                    label="Publish Workbook"
                    reverse
                    @change="publishWorkbook()"
                />
                <p
                    v-if="workbook.published && workbook.publish_until"
                    class="text-end text-sm text-muted"
                >
                    Available until {{ workbook.publish_until }}
                </p>
            </div>
        </div>
        <WorkbookBase
            class="grow"
            :workbook-data="wbData"
            :active-page="wbData.body[0].page"
            :values="wbValues"
            :workbook-hash="workbook.wb_hash"
        />
        <Modal ref="publish-modal" title="Publish Workbook">
            <p class="text-center">
                Workbook will be available through the public link until the
                date below.
            </p>
            <PublishWorkbookForm
                :equipment="equipment"
                :customer="customer"
                :expires="workbook.publish_until ?? date"
                @success="modal?.hide()"
            />
        </Modal>
    </div>
</template>
