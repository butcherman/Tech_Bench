<template>
    <Head title="User Administration" />
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Select A User</div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in userList"
                                :key="user.user_id"
                                class="row-link"
                            >
                                <td>
                                    <Link
                                        :href="
                                            $route(
                                                'admin.users.show',
                                                user.username
                                            )
                                        "
                                    >
                                        {{ user.username }}
                                    </Link>
                                </td>
                                <td>
                                    <Link
                                        :href="
                                            $route(
                                                'admin.users.show',
                                                user.username
                                            )
                                        "
                                    >
                                        {{ user.full_name }}
                                    </Link>
                                </td>
                                <td>
                                    <Link
                                        :href="
                                            $route(
                                                'admin.users.show',
                                                user.username
                                            )
                                        "
                                    >
                                        {{ user.email }}
                                    </Link>
                                </td>
                                <td>
                                    <Link
                                        :href="
                                            $route(
                                                'admin.users.show',
                                                user.username
                                            )
                                        "
                                    >
                                        {{ user.user_role.name }}
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <Link
                        as="button"
                        :href="$route('admin.users.create')"
                        class="btn btn-info w-100"
                        >Create New User</Link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps<{
    userList: userWithRole[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
