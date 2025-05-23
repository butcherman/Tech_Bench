<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import TechTipSettingsForm from "@/Forms/TechTip/TechTipSettingsForm.vue";

const props = defineProps<{
    allow_comments: boolean;
    allow_download: boolean;
    allow_public: boolean;
    public_link_text: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <TechTipSettingsForm v-bind="props" />
        </Card>
    </div>
</template>
