<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserAdministrationProfileForm from "@/features/user/forms/UserAdministrationProfileForm.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    roles: UserRole[];
    user: User;
}>();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">Update User Information</h1>
            <p class="text-muted">
                Update the personal information for this user.
            </p>
        </div>
        <div class="flex justify-center">
            <Card class="w-full lg:w-3/4">
                <UserAdministrationProfileForm
                    :role-list="roles"
                    :user="user"
                />
            </Card>
        </div>
    </div>
</template>
