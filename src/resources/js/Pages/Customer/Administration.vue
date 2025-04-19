<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSettingsForm from "@/Forms/Customer/CustomerSettingsForm.vue";

defineProps<{
    selectId: boolean;
    updateSlug: boolean;
    defaultState: string;
    autoPurge: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <CustomerSettingsForm
                :select-id="selectId"
                :update-slug="updateSlug"
                :default-state="defaultState"
                :auto-purge="autoPurge"
            />
        </Card>
    </div>
</template>
