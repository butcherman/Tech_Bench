<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

defineProps<{
    roles: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Select A Role">
            <template #append-title>
                <AddButton
                    :href="$route('admin.user-roles.create')"
                    size="small"
                    text="Create Role"
                    pill
                />
            </template>
            <ResourceList
                label-field="name"
                :list="roles"
                :link-fn="
                    (item) => $route('admin.user-roles.show', item.role_id)
                "
                center
            />
        </Card>
    </div>
</template>
