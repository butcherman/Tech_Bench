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
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">Password Policy</h1>
            <p class="text-muted">
                Modify the password policy to fit your security needs.
            </p>
        </div>
        <div class="flex justify-center">
            <Card class="lg:w-3/4 w-full">
                <PasswordPolicyForm :policy="policy" />
            </Card>
        </div>
    </div>
</template>
