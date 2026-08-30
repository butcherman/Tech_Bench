<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import EditButton from "@/core/components/buttons/EditButton.vue";
import verifyModal from "@/core/features/verifyModal";
import { edit, copy, destroy } from "@/wayfinder/routes/admin/user-roles";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    role: UserRole;
    permissionList: UserRolePermissionCategory;
    permissionValues: UserRolePermission[];
}>();

/**
 * Get the Icon and Text Color for the permission type
 */
const getIconProps = (permId: number): { icon: string; class: string } => {
    let value = props.permissionValues.find(
        (perm) => perm.perm_type_id === permId,
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
 * Create a new Role using this role as a baseline
 */
const onCopyRole = () => {
    router.post(copy.url(), { role_id: props.role.role_id });
};

/**
 * Delete Role.
 * Note:  Role cannot be deleted if it is assigned to a user
 */
const onDeleteRole = () => {
    verifyModal("This operation cannot be undone.").then((res) => {
        if (res) {
            router.delete(destroy.url(props.role.role_id));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col gap-3">
        <div class="flex justify-center">
            <Card title="Role Permissions">
                <h5 class="text-center font-bold text-lg">{{ role.name }}</h5>
                <p class="text-center">{{ role.description }}</p>
                <hr />
                <div>
                    <div v-for="(group, name) in permissionList" :key="name">
                        <h6 class="font-bold">
                            {{ name }}
                        </h6>
                        <div class="grid md:grid-cols-3 lg:grid-cols-4">
                            <template
                                v-for="opt in group"
                                :key="opt.perm_type_id"
                            >
                                <div v-if="opt.feature_enabled" class="flex">
                                    <div class="me-1">
                                        <fa-icon
                                            v-bind="
                                                getIconProps(opt.perm_type_id)
                                            "
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
            <Card size="md">
                <BaseButton
                    class="w-full my-1"
                    text="Copy Role"
                    icon="copy"
                    @click="onCopyRole"
                />
                <EditButton
                    v-if="role.allow_edit"
                    class="w-full my-1"
                    text="Edit Role"
                    :href="edit.url(role.role_id)"
                />
                <BaseButton
                    v-if="role.allow_edit"
                    class="w-full my-1"
                    icon="trash-alt"
                    text="Delete Role"
                    variant="danger"
                    @click="onDeleteRole"
                />
            </Card>
        </div>
    </div>
</template>
