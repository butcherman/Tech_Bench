<script setup lang="ts">
import BaseSelectInput from "@/core/forms/components/baseInputs/BaseSelectInput.vue";
import BaseTextInput from "@/core/forms/components/baseInputs/BaseTextInput.vue";
import BaseVueForm from "@/core/forms/components/BaseVueForm.vue";
import { computed, reactive, ref } from "vue";
import { show } from "@/wayfinder/routes/maint/logs";
import { router } from "@inertiajs/vue3";

const emit = defineEmits<{
    applyFilter: [LogFilter];
}>();

const props = defineProps<{
    logFile: string;
    logList: string[];
    logStats?: LogStats;
}>();

const searchDelay = ref<number>();

const searchFilters = reactive<LogFilter>({
    search: "",
    logFile: props.logFile,
    level: "All",
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

/**
 * Apply a log filter
 */
const onFilterChange = (): void => {
    clearTimeout(searchDelay.value);

    searchDelay.value = setTimeout(() => {
        emit("applyFilter", searchFilters);
    }, 500);
};
</script>

<template>
    <div>
        <BaseVueForm
            name="log-filters"
            hide-submit
            hide-overlay
            @submit="onFilterChange"
        >
            <div>
                <BaseTextInput
                    v-model="searchFilters.search"
                    name="search"
                    label="Search message, user, IP Address, or Trace ID"
                    placeholder="Search message, user, IP Address, or Trace ID"
                    @input="onFilterChange"
                />
            </div>
            <div class="flex gap-2">
                <BaseSelectInput
                    v-model="searchFilters.logFile"
                    name="logFile"
                    label="Log File"
                    :list="logList"
                    @change="onLogFileChange"
                />
                <BaseSelectInput
                    v-model="searchFilters.level"
                    class="[&_option]:uppercase"
                    name="logLevel"
                    label="Log Level"
                    :list="logLevelList"
                    @change="onFilterChange"
                />
                <BaseSelectInput
                    v-model="searchFilters.user"
                    name="user"
                    label="User"
                    :list="userList"
                    @change="onFilterChange"
                />
            </div>
        </BaseVueForm>
    </div>
</template>
