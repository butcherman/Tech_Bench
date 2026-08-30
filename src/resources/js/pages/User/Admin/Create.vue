<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserAdministrationProfileForm from "@/features/user/forms/UserAdministrationProfileForm.vue";

defineProps<{
    roles: UserRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="Enter New User Information" size="md">
            <UserAdministrationProfileForm :role-list="roles" />
        </Card>
    </div>
</template>
