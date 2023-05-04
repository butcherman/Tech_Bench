<template>
    <VueForm
        ref="editCustomerForm"
        submit-text="Update Customer"
        :validation-schema="customerValidation"
        :initial-values="initialValues"
        @submit="onSubmit"
    >
        <TextInput id="name" name="name" label="Customer Name" />
        <TextInput
            id="dba-name"
            name="dba_name"
            label="Secondary Name (AKA Name)"
        />
        <TextInput id="address" name="address" label="Customer Address" />
        <TextInput id="city" name="city" label="City" />
        <div class="row p-0">
            <div class="col">
                <SelectInput
                    id="state"
                    name="state"
                    label="State"
                    :option-list="allStates"
                />
            </div>
            <div class="col">
                <TextInput
                    id="zip-code"
                    name="zip"
                    label="Zip Code"
                    type="number"
                />
            </div>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import { ref, inject } from "vue";
import { useForm } from "@inertiajs/vue3";
import { allStates } from "@/Modules/allStates.module";
import { customerValidation } from "@/Modules/Validation/customerValidation.module";
import { customerKey } from "@/SymbolKeys/CustomerKeys";
import type { Ref } from "vue";
import type { customerFormType, customerType } from "@/Types";

const emit = defineEmits(["success"]);
const editCustomerForm = ref<InstanceType<typeof VueForm> | null>(null);
const customer = inject(customerKey) as Ref<customerType>;
const initialValues = <customerType>{
    name: customer?.value?.name,
    dba_name: customer?.value?.dba_name,
    address: customer?.value?.address,
    city: customer?.value?.city,
    state: customer?.value?.state,
    zip: customer?.value?.zip,
};

const onSubmit = (form: customerFormType) => {
    const formData = useForm(form);
    formData.put(route("customers.update", customer?.value?.slug), {
        only: ["customer", "flash"],
        onFinish: () => {
            editCustomerForm.value?.endSubmit();
            emit("success");
        },
    });
};
</script>
