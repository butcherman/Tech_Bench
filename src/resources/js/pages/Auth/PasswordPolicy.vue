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
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="fa-user-lock" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Passoword Policy</h3>
                        <p class="text-muted">
                            Modify the password policy to fit your security
                            needs.
                        </p>
                    </div>
                </div>
            </template>
            <PasswordPolicyForm :policy="policy" />
        </Card>
    </div>
</template>
