<template>
    <App>
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
                                    class="pointer"
                                    @click="gotoRole(role)"
                                >
                                    <td>{{ role.name }}</td>
                                    <td>{{ role.description }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="route('admin.users.roles.create')"
                                type="button"
                                class="btn btn-primary w-50"
                            >
                                Create New Role
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App                   from '@/Layouts/app.vue';
    import { Inertia }           from '@inertiajs/inertia';
    import type { userRoleType } from '@/Types';

    defineProps<{
        roles: userRoleType[];
    }>();

    const gotoRole = (role:userRoleType) => {
        Inertia.get(route('admin.users.roles.edit', role.name));
    }
</script>
