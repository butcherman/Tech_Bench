<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import PasswordPolicyForm from "@/Forms/Admin/User/PasswordPolicyForm.vue";

defineProps<{
    policy: passwordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <PasswordPolicyForm :policy="policy" />
        </Card>
    </div>
</template>
