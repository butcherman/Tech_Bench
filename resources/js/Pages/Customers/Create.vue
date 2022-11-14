<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <VueForm
                            ref="newCustomerForm"
                            :validation-schema="customerValidation"
                            @submit="onSubmit"
                        >
                            <div class="row p-0">
                                <div class="col">
                                    <TextInput id="cust-id" name="custId" label="Customer ID" />
                                </div>
                                <div class="col">
                                    <TextInput id="parent-id" name="parentId" label="Parent Site" />
                                </div>
                            </div>
                            <TextInput id="name" name="name" label="Customer Name" />
                            <TextInput id="dba-name" name="dbaName" label="Secondary Name (AKA Name)" />
                            <TextInput id="address" name="address" label="Customer Address" />
                            <TextInput id="city" name="city" label="City" />
                            <div class="row p-0">
                                <div class="col">
                                    <SelectInput id="state" name="state" :option-list="allStates" />
                                </div>
                                <div class="col">
                                    <TextInput id="zip-code" name="zip" label="Zip Code" type="number" />
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
    import App from '@/Layouts/app.vue';
    import VueForm from '@/Components/Base/VueForm.vue';
    import TextInput from '@/Components/Base/Input/TextInput.vue';
    import SelectInput from '@/Components/Base/Input/SelectInput.vue';
    import { ref, reactive, onMounted } from 'vue';
    import { allStates } from '@/Modules/allStates.module';
    import { customerValidation } from '@/Modules/Validation/customerValidation.module';


    const props = defineProps<{}>();
    const newCustomerForm = ref<InstanceType<typeof VueForm> | null>(null);

    const onSubmit = (form) => {
        console.log(form);

        newCustomerForm.value?.endSubmit();
    }
</script>

<script lang="ts">
    export default { layout: App, components: { SelectInput } }
</script>
