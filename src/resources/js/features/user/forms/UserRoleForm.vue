<script setup lang="ts">
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, array } from "yup";
import { computed } from "vue";
import { update, store } from "@/wayfinder/routes/admin/user-roles";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    permissionList: UserRolePermissionCategory;
    permissionValues: UserRolePermission[];
    baseRole?: UserRole;
    editRole?: UserRole;
}>();

/**
 * Name for the role if a copy of another role
 */
const getRoleInitName = () => {
    if (props.editRole) {
        return props.editRole.name;
    }

    if (props.baseRole) {
        return `Copy of ${props.baseRole.name}`;
    }

    return "";
};

/**
 * Description of the role if a copy of another role
 */
const getRoleInitDescription = () => {
    if (props.editRole) {
        return props.editRole.description;
    }

    if (props.baseRole) {
        return `Copy of ${props.baseRole.description}`;
    }

    return "";
};

/**
 * Build the initial values for each permission type
 */
const roleInitialPermissions = (): boolean[] => {
    let init: boolean[] = [];

    Object.values(props.permissionList).forEach((perm) => {
        perm.forEach((item) => {
            let value = props.permissionValues.find(
                (perm) => perm.perm_type_id === item.perm_type_id,
            );
            init[item.perm_type_id] = value?.allow || false;
        });
    });

    return init;
};

const submitRoute = computed<string>(() =>
    props.editRole ? update.url(props.editRole.role_id) : store.url(),
);

const submitMethod = computed<"put" | "post">(() =>
    props.editRole ? "put" : "post",
);
const submitText = computed<string>(() =>
    props.editRole ? "Update Role" : "Create Role",
);

const initValues = {
    name: getRoleInitName(),
    description: getRoleInitDescription(),
    permissions: roleInitialPermissions(),
};

const schema = object({
    name: string().required(),
    description: string().required(),
    permissions: array().required(),
});
</script>

<template>
    <VueForm
        name="user-role-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="$emit('success')"
    >
        <TextInput
            id="role-name"
            name="name"
            label="Role Name"
            placeholder="Enter a descriptive name"
        />
        <TextInput
            id="role-description"
            name="description"
            label="Description"
            placeholder="Enter a short description"
        />
        <div
            v-for="(group, name) in permissionList"
            :key="name"
            class="row mb-2"
        >
            <h6 class="text-muted">{{ name }}:</h6>
            <div class="grid lg:grid-cols-2 2xl:grid-cols-3 gap-2">
                <template v-for="opt in group" :key="opt.perm_type_id">
                    <div>
                        <SwitchInput
                            :id="`permissions-${opt.perm_type_id}`"
                            :name="`permissions[${opt.perm_type_id}]`"
                            :label="opt.description"
                        />
                    </div>
                </template>
            </div>
        </div>
    </VueForm>
</template>
