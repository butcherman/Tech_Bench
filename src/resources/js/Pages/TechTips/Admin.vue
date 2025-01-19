<template>
    <Card class="tb-card" title="Tech Tip Settings">
        <TechTipSettingsForm :settings="settings" />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import TechTipSettingsForm from "@/Forms/TechTips/TechTipSettingsForm.vue";

defineProps<{
    settings: {
        allow_comments: boolean;
        allow_public: boolean;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
