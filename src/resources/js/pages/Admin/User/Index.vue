<script setup lang="ts">
import AddButton from "@/core/components/buttons/AddButton.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import ComponentLoader from "@/core/components/loaders/ComponentLoader.vue";
import UserAdministration from "@/features/user/components/UserAdministration.vue";
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
        <Card title="User Admistration" size="large">
            <template #append-title>
                <AddButton
                    text="New User"
                    size="small"
                    :href="create.url()"
                    pill
                />
            </template>
            <ComponentLoader data="user-list" loading-text="Loading Users">
                <UserAdministration v-if="userList" :user-list="userList" />
            </ComponentLoader>
        </Card>
    </div>
</template>
