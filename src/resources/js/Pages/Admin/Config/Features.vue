<template>
    <Card class="tb-card" title="Enable/Disable Features">
        <FeatureForm :feature-list="featureList" />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import FeatureForm from "@/Forms/Admin/Config/FeatureForm.vue";

defineProps<{
    featureList: {
        [key: string]: boolean;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
