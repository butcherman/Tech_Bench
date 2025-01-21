<template>
    <VueForm
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        :validation-schema="schema"
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
            <div class="grid md:grid-cols-3">
                <template v-for="opt in group" :key="opt.perm_type_id">
                    <div v-show="opt.feature_enabled">
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

<script setup lang="ts">
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, array } from "yup";
import { computed } from "vue";

const props = defineProps<{
    permissionList: userRolePermissionGroup;
    permissionValues: userRolePermission[];
    baseRole?: userRole;
    edit?: boolean;
}>();

/**
 * Name for the role if a copy of another role
 */
const roleInitialName = computed(() =>
    props.baseRole ? `Copy of ${props.baseRole.name}` : ""
);

/**
 * Build the initial values for each permission type
 */
const roleInitialPermissions = () => {
    let init: boolean[] = [];

    Object.values(props.permissionList).forEach((perm) => {
        perm.forEach((item) => {
            let value = props.permissionValues.find(
                (perm) => perm.perm_type_id === item.perm_type_id
            );
            init[item.perm_type_id] = value?.allow || false;
        });
    });

    return init;
};

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() =>
    props.edit
        ? route("admin.user-roles.update", props.baseRole?.role_id)
        : route("admin.user-roles.store")
);

const submitMethod = computed(() => (props.edit ? "put" : "post"));
const submitText = computed(() => (props.edit ? "Update Role" : "Create Role"));

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    name: props.edit ? props.baseRole?.name : roleInitialName.value,
    description: props.baseRole?.description || "",
    permissions: roleInitialPermissions(),
};

const schema = object({
    name: string().required(),
    description: string().required(),
    permissions: array().required(),
});
</script>
