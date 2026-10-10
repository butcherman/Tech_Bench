<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import DeferredLoader from "@/core/components/loaders/DeferredLoader.vue";
import UserAdministrationList from "@/features/user/components/UserAdministrationList.vue";

defineProps<{
    userList?: User[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="Disabled Users">
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="fa-user-slash" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Disabled Users</h3>
                        <p class="text-muted">
                            All disabled users are listed here. Select one to
                            view more information.
                        </p>
                    </div>
                </div>
            </template>
            <DeferredLoader data="user-list">
                <UserAdministrationList
                    v-if="userList"
                    :user-list="userList"
                    disabled-list
                />
            </DeferredLoader>
        </Card>
    </div>
</template>
