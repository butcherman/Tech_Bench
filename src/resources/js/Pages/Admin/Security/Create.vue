<template>
    <div>
        <Card class="tb-card" title="Upload Certificate">
            <CertificateForm :has-key="hasKey" />
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CertificateForm from "@/Forms/Admin/Config/CertificateForm.vue";

defineProps<{
    hasKey: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
