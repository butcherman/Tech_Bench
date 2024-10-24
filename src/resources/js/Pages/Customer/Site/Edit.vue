<template>
    <div class="row justify-content-center">
        <Head title="Edit Customer Site" />
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <CustomerSiteForm
                        :default-state="defaultState"
                        :parent-customer="parentCustomer"
                        :customer-site="site"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerSiteForm from "@/Forms/Customer/CustomerSiteForm.vue";

defineProps<{
    defaultState: string;
    parentCustomer: customer | null;
    site: customerSite;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
