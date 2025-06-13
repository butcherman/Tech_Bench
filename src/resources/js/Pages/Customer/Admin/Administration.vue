<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSettingsForm from "@/Forms/Customer/CustomerSettingsForm.vue";

const props = defineProps<{
    select_id: boolean;
    update_slug: boolean;
    default_state: string;
    auto_purge: boolean;
    allow_vpn_data: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <CustomerSettingsForm v-bind="props" />
        </Card>
    </div>
</template>
