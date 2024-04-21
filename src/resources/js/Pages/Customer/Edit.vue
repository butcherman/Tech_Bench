<template>
    <div>
        <Head title="Edit Customer" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <CustomerDetailsForm
                            :select-id="selectId"
                            :default-state="defaultState"
                            :customer="customer"
                            :site-list="siteList"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetailsForm from "@/Forms/Customer/CustomerDetailsForm.vue";
import { ref, reactive, onMounted } from "vue";
import { customer, siteList } from "@/State/CustomerState";

const props = defineProps<{
    selectId: boolean;
    defaultState: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
