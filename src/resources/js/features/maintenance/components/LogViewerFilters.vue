<script setup lang="ts">
import BaseSelectInput from "@/core/forms/components/baseInputs/BaseSelectInput.vue";
import BaseTextInput from "@/core/forms/components/baseInputs/BaseTextInput.vue";
import BaseVueForm from "@/core/forms/components/BaseVueForm.vue";
import { computed, reactive } from "vue";
import { show } from "@/wayfinder/routes/maint/logs";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    logFile: string;
    logList: string[];
    logStats?: LogStats;
}>();

const searchFilters = reactive({
    search: "",
    logFile: props.logFile,
    logLevel: "All",
    user: "All",
});

const logLevelList = computed<string[]>(() => {
    let list: string[] = [];

    if (props.logStats) {
        list = Object.keys(props.logStats?.levels);
    }

    list.unshift("All");

    return list;
});

const userList = computed<string[]>(() => {
    let list: string[] = [];

    if (props.logStats?.userList) {
        list = Object.keys(props.logStats.userList);
    }

    list.unshift("All");

    return list;
});

/**
 * Move to a different file
 */
const onLogFileChange = (): void => {
    router.get(show.url(searchFilters.logFile));
};
</script>

<template>
    <div>
        <BaseVueForm name="log-filters" hide-submit hide-overlay>
            <!-- <div>
                <BaseTextInput
                    v-model="searchFilters.search"
                    name="search"
                    label="Search message, user, IP Address, or Trace ID"
                    placeholder="Search message, user, IP Address, or Trace ID"
                />
            </div> -->
            <div class="flex gap-2">
                <BaseSelectInput
                    v-model="searchFilters.logFile"
                    name="logFile"
                    label="Log File"
                    :list="logList"
                    @change="onLogFileChange"
                />
                <!-- <BaseSelectInput
                    v-model="searchFilters.logLevel"
                    class="[&_option]:uppercase"
                    name="logLevel"
                    label="Log Level"
                    :list="logLevelList"
                />
                <BaseSelectInput
                    v-model="searchFilters.user"
                    name="user"
                    label="User"
                    :list="userList"
                /> -->
            </div>
        </BaseVueForm>
    </div>
</template>
