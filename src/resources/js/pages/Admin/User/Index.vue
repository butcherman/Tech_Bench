<script setup lang="ts">
import AddButton from "@/core/components/buttons/AddButton.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import AtomLoader from "@/core/components/loaders/AtomLoader.vue";
import Card from "@/core/components/Card.vue";
import { Deferred } from "@inertiajs/vue3";

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
            <Deferred data="user-list">
                <template #fallback>
                    <div class="flex justify-center">
                        <AtomLoader text="Loading Users" />
                    </div>
                </template>
            </Deferred>
            user list
        </Card>
    </div>
</template>
