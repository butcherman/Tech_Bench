<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object } from "yup";
import { useForm } from "vee-validate";
import { closeWbEditor } from "@/Composables/Equipment/WorkbookEditor.module";

const props = defineProps<{
    component: workbookElement;
}>();

const validationSchema = object({});

const initialValues = props.component.props;

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    let keyList = Object.keys(form);

    // Change the data type to the proper input type (number, bool, etc)
    keyList.forEach((input) => {
        if (props.component.assist) {
            let dataType = props.component.assist[input].type;
            if (dataType === "number") {
                form[input] = Number(form[input]);
            } else if (
                dataType === "array" &&
                typeof form[input] === "string"
            ) {
                form[input] = form[input].split(",").map((item) => item.trim());
            }
        }
    });

    props.component.props = form;
    closeWbEditor();
});

/**Determine if this is an input that accepts text type data
 */
const isTextInput = (type: string): boolean => {
    let allowed = ["string", "number", "array"];

    return allowed.includes(type);
};
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <template v-for="(data, prop) in component.assist">
            <TextInput
                v-if="isTextInput(data.type)"
                :id="prop.toString()"
                :name="prop.toString()"
                :label="data.label"
                :help="data.help"
                :type="data.type"
            />
            <SwitchInput
                v-else-if="data.type === 'boolean'"
                :id="prop.toString()"
                :name="prop.toString()"
                :label="data.label"
                :help="data.help"
            />
            <div v-else>other input type</div>
        </template>
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>
