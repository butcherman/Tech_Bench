<script setup lang="ts">
import Collapse from "@/Components/_Base/Collapse.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, number, boolean } from "yup";
import { ref } from "vue";

const props = defineProps<{
    feature_enabled: boolean;
    default_link_life: number;
    auto_delete: boolean;
    auto_delete_days: number;
    auto_delete_override: boolean;
}>();

const showCollapse = ref<boolean>(props.feature_enabled);
const showAutoDelete = ref<boolean>(props.auto_delete);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    feature_enabled: props.feature_enabled,
    default_link_life: props.default_link_life,
    auto_delete: props.auto_delete,
    auto_delete_days: props.auto_delete_days,
    auto_delete_override: props.auto_delete_override,
};
const schema = object({
    feature_enabled: boolean().required(),
    default_link_life: number().required(),
    auto_delete: boolean().required(),
    auto_delete_days: number().required(),
    auto_delete_override: boolean().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.links.settings.update')"
        submit-method="put"
        submit-text="Update Settings"
    >
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="feature-enabled"
                    name="feature_enabled"
                    label="Feature Enabled"
                    @change="showCollapse = !showCollapse"
                />
                <Collapse :show="showCollapse">
                    <SwitchInput
                        id="auto-delete"
                        name="auto_delete"
                        label="Auto Delete Links"
                        help="When enabled, expired links will automatically be
                          removed along with attached files"
                        @change="showAutoDelete = !showAutoDelete"
                    />
                    <SwitchInput
                        id="auto-delete-override"
                        name="auto_delete_override"
                        label="Allow Users to override Auto Delete"
                        help="Allow Users to disable Auto Delete for their profile"
                    />
                </Collapse>
            </div>
        </div>
        <Collapse :show="showCollapse">
            <Collapse :show="showAutoDelete">
                <TextInput
                    id="delete-days"
                    type="number"
                    name="auto_delete_days"
                    label="Auto Delete Days"
                    help="When Auto Delete is enabled, this field determines how
                          long after the link has expired will be before it is
                          be deleted"
                >
                    <template #end-text> Days </template>
                </TextInput>
            </Collapse>
            <TextInput
                id="link-life"
                class="mt-4"
                type="number"
                name="default_link_life"
                label="Default Link Life"
                help="Set how many days the links is valid for by default.
                          This can be easily changed when creating a new link"
            >
                <template #end-text> Days</template>
            </TextInput>
        </Collapse>
    </VueForm>
</template>
