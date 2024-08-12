<template>
    <div>
        <Head title="View Role" />
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Role Permissions</div>
                        <h5 class="text-center">{{ role.name }}</h5>
                        <p class="text-center">{{ role.description }}</p>
                        <div class="border-top pt-2">
                            <div
                                v-for="(group, name) in permissionList"
                                :key="name"
                                class="row"
                            >
                                <h6 class="mt-2">{{ name }}</h6>
                                <template
                                    v-for="opt in group"
                                    :key="opt.perm_type_id"
                                >
                                    <div
                                        v-show="opt.feature_enabled"
                                        class="col-12 col-lg-4"
                                    >
                                        <fa-icon
                                            :icon="
                                                isAllowed(opt)
                                                    ? 'fa-check'
                                                    : 'fa-xmark'
                                            "
                                            :class="
                                                isAllowed(opt)
                                                    ? 'text-success'
                                                    : 'text-danger'
                                            "
                                        />
                                        {{ opt.description }}
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <Link
                            :href="$route('admin.user-roles.copy')"
                            as="button"
                            class="btn btn-info m-1"
                            method="post"
                            :data="role"
                        >
                            <fa-icon icon="copy" class="me-1" />
                            Copy Role
                        </Link>
                        <EditButton
                            v-if="role.allow_edit"
                            :href="
                                $route('admin.user-roles.edit', role.role_id)
                            "
                            class="m-1"
                        >
                            Edit Role
                        </EditButton>
                        <DeleteButton
                            v-if="role.allow_edit"
                            class="m-1"
                            @click="deleteRole"
                        >
                            Delete Role
                        </DeleteButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    role: userRole;
    permissionList: userRolePermissionGroup;
    permissionValues: userRolePermission[];
}>();

const isAllowed = (permission: userRolePermission) => {
    let value = props.permissionValues.find(
        (perm) => perm.perm_type_id === permission.perm_type_id
    );
    return value?.allow || false;
};

const deleteRole = () => {
    verifyModal("This Operation Cannot Be Undone").then((res) => {
        if (res) {
            router.delete(
                route("admin.user-roles.destroy", props.role.role_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
