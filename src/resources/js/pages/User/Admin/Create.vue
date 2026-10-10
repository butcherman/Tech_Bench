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
        <Card size="md">
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="fa-user-plus" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Create New User</h3>
                        <p class="text-muted">
                            Enter the information for the new user. A welcome
                            email will be sent on completion.
                        </p>
                    </div>
                </div>
            </template>
            <UserAdministrationProfileForm :role-list="roles" />
        </Card>
    </div>
</template>
