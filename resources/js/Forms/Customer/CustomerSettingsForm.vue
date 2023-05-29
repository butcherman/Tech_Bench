<template>
    <VueForm
        ref="customerSettingsForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        @submit="onSubmit"
    >
        <CheckboxSwitch id="select-id" name="selectId">
            <template #label>
                Allow users to manually input customer ID
                <span
                    class="text-info"
                    title="What is this?"
                    data-bs-content="The Customer ID is the index field for the Customer
                                                         database.  When allowed, a user creating a new customer
                                                         can manually input a custom Customer ID.  When
                                                         disabled, the Customer ID is automatically
                                                         populated in the database."
                    v-popover
                    @click.prevent
                >
                    <fa-icon icon="fa-circle-question" />
                </span>
            </template>
        </CheckboxSwitch>
        <CheckboxSwitch id="update-slug" name="updateSlug">
            <template #label>
                Automatically update slug when Customer Name is changed
                <span
                    class="text-info"
                    title="What is this?"
                    data-bs-content="The Slug is the URL reference for a customer.
                                                         When disabled, updating a customer name will not
                                                         change the URL that is used to access this Customer.
                                                         Disabling this field is helpful for users who like to
                                                         bookmark Customers using their Browsers Bookmarks
                                                         rather than the Tech Bench's built in bookmarks."
                    v-popover
                    @click.prevent
                >
                    <fa-icon icon="fa-circle-question" />
                </span>
            </template>
        </CheckboxSwitch>
        <SelectInput
            id="default-state"
            name="defaultState"
            label="Default State for new customers"
            :option-list="allStates"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, bool, string } from "yup";
import { allStates } from "@/Modules/allStates.module";

type customerSettings = {
    selectId: boolean;
    updateSlug: boolean;
    defaultState: string;
}

const props = defineProps<{
    currentSettings: customerSettings;
}>();

const customerSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);

const validationSchema = object({
    selectId: bool(),
    updateSlug: bool(),
    defaultState: string().required().label("Default State"),
});
const initialValues = <customerSettings>{
    selectId: props.currentSettings.selectId,
    updateSlug: props.currentSettings.updateSlug,
    defaultState: props.currentSettings.defaultState,
};

const onSubmit = (form: customerSettings) => {
    const formData = useForm(form);
    console.log(formData);
    formData.post(route("admin.cust.set-settings"), {
        onFinish: () => customerSettingsForm.value?.endSubmit(),
    });
};
</script>
