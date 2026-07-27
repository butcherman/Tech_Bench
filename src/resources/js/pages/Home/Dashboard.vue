<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import TextInput from "@/core/forms/components/TextInput.vue";
import PasswordInput from "@/core/forms/components/PasswordInput.vue";
import SwitchInput from "@/core/forms/components/SwitchInput.vue";
import SelectInput from "@/core/forms/components/SelectInput.vue";
import AutoCompleteInput from "@/core/forms/components/AutoCompleteInput.vue";
import DatePickerInput from "@/core/forms/components/DatePickerInput.vue";
import MultiSelectInput from "@/core/forms/components/MultiSelectInput.vue";
import MultiSelectGroupInput from "@/core/forms/components/MultiSelectGroupInput.vue";
import OtpInput from "@/core/forms/components/OtpInput.vue";

const selectList = ["option 1", "option 2", "option 3", "option 4"];

const groupList = [
    {
        label: "Group 1",
        list: ["1-1", "1-2", "1-3"],
    },
    {
        label: "Group 2",
        list: ["2-1", "2-2", "2-3"],
    },
];
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
                <OtpInput
                    name="Otp"
                    help-message="Otp Input"
                    variant="standard"
                />
                <OtpInput
                    name="Otp1"
                    help-message="Otp Input"
                    variant="filled"
                />
                <OtpInput
                    name="Otp2"
                    help-message="Otp Input"
                    variant="outlined"
                />
            </VueForm>
        </Card>
    </div>
</template>
