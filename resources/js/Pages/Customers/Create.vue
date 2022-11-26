<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <VueForm
                            ref="newCustomerForm"
                            :validation-schema="customerValidation"
                            :initial-values="{ state: defaultState, parent_id: null }"
                            @submit="onSubmit"
                        >
                            <div class="row p-0">
                                <div v-if="selectId" class="col">
                                    <TextInput
                                        id="cust-id"
                                        type="number"
                                        name="cust_id"
                                        label="Customer ID"
                                        @change="checkId"
                                    />
                                </div>
                                <div class="col">
                                    <div class="input-group flex-nowrap">
                                        <TextInput
                                            id="parent-name"
                                            name="parent_name"
                                            label="Parent Site"
                                            @change="openCustSearch"
                                        />
                                        <span
                                            class="input-group-text pointer mb-3"
                                            title="Search"
                                            v-tooltip
                                            @click="openCustSearch"
                                        >
                                            <fa-icon icon="fa-brands fa-searchengin" />
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <TextInput
                                id="name"
                                name="name"
                                label="Customer Name"
                            />
                            <TextInput
                                id="dba-name"
                                name="dba_name"
                                label="Secondary Name (AKA Name)"
                            />
                            <TextInput
                                id="address"
                                name="address"
                                label="Customer Address"
                            />
                            <TextInput
                                id="city"
                                name="city"
                                label="City"
                            />
                            <div class="row p-0">
                                <div class="col">
                                    <SelectInput
                                        id="state"
                                        name="state"
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                       from '@/Layouts/app.vue';
    import VueForm                   from '@/Components/Base/VueForm.vue';
    import TextInput                 from '@/Components/Base/Input/TextInput.vue';
    import SelectInput               from '@/Components/Base/Input/SelectInput.vue';
    import axios                     from 'axios';
    import { ref }                   from 'vue';
    import { useForm }               from '@inertiajs/inertia-vue3';
    import { allStates }             from '@/Modules/allStates.module';
    import { customerValidation }    from '@/Modules/Validation/customerValidation.module';
    import { customerSearchBox }     from '@/Modules/customerSearchBox.module';
    import type { customerFormType,
                  customerType }     from '@/Types';

    defineProps<{
        selectId    : boolean;
        defaultState: string;
    }>();
    const newCustomerForm = ref<InstanceType<typeof VueForm> | null>(null);

    /**
     * Make sure that the Customer ID field is not already taken.
     */
    const checkId = async (value:number) => {
        if(value) {
            await axios.get(route('customers.check-id', value)).then((res) => {
                if(!res.data.valid)
                {
                    newCustomerForm.value?.setFieldError('cust_id' as never, `This Customer ID is taken by ${res.data.name}`);
                }
            });
        }
    }

    /**
     * Make sure that the Parent Site is valid and set the parent_id field (hidden)
     */
    const openCustSearch = () => {
        customerSearchBox(newCustomerForm.value?.getFieldValue('parent_name')).then((res:customerType) => {
            console.log(res);
            newCustomerForm.value?.setFieldValue('parent_name' as never, res.name as never);
            newCustomerForm.value?.setFieldValue('parent_id' as never, res.cust_id as never);
        });
    }

    const onSubmit = (form:customerFormType) => {
        //  Re-check the customer ID to show who has the ID if it is taken
        checkId(form.cust_id);

        const formData = useForm(form);
        formData.post(route('customers.store'), {
            onFinish: () => newCustomerForm.value?.endSubmit(),
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
