<template>
    <VueForm
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :submit-method="submitMethod"
        :validation-schema="schema"
        @success="$emit('success')"
    >
        <TextInput id="username" name="username" label="Username" focus />
        <TextInput id="first-name" name="first_name" label="First Name" />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" type="email" label="Email Address" />
        <SelectInput
            id="role"
            label="Role"
            name="role_id"
            text-field="name"
            value-field="role_id"
            :disabled="init"
            :list="roles"
        />
    </VueForm>
</template>

<script setup lang="ts">
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed } from "vue";
import { number, object, string } from "yup";

defineEmits(["success"]);
const props = defineProps<{
    roles: userRole[];
    user?: user | null;
    init?: boolean;
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitText = computed(() =>
    props.user ? "Update User Profile" : "Create User"
);

const submitRoute = computed(() => {
    if (props.init) {
        return route("init.step-4.submit", props.user?.username);
    }

    return props.user
        ? route("admin.user.update", props.user.username)
        : route("admin.user.store");
});

const submitMethod = computed(() => {
    return props.user ? "put" : "post";
});

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    username: props.user?.username || null,
    first_name: props.user?.first_name || null,
    last_name: props.user?.last_name || null,
    email: props.user?.email || null,
    role_id: props.user?.role_id || 4,
};

const schema = object({
    username: string().required(),
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
    role_id: number().required(),
});
</script>
