<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import Button from "@/Components/_Base/Buttons/BaseButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import { router } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

const props = defineProps<{
    role: userRole;
    permissionList: userRolePermissionGroup;
    permissionValues: userRolePermission[];
}>();

/**
 * Get the Icon and Text Color for the permission type
 */
const getIconProps = (permId: number): { icon: string; class: string } => {
    let value = props.permissionValues.find(
        (perm) => perm.perm_type_id === permId
    );

    if (value?.allow) {
        return {
            icon: "check",
            class: "text-success",
        };
    }

    return {
        icon: "xmark",
        class: "text-danger",
    };
};

/**
 * Create a new Role as a copy of this one
 */
const copyRole = (): void => {
    router.post(route("admin.user-roles.copy"), props.role);
};

/**
 * Delete the Role from the Database.  Note, role cannot be deleted if in use.
 */
const deleteRole = (): void => {
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

<template>
    <div class="flex justify-center">
        <Card title="Role Permissions" class="tb-card-lg">
            <h5 class="text-center font-bold text-lg">{{ role.name }}</h5>
            <p class="text-center">{{ role.description }}</p>
            <hr />
            <div>
                <div v-for="(group, name) in permissionList" :key="name">
                    <h6 class="font-bold">
                        {{ name }}
                    </h6>
                    <div class="grid md:grid-cols-3 lg:grid-cols-4">
                        <template v-for="opt in group" :key="opt.perm_type_id">
                            <div v-if="opt.feature_enabled" class="flex">
                                <div class="me-1">
                                    <fa-icon
                                        v-bind="getIconProps(opt.perm_type_id)"
                                    />
                                </div>
                                <div>
                                    {{ opt.description }}
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </Card>
    </div>
    <div class="flex justify-center">
        <Card class="tb-card-md">
            <Button
                class="w-full my-1"
                text="Copy Role"
                icon="copy"
                @click="copyRole"
            />
            <EditButton
                v-if="role.allow_edit"
                class="w-full my-1"
                text="Edit Role"
                :href="$route('admin.user-roles.edit', role.role_id)"
            />
            <DeleteButton
                v-if="role.allow_edit"
                class="w-full my-1"
                text="Delete Role"
                @click="deleteRole"
            />
        </Card>
    </div>
</template>
