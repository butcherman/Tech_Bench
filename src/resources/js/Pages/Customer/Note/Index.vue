<template>
    <div>
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle my-2">
            <CustomerDetails />
        </div>
        <div class="row justify-content-center my-2">
            <div class="col">
                <CustomerNote />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerNote from "@/Components/Customer/CustomerNote.vue";
import { ref, reactive, onMounted } from "vue";
import { customer, siteList, notes } from "@/State/CustomerState";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    // note: customerNote;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
