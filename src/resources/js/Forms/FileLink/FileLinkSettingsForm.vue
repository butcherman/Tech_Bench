<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.links.settings.update')"
        submit-method="put"
        submit-text="Update Settings"
    >
        <div>
            <SwitchInput
                id="auto-delete"
                name="auto_delete"
                label="Auto Delete Links"
                help="When enabled, expired links will automatically be
                          removed along with attached files"
                @change="disableFields = !disableFields"
            />
            <SwitchInput
                id="auto-delete-override"
                name="auto_delete_override"
                label="Allow Users to override Auto Delete"
                help="Allow Users to disable Auto Delete for their profile"
                :disabled="disableFields"
            />
            <TextInput
                id="delete-days"
                type="number"
                name="auto_delete_days"
                label="Auto Delete Days"
                help="When Auto Delete is enabled, this field determines how
                          long after the link has expired will be before it is
                          be deleted"
                :disabled="disableFields"
            >
                <template #end-group-text>
                    <span class="input-group-text">Days</span>
                </template>
            </TextInput>
            <TextInput
                id="link-life"
                class="mt-4"
                type="number"
                name="default_link_life"
                label="Default Link Life"
                help="Set how many days the links is valid for by default.
                          This can be easily changed when creating a new link"
            >
                <template #end-group-text
                    ><span class="input-group-text">Days</span></template
                >
            </TextInput>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import { ref } from "vue";
import { boolean, number, object } from "yup";

const props = defineProps<{
    settings: {
        default_link_life: number;
        auto_delete: boolean;
        auto_delete_days: number;
        auto_delete_override: boolean;
    };
}>();

const disableFields = ref(!props.settings.auto_delete);

const initValues = {
    default_link_life: props.settings.default_link_life,
    auto_delete: props.settings.auto_delete,
    auto_delete_days: props.settings.auto_delete_days,
    auto_delete_override: props.settings.auto_delete_override,
};
const schema = object({
    default_link_life: number().required(),
    auto_delete: boolean().required(),
    auto_delete_days: number().required(),
    auto_delete_override: boolean().required(),
});
</script>
