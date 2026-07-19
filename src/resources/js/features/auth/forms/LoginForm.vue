<script setup lang="ts">
import VueForm from "@/core/forms/VueForm.vue";

//

const initialValues = {};
const validationSchema = {};
</script>

<template>
    <div>
        <VueForm
            name="login-form"
            submit-method="post"
            submit-route="#"
            :validation-schema="{}"
            :initial-values="{}"
        >
            slot...
        </VueForm>
    </div>
</template>
