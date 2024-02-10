<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
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
            <h6>{{ name }}</h6>
            <div
                v-for="opt in group"
                :key="opt.perm_type_id"
                class="col-12 col-lg-4 col-md-6"
            >
                <CheckboxSwitch
                    :id="`permissions-${opt.perm_type_id}`"
                    :name="`permissions[${opt.perm_type_id}]`"
                    :label="opt.description"
                />
            </div>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { computed } from "vue";
import { array, object, string } from "yup";

const props = defineProps<{
    permissionList: userRolePermissionGroup;
    baseRole?: userRole;
    edit?: boolean;
}>();

const submitRoute = computed(() =>
    props.edit
        ? route("admin.user-roles.update", props.baseRole?.role_id)
        : route("admin.user-roles.store")
);
const submitMethod = computed(() => (props.edit ? "put" : "post"));
const submitText = computed(() => (props.edit ? "Update Role" : "Create Role"));

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
            init[item.perm_type_id] = item.allow || false;
        });
    });

    return init;
};

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
