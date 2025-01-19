<template>
    <Card class="tb-card">
        <PasswordPolicyForm class="md:w-3/4 mx-auto" :policy="policy" />
    </Card>
</template>

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
