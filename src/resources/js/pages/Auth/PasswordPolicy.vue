<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import PasswordPolicyForm from "@/features/auth/forms/PasswordPolicyForm.vue";

defineProps<{
    policy: PasswordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="md">
            <PasswordPolicyForm :policy="policy" />
        </Card>
    </div>
</template>
