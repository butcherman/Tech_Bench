<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import AddButton from "@/core/components/buttons/AddButton.vue";
import { create } from "@/wayfinder/routes/admin/user-roles";
import ResourceList from "@/features/dataTable/ResourceList.vue";

defineProps<{
    roles: UserRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card title="Select A Role">
            <template #append-title>
                <AddButton
                    size="small"
                    text="Create Role"
                    :href="create.url()"
                    pill
                />
            </template>
            <ResourceList :list="roles" />
        </Card>
    </div>
</template>
