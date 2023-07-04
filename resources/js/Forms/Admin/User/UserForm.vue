<template>
    <VueForm
        ref="userForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-text="submitText"
        @submit="onSubmit"
    >
        <TextInput id="username" name="username" label="Username" focus />
        <TextInput id="first-name" name="first_name" label="First Name" />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" type="email" label="Email Address" />
        <SelectInput
            id="role"
            name="role_id"
            label="Role"
            :list="roles"
            text-field="name"
            value-field="role_id"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";

import { ref, computed } from "vue";
import { number, object, string } from "yup";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    roles: userRole[];
    user?: user;
}>();

const submitText = computed(() => {
    return props.user ? "Update User" : "Create User and Send Email Invite";
});
const userForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    username: props.user ? props.user.username : "",
    first_name: props.user ? props.user.first_name : "",
    last_name: props.user ? props.user.last_name : "",
    email: props.user ? props.user.email : "",
    role_id: props.user ? props.user.role_id : 4,
};
const schema = object({
    username: string().required(),
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
    role_id: number().required(),
});

const onSubmit = (form: user) => {
    const formData = useForm(form);

    if (props.user) {
        formData.put(route("admin.users.update", props.user.username), {
            onFinish: () => userForm.value?.endSubmit(),
        });
    } else {
        formData.post(route("admin.users.store"), {
            onFinish: () => userForm.value?.endSubmit(),
        });
    }
};
</script>
