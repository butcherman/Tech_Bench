<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserAdministrationProfileForm from "@/features/user/forms/UserAdministrationProfileForm.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    roles: UserRole[];
}>();
</script>

<template>
    <div class="flex justify-center">
        <Card
            title="Create New User"
            sub-title="Enter the information for the new user.  A welcome email will be sent on completion."
            title-icon="user-plus"
        >
            <UserAdministrationProfileForm :role-list="roles" />
        </Card>
    </div>
</template>
