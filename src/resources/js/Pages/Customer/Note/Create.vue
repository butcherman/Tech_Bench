<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerNoteForm from "@/Forms/Customer/CustomerNoteForm.vue";
import {
    currentSite,
    customer,
    siteList,
} from "@/Composables/Customer/CustomerData.module";

defineProps<{
    activeEquipment?: customerEquipment;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 mb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
        </div>
        <Card>
            <CustomerNoteForm
                :customer="customer"
                :site-list="siteList ?? []"
                :current-site="currentSite"
                :equipment="activeEquipment"
            />
        </Card>
    </div>
</template>
