<template>
    <Head title="Change Customer ID" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Change Customer ID</div>
                        <CustomerBasicSearch @selected="selectCust" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import CustomerBasicSearch from "@/Components/Customer/CustomerBasicSearch.vue";
import { router } from "@inertiajs/vue3";

const selectCust = (cust: customer) => {
    router.visit(route("admin.cust.change_id.show", cust.slug));
};
</script>

<script lang="ts">
export default { layout: App };
</script>
