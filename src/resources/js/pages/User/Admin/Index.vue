<script setup lang="ts">
import AddBadge from "@/core/components/badges/AddBadge.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import DeferredLoader from "@/core/components/loaders/DeferredLoader.vue";
import UserAdministrationList from "@/features/user/components/UserAdministrationList.vue";
import { create } from "@/wayfinder/routes/admin/user";

defineOptions({ layout: AppLayout });

defineProps<{
    userList?: User[];
}>();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">User Administration</h1>
            <p class="text-muted">
                Select a User to edit, or create a new user.
            </p>
        </div>
        <div class="w-full min-w-0">
            <Card class="w-full min-w-0">
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
                    <UserAdministrationList
                        v-if="userList"
                        :user-list="userList"
                    />
                </DeferredLoader>
            </Card>
        </div>
    </div>
</template>
