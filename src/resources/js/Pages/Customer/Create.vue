<template>
    <div class="row justify-content-center">
        <Head title="Create Customer" />
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Create Customer</div>
                    <CustomerDetailsForm
                        :select-id="selectId"
                        :default-state="defaultState"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetailsForm from "@/Forms/Customer/CustomerDetailsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    selectId: boolean;
    defaultState: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
