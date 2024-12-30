<template>
    <Card title="Select A Role" class="reasonable-width justify-self-center">
        <template #append-title>
            <AddButton
                :href="$route('admin.user-roles.create')"
                size="small"
                text="Create Role"
                pill
            />
        </template>
        <v-list>
            <template v-for="role in roles">
                <Link
                    :href="$route('admin.user-roles.show', role.role_id)"
                    class="w-full"
                >
                    <v-list-item class="border my-2">
                        <span class="font-bold">
                            {{ role.name }}
                        </span>
                        <span class="float-end">{{ role.description }}</span>
                    </v-list-item>
                </Link>
            </template>
        </v-list>
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";

defineProps<{
    roles: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
