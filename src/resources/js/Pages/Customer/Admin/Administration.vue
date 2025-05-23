<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSettingsForm from "@/Forms/Customer/CustomerSettingsForm.vue";

defineProps<{
    select_id: boolean;
    update_slug: boolean;
    default_state: string;
    auto_purge: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <CustomerSettingsForm
                :select_id="select_id"
                :update_slug="update_slug"
                :default_state="default_state"
                :auto_purge="auto_purge"
            />
        </Card>
    </div>
</template>
