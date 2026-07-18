<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object } from "yup";
import { useForm } from "vee-validate";
import { closeNodeEditor } from "@/Composables/Workbook/WorkbookEditor.module";

const props = defineProps<{
    node: workbookNode;
}>();

const validationSchema = object({});

const initialValues = props.node.props;

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form: workbookNodeProps): void => {
    if (props.node.nodeHelper) {
        let formInputs = Object.keys(props.node.nodeHelper);

        // Go through each entry and set the proper data type, then save
        formInputs.forEach((input) => {
            if (props.node.nodeHelper) {
                let type = props.node.nodeHelper[input].type;
                let inputVal = form[input];

                switch (type) {
                    case "number":
                        inputVal = Number(inputVal);
                        break;
                    case "boolean":
                        inputVal = Boolean(inputVal);
                        break;
                    case "array":
                        inputVal = makeInputArray(inputVal);
                        break;
                }

                // Save the data
                props.node.props[input] = inputVal;
            }
        });
    }

    closeNodeEditor();
});

const makeInputArray = (
    checkVal:
        | boolean
        | number
        | workbookTableColumn[]
        | string
        | string[]
        | undefined,
): string[] | workbookTableColumn[] => {
    if (Array.isArray(checkVal)) {
        return checkVal;
    }

    let stringVal = String(checkVal);
    return stringVal.split(",").map((item) => item.trim());
};

/**Determine if this is an input that accepts text type data
 */
const isTextInput = (type: string): boolean => {
    let allowed = ["string", "number", "array"];

    return allowed.includes(type);
};
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <template v-for="(data, prop) in node.nodeHelper">
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
