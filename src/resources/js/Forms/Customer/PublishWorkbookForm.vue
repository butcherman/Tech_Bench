<script setup lang="ts">
import DatePicker from "../_Base/DatePicker.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    edit?: string;
    equipment: customerEquipment;
    customer: customer;
    expires: Date | string;
}>();

const initValues = {
    expires: props.expires,
};
const schema = object({
    expires: string().required(),
});
</script>

<template>
    <VueForm
        submit-method="post"
        submit-text="Publish Workbook"
        :submit-route="
            $route('customers.equipment.workbook.store', [
                customer.slug,
                equipment.cust_equip_id,
            ])
        "
        :initial-values="initValues"
        :validation-schema="schema"
        @success="$emit('success')"
    >
        <DatePicker id="expires" name="expires" label="Workbook Expires On" />
    </VueForm>
</template>
