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
                <MultiSelectInput
                    name="multiselect"
                    :list="selectList"
                    label="multi"
                    variant="filled"
                />
                <MultiSelectInput
                    name="multiselect2"
                    :list="selectList"
                    label="multi"
                    variant="standard"
                />
                <MultiSelectInput
                    name="multiselect3"
                    :list="selectList"
                    label="multi"
                    variant="outlined"
                />
                <DatePickerInput
                    name="datepicker"
                    label="Datepicker"
                    variant="standard"
                />
                <DatePickerInput
                    name="datepicker1"
                    label="Datepicker"
                    variant="outlined"
                />
                <DatePickerInput
                    name="datepicker2"
                    label="Datepicker"
                    variant="filled"
                />
            </VueForm>
        </Card>
    </div>
</template>
