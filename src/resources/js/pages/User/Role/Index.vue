<script setup lang="ts">
import AddBadge from "@/core/components/badges/AddBadge.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import ResourceList from "@/core/features/dataResources/ResourceList.vue";
import { show, create } from "@/wayfinder/routes/admin/user-roles";
import { useLinkHelper } from "@/core/composables/linkHelper";

defineOptions({ layout: AppLayout });

defineProps<{
    roles: UserRole[];
}>();

const onRowClick = (event: MouseEvent, listItem: UserRole) => {
    let link = {
        href: show.url(listItem.role_id),
        external: false,
    };

    useLinkHelper(event, link);
};
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">User Roles and Permissions</h1>
            <p class="text-muted">Select a User Role for create a new one.</p>
        </div>
        <Card>
            <template #append-title>
                <AddBadge size="sm" text="Create Role" :href="create.url()" />
            </template>
            <ResourceList
                :list="roles"
                :row-link-fn="onRowClick"
                allow-row-click
                center
                hover-row
                label-field="name"
                striped
            />
        </Card>
    </div>
</template>
