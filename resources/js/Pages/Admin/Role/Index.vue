<template>
    <Head title="Roles and Permissions" />
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Select A Role</div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="role in roles"
                                :key="role.role_id"
                                class="row-link"
                            >
                                <td>
                                    <Link :href="$route('admin.user-roles.show', role.role_id)">
                                        {{ role.name }}
                                    </Link>
                                </td>
                                <td>
                                    <Link :href="$route('admin.user-roles.show', role.role_id)">
                                        {{ role.description }}
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <Link
                            as="button"
                            :href="$route('admin.user-roles.create')"
                            type="button"
                            class="btn btn-info w-50"
                        >
                            <fa-icon icon="add" />
                            Create New Role
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps<{
    roles: userRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
