<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import PasswordPolicyForm from "@/features/user/forms/PasswordPolicyForm.vue";
import AppLayout from "@/layouts/AppLayout.vue";

const props = defineProps<{
    policy: PasswordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="Password Policy">
            <PasswordPolicyForm :policy="policy" />
        </Card>
    </div>
</template>
