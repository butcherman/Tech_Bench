<script setup lang="ts">
import SelectInput from "@/core/forms/components/validatedInputs/SelectInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number } from "yup";
import { computed } from "vue";
import { submit } from "@/wayfinder/routes/init/step-4";
import { update, store } from "@/wayfinder/routes/admin/user";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    roleList: UserRole[];
    user?: User;
    init?: boolean;
}>();

const submitText = computed(() =>
    props.user ? "Update User Profile" : "Create User",
);

const submitRoute = computed(() => {
    if (props.init && props.user) {
        return submit.url(props.user.username);
    }

    return props.user ? update.url(props.user.username) : store.url();
});

const submitMethod = computed(() => {
    return props.user ? "put" : "post";
});

const initValues = {
    username: props.user?.username ?? "",
    first_name: props.user?.first_name ?? "",
    last_name: props.user?.last_name ?? "",
    email: props.user?.email ?? "",
    role_id: props.user?.role_id ?? "4",
};

const schema = object({
    username: string().required(),
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
    role_id: number().required(),
});
</script>

<template>
    <VueForm
        name="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
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
            :list="roleList"
        />
    </VueForm>
</template>
