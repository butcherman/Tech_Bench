<script setup lang="ts">
import AddBadge from "@/core/components/badges/AddBadge.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import ResourceList from "@/core/components/ResourceList.vue";
import { show, create } from "@/wayfinder/routes/admin/user-roles";
import { useLinkHelper } from "@/core/composables/linkHelper";

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

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="Select A Role" size="md">
            <template #append-title>
                <AddBadge
                    text="Create Role"
                    size="sm"
                    :href="create.url()"
                    pill
                />
            </template>
            <ResourceList
                :list="roles"
                :row-click-fn="onRowClick"
                text-field="name"
                text-position="center"
                has-border
                hover-row
            />
        </Card>
    </div>
</template>
