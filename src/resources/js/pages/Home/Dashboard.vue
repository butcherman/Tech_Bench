<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import TextInput from "@/core/forms/components/TextInput.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex flex-col items-center gap-2">
        <h2 class="pb-2">Dashboard</h2>
        <Card>
            <VueForm
                name="test-form"
                :initial-values="{}"
                submit-method="post"
                :validation-schema="{}"
                submit-route="#"
            >
                <TextInput
                    id="test-1"
                    name="text_1"
                    label="Test 1"
                    input-style="filled"
                >
                    <template #prepend-input>https://</template>
                    <template #append-input> <fa-icon icon="eye" /> </template>
                </TextInput>
                <TextInput
                    id="test-2"
                    name="text_2"
                    label="Test 2"
                    input-style="standard"
                >
                    <template #prepend-input>https://</template>
                    <template #append-input> <fa-icon icon="eye" /> </template>
                </TextInput>
                <TextInput
                    id="test-3"
                    name="text_3"
                    label="Test 3"
                    input-style="outlined"
                >
                    <template #prepend-input>https://</template>
                    <template #append-input>
                        <fa-icon icon="eye" />
                    </template>
                </TextInput>
            </VueForm>
        </Card>
    </div>
</template>
