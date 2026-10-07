<script setup lang="ts">
import AddBadge from "@/core/components/badges/AddBadge.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import DeferredLoader from "@/core/components/loaders/DeferredLoader.vue";
import UserAdministrationList from "@/features/user/components/UserAdministrationList.vue";
import { create } from "@/wayfinder/routes/admin/user";

defineProps<{
    userList?: User[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="User Administration">
            <template #append-title>
                <AddBadge text="New User" size="sm" :href="create.url()" pill />
            </template>
            <DeferredLoader data="user-list">
                <UserAdministrationList v-if="userList" :user-list="userList" />
            </DeferredLoader>
        </Card>
    </div>
</template>
