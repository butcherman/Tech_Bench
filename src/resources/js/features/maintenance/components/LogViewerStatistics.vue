<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import { useLogEntryHelper } from "../composables/logEntryHelper";

const props = defineProps<{
    loaded: boolean;
    loggingLevel: LogLevel;
    logStats?: LogStats;
}>();

const { getBadgeClass, getBadgeIcon } = useLogEntryHelper();
</script>

<template>
    <div>
        <div class="flex justify-center gap-3">
            <div
                class="border border-slate-300 rounded-lg flex-1 flex flex-col justify-center"
            >
                <div class="text-center px-1">
                    <BaseBadge
                        class="uppercase w-full text-center opacity-80"
                        variant="info"
                    >
                        <div class="text-center w-full">Total</div>
                    </BaseBadge>
                </div>
                <div class="text-center">
                    {{ logStats?.total }}
                </div>
            </div>
            <template v-for="(value, level) in logStats?.levels">
                <div
                    v-if="value > 0"
                    class="border border-slate-300 rounded-lg flex-1 flex flex-col justify-center"
                >
                    <div class="text-center px-1">
                        <BaseBadge
                            class="uppercase w-full text-center"
                            variant="none"
                            :class="getBadgeClass(level)"
                            :icon="getBadgeIcon(level)"
                            :text="level"
                        />
                    </div>
                    <div class="text-center">
                        {{ value ?? 0 }}
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
