<script setup lang="ts">
import AddButton from "@/core/components/buttons/AddButton.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import ComponentLoader from "@/core/components/loaders/ComponentLoader.vue";
import UserAdministration from "@/features/administration/user/components/UserAdministration.vue";

defineProps<{
    userList?: User[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="User Admistration">
            <template #append-title>
                <AddButton text="New User" size="small" pill />
            </template>
            <ComponentLoader data="user-list" loading-text="Loading Users">
                <UserAdministration v-if="userList" :user-list="userList" />
            </ComponentLoader>
        </Card>
    </div>
</template>
