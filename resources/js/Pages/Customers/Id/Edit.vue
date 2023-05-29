<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{ customer.name }}</div>
                        <h6 class="text-center">
                            Current ID - {{ customer.cust_id }}
                        </h6>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <VueForm
                                    ref="customerIdForm"
                                    :validation-schema="validationSchema"
                                    @submit="onSubmit"
                                >
                                    <TextInput
                                        id="new-id"
                                        name="cust_id"
                                        label="New Customer ID"
                                        type="number"
                                    />
                                </VueForm>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import App from "@/Layouts/app.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import * as yup from "yup";

const props = defineProps<{
    customer: customer;
}>();

const customerIdForm = ref<InstanceType<typeof VueForm> | null>(null);
const validationSchema = {
    cust_id: yup.number().required().label("New Customer ID"),
};

const onSubmit = (form: customer) => {
    const formData = useForm(form);
    formData.put(route("admin.cust.change_id.update", props.customer.slug), {
        onFinish: () => customerIdForm.value?.endSubmit(),
    });
};
</script>

<script lang="ts">
export default { layout: App };
</script>
