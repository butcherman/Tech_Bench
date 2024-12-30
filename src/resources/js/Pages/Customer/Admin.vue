<template>
    <Card class="tb-card" title="Customer Settings">
        <CustomerSettingsForm v-bind="props" />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSettingsForm from "@/Forms/Customer/CustomerSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    select_id: boolean;
    update_slug: boolean;
    default_state: string;
    auto_purge: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
