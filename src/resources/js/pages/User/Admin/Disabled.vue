<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import DeferredLoader from "@/core/components/loaders/DeferredLoader.vue";
import UserAdministrationList from "@/features/user/components/UserAdministrationList.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    userList?: User[];
}>();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">Disabled Users</h1>
            <p class="text-muted">
                All disabled users are listed here. Select one to view more
                information.
            </p>
        </div>
        <div class="w-full min-w-0">
            <Card>
                <DeferredLoader data="user-list">
                    <UserAdministrationList
                        v-if="userList"
                        :user-list="userList"
                        disabled-list
                    />
                </DeferredLoader>
            </Card>
        </div>
    </div>
</template>
