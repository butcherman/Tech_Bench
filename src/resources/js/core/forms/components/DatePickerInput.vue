<script setup lang="ts">
import BaseTextWrapper from "./wrappers/BaseTextWrapper.vue";
import { ref, useId } from "vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useDateHelper } from "../composables/dateHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [any];
}>();

const props = defineProps<{
    name: string;

    label?: string;
    helpMessage?: string;
    variant?: InputVariant;
    placeholder?: string;
    helpVisible?: boolean;
    autocomplete?: string;
    disabled?: boolean;
    min?: string;
    max?: string;
}>();

const inputId = useId();
const wrapperId = useId();

const { onBlur, onFocus, value } = useBaseInputHelper(props, emit);
const { inputVariantStyle } = useFormInputHelper(props);
const {
    daysInMonth,
    dayOfWeek,
    selectedMonth,
    selectedMonthName,
    selectedYear,
    monthNames,
    yearList,
    increase,
    decrease,
    selectYear,
    selectMonth,
} = useDateHelper();

/**
 * Show the calendar input
 */
const showCal = ref<boolean>(false);

/**
 * Open the calendar before triggering the focus emit
 */
const triggerFocus = () => {
    showCal.value = true;
    onFocus();
};

/**
 * Which Calendar portion are we showing?
 */
const inDisplay = ref<"cal" | "year" | "month">("cal");

const onYearSelected = (year: number): void => {
    selectYear(year);
    inDisplay.value = "month";
};

const onMonthSelected = (month: string): void => {
    selectMonth(month);
    inDisplay.value = "cal";
};

const onDaySelected = (day: number | null): void => {
    console.log(day);

    let dateValue = `${selectedMonth.value + 1}-${day}-${selectedYear.value}`;
    value.value = dateValue;
    showCal.value = false;
};

/**
 * Close the calendar if the input looses focus
 */
const onClickOutsideCalHandler = [
    () => (showCal.value = false),
    {
        ignore: [`#${wrapperId}`],
    },
];
</script>

<template>
    <BaseTextWrapper v-bind="props" :id="wrapperId">
        <template v-for="name of Object.keys($slots)" v-slot:[name]="data">
            <slot :name="name" v-bind="data" />
        </template>
        <input
            v-model="value"
            class="block peer form-input-base"
            type="text"
            autocomplete="off"
            :class="[inputVariantStyle]"
            :disabled="disabled"
            :id="inputId"
            :placeholder="placeholder ?? ''"
            :name="name"
            @focus="triggerFocus"
            @blur="onBlur"
            @change="$emit('change', value)"
        />
        <div class="absolute inset-e-1.5 bottom-1.5 text-muted pointer">
            <fa-icon icon="fa-calendar" />
        </div>
        <label
            :for="inputId"
            class="form-label-base"
            :class="{ 'bg-white!': variant === 'outlined' }"
        >
            {{ label }}
        </label>
        <div
            v-show="showCal"
            class="absolute top-10 left-5 z-50 p-2 bg-white rounded-lg border border-slate-300"
            v-on-click-outside="onClickOutsideCalHandler"
        >
            <div class="flex gap-2">
                <div class="text-muted pointer" @click="decrease(inDisplay)">
                    <fa-icon icon="angles-left" />
                </div>
                <div class="grow pointer" @click="inDisplay = 'year'">
                    <div class="text-center">
                        {{ `${selectedMonthName} ${selectedYear}` }}
                    </div>
                </div>
                <div class="text-muted pointer" @click="increase(inDisplay)">
                    <fa-icon icon="angles-right" />
                </div>
            </div>
            <div>
                <div v-if="inDisplay === 'cal'" class="grid grid-cols-7 gap-2">
                    <div v-for="day in dayOfWeek" :key="day.short">
                        {{ day.short }}
                    </div>
                    <div
                        v-for="d in daysInMonth"
                        class="rounded-full text-center hover:bg-blue-300 pointer px-1"
                        @click="onDaySelected(d)"
                    >
                        {{ d }}
                    </div>
                </div>
                <div v-if="inDisplay === 'year'" class="grid grid-cols-5 gap-4">
                    <div
                        v-for="y in yearList"
                        class="hover:bg-blue-300 rounded-lg px-1 text-center pointer"
                        @click="onYearSelected(y)"
                    >
                        {{ y }}
                    </div>
                </div>
                <div
                    v-if="inDisplay === 'month'"
                    class="grid grid-cols-3 gap-3"
                >
                    <div
                        v-for="month in monthNames"
                        class="hover:bg-blue-300 rounded-lg px-1 text-center pointer"
                        @click="onMonthSelected(month)"
                    >
                        {{ month }}
                    </div>
                </div>
            </div>
        </div>
    </BaseTextWrapper>
</template>
