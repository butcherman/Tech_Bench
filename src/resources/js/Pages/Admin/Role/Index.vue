<template>
    <Card title="Select A Role" class="tb-card">
        <template #append-title>
            <AddButton
                :href="$route('admin.user-roles.create')"
                size="small"
                text="Create Role"
                pill
            />
        </template>
        <ResourceList
            :list="roles"
            label-field="description"
            :link-fn="(item) => $route('admin.user-roles.show', item.role_id)"
        />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

defineProps<{
    roles: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
