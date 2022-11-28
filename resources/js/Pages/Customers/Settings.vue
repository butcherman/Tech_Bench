<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer Default Settings</div>
                        <VueForm
                            ref="customerSettingsForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
                            @submit="onSubmit"
                        >
                            <CheckboxSwitch
                                id="select-id"
                                name="selectId"
                            >
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
                            <CheckboxSwitch
                                id="update-slug"
                                name="updateSlug"
                            >
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import VueForm from '@/Components/Base/VueForm.vue';
    import SelectInput from '@/Components/Base/Input/SelectInput.vue';
    import CheckboxSwitch from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref, reactive, onMounted } from 'vue';
    import { allStates }            from '@/Modules/allStates.module';
    import * as yup from 'yup';
import { useForm } from '@inertiajs/inertia-vue3';

    const props = defineProps<{
        selectId    : boolean;
        updateSlug  : boolean;
        defaultState: string;
    }>();
    const customerSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);

    const validationSchema = {
        selectId    : yup.bool(),
        updateSlug  : yup.bool(),
        defaultState: yup.string().required().label('Default State'),
    }
    const initialValues    = {
        selectId    : props.selectId,
        updateSlug  : props.updateSlug,
        defaultState: props.defaultState,
    }

    const onSubmit = (form) => {
        const formData = useForm(form);
        formData.post(route('admin.cust.set-settings'), {
            onFinish: () => customerSettingsForm.value?.endSubmit(),
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
