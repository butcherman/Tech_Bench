<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import ResourceList from "@/core/components/ResourceList.vue";
import { create, show } from "@/wayfinder/routes/admin/user-roles";
import AddButton from "@/core/components/buttons/AddButton.vue";

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
                    :href="create.url()"
                    size="small"
                    text="Create Role"
                    pill
                />
            </template>
            <ResourceList :data="roles" />
        </Card>
    </div>
</template>
