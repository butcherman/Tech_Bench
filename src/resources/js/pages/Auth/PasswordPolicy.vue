<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import PasswordPolicyForm from "@/features/auth/forms/PasswordPolicyForm.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    policy: PasswordPolicy;
}>();
</script>

<template>
    <div class="flex justify-center">
        <Card
            class="lg:w-3/4 w-full"
            title="Password Policy"
            sub-title="Modify the password policy to fit your security needs."
            title-icon="user-lock"
        >
            <PasswordPolicyForm :policy="policy" />
        </Card>
    </div>
</template>
