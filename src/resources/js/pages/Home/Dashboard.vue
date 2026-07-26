<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import TextInput from "@/core/forms/components/TextInput.vue";
import PasswordInput from "@/core/forms/components/PasswordInput.vue";
import SwitchInput from "@/core/forms/components/SwitchInput.vue";
import SelectInput from "@/core/forms/components/SelectInput.vue";

const props = defineProps<{
    bookmarks: {
        techTips: any[];
    };
}>();

const selectList = ["option 1", "option 2", "option 3", "option 4"];
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
                :initial-values="{ select: null }"
                submit-method="post"
                :validation-schema="{}"
                submit-route="#"
            >
                <TextInput
                    name="text_1"
                    label="Test 1"
                    variant="filled"
                    disabled
                >
                    <template #prepend-input>https://</template>
                    <template #append-input> <fa-icon icon="eye" /> </template>
                </TextInput>
                <TextInput name="text_2" label="Test 2" variant="standard">
                    <template #prepend-input>https://</template>
                    <template #append-input> <fa-icon icon="eye" /> </template>
                </TextInput>
                <TextInput
                    name="text_3"
                    label="Test 3"
                    variant="outlined"
                    help-message="This is a help message"
                    placeholder="Test Placeholder"
                >
                    <template #prepend-input>https://</template>
                    <template #append-input>
                        <fa-icon icon="eye" />
                    </template>
                </TextInput>
                <PasswordInput name="password" label="Password" />
                <PasswordInput
                    name="password1"
                    label="Password"
                    variant="standard"
                />
                <PasswordInput
                    name="password2"
                    label="Password"
                    variant="filled"
                />
                <div class="flex justify-center">
                    <SwitchInput
                        name="switch"
                        label="Switch Input"
                        switch-variant="warning"
                        disabled
                    />
                </div>
                <SelectInput
                    name="select"
                    label="Select Input"
                    :list="selectList"
                    variant="outlined"
                />
                <SelectInput
                    name="select1"
                    label="Select Input"
                    :list="selectList"
                    variant="filled"
                />
                <SelectInput
                    name="select2"
                    label="Select Input"
                    :list="bookmarks.techTips"
                    variant="standard"
                    text-field="subject"
                    value-field="tip_id"
                />
            </VueForm>
        </Card>
    </div>
</template>
