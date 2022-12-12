<template>
    <Head title="Disabled Users" />
    <div>
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Disabled At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in userList" :key="user.user_id">
                                    <td>{{ user.username }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.full_name }}</td>
                                    <td>{{ user.deleted_at }}</td>
                                    <td>
                                        <Link
                                            :href="$route('admin.users.enable', user.username)"
                                            class="pointer text-muted"
                                            title="Enable User"
                                            v-tooltip
                                        >
                                            <fa-icon icon="fa-unlock-alt" />
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App               from '@/Layouts/app.vue';
    import type { userType } from '@/Types';

    interface disabledUserType extends userType {
        user_id   : number;
        deleted_at: string;
    }

    defineProps<{
        userList: disabledUserType[];
    }>();

    const $route = route;
</script>

<script lang="ts">
    export default { layout: App }
</script>
