<template>
    <VueForm
        ref="verificationForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Verify"
        @submit="onSubmit"
    >
        <TextInput
            id="code"
            name="code"
            label="Verification Code"
            focus
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
import { object, number } from "yup";

// const props = defineProps<{}>();

const verificationForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    code: '',
};
const schema = object({
    code: number().required(),
});

const onSubmit = (form) => {
    const formData = useForm(form);
    console.log(formData);
    //

    formData.post(route('2fa.store'), {
        onFinish: () => verificationForm.value?.endSubmit(),
    });
};
</script>