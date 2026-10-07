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
