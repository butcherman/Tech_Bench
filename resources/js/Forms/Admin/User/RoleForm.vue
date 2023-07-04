<template>
    <VueForm
        ref="roleForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-text="submitText"
        @submit="onSubmit"
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
        <div v-for="(group, name) in permissionList" :key="name" class="row">
            <h6>{{ name }}</h6>
            <div
                v-for="opt in group"
                :key="opt.perm_type_id"
                class="col-12 col-lg-4 col-md-6"
            >
                <CheckboxSwitch
                    :id="`type-${opt.perm_type_id}`"
                    :name="`type-${opt.perm_type_id}`"
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
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { object, string } from "yup";

const props = defineProps<{
    permissionList: { [key: string]: any[] };
    baseRole?: userRole;
    edit?: boolean;
}>();

const submitText = computed(() => (props.edit ? "Update Role" : "Create Role"));
const objectifyPermissions = () => {
    let initValues = {
        name: props.baseRole ? props.baseRole.name : "",
        description: props.baseRole ? props.baseRole.description : "",
    };

    Object.values(props.permissionList).forEach((perm) => {
        perm.forEach((item) => {
            initValues[`type-${item.perm_type_id}` as keyof typeof initValues] =
                item.allow ? item.allow : false;
        });
    });

    return initValues;
};

const roleForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = objectifyPermissions();
const schema = object({
    name: string().required(),
    description: string().required(),
});

const onSubmit = (form: userRole) => {
    const formData = useForm(form);

    if (props.edit) {
        formData.put(
            route("admin.user-roles.update", props.baseRole?.role_id),
            {
                onFinish: () => roleForm.value?.endSubmit(),
            }
        );
    } else {
        formData.post(route("admin.user-roles.store"), {
            onFinish: () => roleForm.value?.endSubmit(),
        });
    }
};
</script>
