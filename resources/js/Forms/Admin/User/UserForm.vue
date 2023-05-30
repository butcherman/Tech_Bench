<template>
    <VueForm
        ref="userForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        @submit="onSubmit"
    >
        <TextInput id="username" name="username" label="Username" />
        <TextInput id="first-name" name="first_name" label="First Name" />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" type="email" label="Email Address" />
        <SelectInput
            id="role"
            name="role_id"
            label="Role"
            :optionList="roleTypes"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string, number } from "yup";

const props = defineProps<{
    roles: userRole[];
    user?: userAuth;
}>();

const userForm = ref<InstanceType<typeof VueForm> | null>(null);
const roleTypes = computed<optionListObject[]>(() => {
    const roleArr = <optionListObject[]>[];
    props.roles.forEach((role) => {
        roleArr.push({
            text: role.name,
            value: role.role_id,
        });
    });

    return roleArr;
});

const validationSchema = object({
    username: string().required("You must enter a Username"),
    first_name: string().required("First Name is required"),
    last_name: string().required("Last Name is required"),
    email: string().email().required("Email Address is required"),
    role_id: number().required("You must select a Role"),
});

const initialValues = {
    username: props.user?.username || "",
    first_name: props.user?.first_name || "",
    last_name: props.user?.last_name || "",
    email: props.user?.email || "",
    role_id: props.user?.role_id || null,
};

const onSubmit = (form: user) => {
    const formData = useForm(form);

    if(props.user) {
        formData.put(route('admin.users.update', props.user.username), {
            onFinish: () => userForm.value?.endSubmit(),
        });

    } else {
        formData.post(route("admin.users.store"), {
            onFinish: () => userForm.value?.endSubmit(),
        });
    }
};
</script>
