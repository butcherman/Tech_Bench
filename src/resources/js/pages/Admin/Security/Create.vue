<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import SslCertForm from "@/features/administration/forms/SslCertForm.vue";

defineProps<{
    hasKey: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="md">
            <SslCertForm :hasKey />
        </Card>
    </div>
</template>
