<script setup lang="ts">
import TextInput from "../_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    equipment: customerEquipment;
    publish_until: string | Date | null;
}>();

// How many days the published workbook is good for by default
const expireDaysToAdd = 90;

// Helper function to add leading 0 to month and day
const addLeadingZero = (n: number): string | number => (n < 10 ? `0${n}` : n);

/**
 * Set the default expiration date for the workbook
 */
const getExpireDate = () => {
    let expire = new Date();
    expire.setDate(expire.getDate() + expireDaysToAdd);

    let date = {
        month: addLeadingZero(expire.getMonth()),
        day: addLeadingZero(expire.getDay()),
        year: expire.getFullYear(),
    };

    return `${date.year}-${date.month}-${date.day}`;
};

const initValues = {
    publish_until: props.publish_until ?? getExpireDate(),
};
const schema = object({
    publish_until: string().required("Expiration Date"),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        submit-method="post"
        submit-text="Publish Workbook"
        :submit-route="
            $route('customers.equipment.workbook.publish', [
                customer.slug,
                equipment.cust_equip_id,
            ])
        "
        :validation-schema="schema"
        @success="$emit('success')"
    >
        <p class="text-center">Workbook Will Be Published Until</p>
        <TextInput id="publish-until" name="publish_until" type="date" />
    </VueForm>
</template>
