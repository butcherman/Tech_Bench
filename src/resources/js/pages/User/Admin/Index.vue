<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import AddBadge from "@/core/components/badges/AddBadge.vue";
import DeferredLoader from "@/core/components/loaders/DeferredLoader.vue";
import UserAdministrationList from "@/features/user/components/UserAdministrationList.vue";
import { create } from "@/wayfinder/routes/admin/user";

defineOptions({ layout: AppLayout });

defineProps<{
    userList?: User[];
}>();
</script>

<template>
    <div class="w-full min-w-0">
        <Card class="w-full min-w-0">
            <template #title>
                <div class="flex gap-2 items-center">
                    <h1><fa-icon icon="fa-user" /></h1>
                    <div>
                        <h3 class="text-black">User Administration</h3>
                        <p class="text-muted">
                            Select a User to edit, or create a new user.
                        </p>
                    </div>
                </div>
            </template>
            <template #append-title>
                <div class="flex items-center h-full">
                    <AddBadge
                        :href="create.url()"
                        text="New User"
                        size="sm"
                        pill
                    />
                </div>
            </template>
            <DeferredLoader data="user-list">
                <UserAdministrationList v-if="userList" :user-list="userList" />
            </DeferredLoader>
        </Card>
    </div>
</template>
