<template>
    <VueForm
        ref="customerAlertForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="resetForm"
    >
        <TextInput id="message" name="message" label="Alert Message" focus />
        <SelectInput
            id="type"
            name="type"
            label="Alert Type"
            :list="alertTypeList"
            text-field="name"
            value-field="value"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import { ref, computed } from "vue";
import { object, string } from "yup";

const emit = defineEmits(["success"]);
const props = defineProps<{
    customer: customer;
    alert: customerAlert | null;
}>();

const customerAlertForm = ref<InstanceType<typeof VueForm> | null>(null);

const submitText = computed(() =>
    props.alert ? "Update Alert" : "Create Alert"
);
const submitMethod = computed(() => (props.alert ? "put" : "post"));
const submitRoute = computed(() =>
    props.alert
        ? route("customers.alerts.update", [
              props.customer.slug,
              props.alert.alert_id,
          ])
        : route("customers.alerts.store", props.customer.slug)
);

const initValues = {
    message: props.alert?.message || "",
    type: props.alert?.type || "danger",
};
const schema = object({
    message: string()
        .required()
        .max(75, "Please keep the alert short and sweet"),
    type: string().required(),
});

const resetForm = () => {
    emit("success");
    customerAlertForm.value?.resetForm();
};

const alertTypeList = ref([
    {
        name: "General Note (green background)",
        value: "success",
    },
    {
        name: "Warning (yellow background)",
        value: "warning",
    },
    {
        name: "Alert (red background)",
        value: "danger",
    },
]);
</script>
