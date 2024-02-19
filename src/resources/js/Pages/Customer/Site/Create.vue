<template>
    <div class="row justify-content-center">
        <Head title="New Customer Site" />
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <CustomerSiteForm
                        :default-state="defaultState"
                        :parent-customer="parentCustomer"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerSiteForm from "@/Forms/Customer/CustomerSiteForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    defaultState: string;
    parentCustomer: customer | null;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
