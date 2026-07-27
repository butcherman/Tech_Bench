<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserDetailsForm from "@/features/user/forms/UserDetailsForm.vue";

defineProps<{
    roles: UserRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="New User">
            <UserDetailsForm :roles="roles" />
        </Card>
    </div>
</template>
