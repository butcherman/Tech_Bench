<template>
    <Head title="User List" />
    <App>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <Overlay :loading="loading">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.user_id">
                                        <td>{{ user.username }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.full_name }}</td>
                                        <td>{{ user.user_roles.name }}</td>
                                        <td>
                                            <Link
                                                :href="route('admin.user.edit', user.username)"
                                                title="Edit User"
                                                v-tooltip
                                            >
                                                <fa-icon
                                                    class="mx-1 pointer text-primary"
                                                    icon="edit"
                                                />
                                            </Link>
                                            <Link
                                                :href="route('admin.reset-password.edit', user.username)"
                                                title="Reset Password"
                                                v-tooltip
                                            >
                                                <fa-icon
                                                    class="mx-1 pointer text-primary"
                                                    icon="key" title="Reset Password"
                                                />
                                            </Link>
                                            <span
                                                title="Disable User"
                                                v-tooltip
                                            >
                                                <fa-icon
                                                    class="mx-1 pointer text-danger"
                                                    icon="user-slash"
                                                    @click="disableUser(user)"
                                                />
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </Overlay>
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="route('admin.user.create')"
                                type="button"
                                class="btn btn-primary w-50"
                            >
                                Create New User
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App               from '@/Layouts/app.vue';
    import Overlay           from '@/Components/Base/Overlay.vue';
    import { ref }           from 'vue';
    import { verifyModal }   from '@/Modules/verifyModal.module';
    import { Inertia }       from '@inertiajs/inertia'
    import type { userType } from '@/Types';

    interface userWithRole extends userType {
        user_id   : number;
        user_roles: {
            description: string;
            name       : string;
            role_id    : number;
        }
    }

    defineProps<{
        users: userWithRole[];
    }>();

    const loading = ref(false);
    const disableUser = (user:userType) => {
        verifyModal('This user will be immediately disabled').then(res => {
            if(res)
            {
                loading.value = true;
                Inertia.delete(route('admin.user.destroy', user.username), {
                    onFinish: () => loading.value = false,
                });
            }
        });
    }
</script>
