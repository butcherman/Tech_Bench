<template>
  <VueForm
    :initial-values="initValues"
    :validation-schema="schema"
    :submit-route="submitRoute"
    :submit-text="submitText"
    submit-method="put"
    @success="$emit('success')"
  >
    <TextInput id="url" name="url" label="Site URL" focus>
      <template #start-group-text>
        <span class="input-group-text">https://</span>
      </template>
    </TextInput>
    <TextInput id="company-name" name="company_name" label="Company Name" />
    <SelectInput id="timezone" name="timezone" label="Timezone" :list="tzList" />
    <RangeInput
      id="file-size"
      name="max_filesize"
      label="Maximum Uploaded File Size"
      :min="5000"
      :max="10737418240"
      format="prettybytes"
    />
  </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import { number, object, string } from "yup";
import { computed } from "vue";

defineEmits(["success"]);
const props = defineProps<{
  tzList: TimezoneList;
  url: string;
  company_name: string;
  timezone: string;
  maxFilesize: number;
  init?: boolean;
}>();

const submitRoute = computed(() =>
  props.init ? route("init.step-1.submit") : route("admin.basic-settings.update")
);

const submitText = computed(() =>
  props.init ? "Save and Continue" : "Update Application Configuration"
);

const initValues = {
  url: props.url,
  company_name: props.company_name,
  timezone: props.timezone,
  max_filesize: props.maxFilesize,
};
const schema = object({
  url: string().required(),
  company_name: string().required().label("Company Name"),
  timezone: string().required(),
  max_filesize: number().required(),
});
</script>
