<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserAdministrationProfileForm from "@/features/user/forms/UserAdministrationProfileForm.vue";

defineProps<{
    roles: UserRole[];
    user: User;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="Update User Information" size="md">
            <UserAdministrationProfileForm :role-list="roles" :user="user" />
        </Card>
    </div>
</template>
