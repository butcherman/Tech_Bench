<script setup lang="ts">
import TextInputWrapper from "../wrappers/TextInputWrapper.vue";
import { computed, ref, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";
import { useDateHelper } from "../../composables/dateHelper.js";

const emit = defineEmits<{
    "update:value": [string];
    focus: [];
    blur: [];
    change: [string];
}>();

const props = defineProps<{
    name: string;
    value: string;

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    // max?: string;
    // min?: string;
    placeholder?: string;
    variant?: InputVariant;
}>();

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);
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

const inputId = useId();
const showCal = ref(false);
const formattedValue = ref();
const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});

/**
 * Open the Calendar input to allow user to select the date.
 */
const triggerFocus = () => {
    showCal.value = true;
    onFocus();
};

/**
 * If the input was cleared, we need to clear the head end value as well.
 * If date was manually inputted, we need to properly format it.
 */
const onChange = () => {
    if (!formattedValue.value) {
        inputValue.value = "";
        return;
    }

    let newDate = new Date(formattedValue.value);

    selectYear(newDate.getFullYear());
    selectMonth(monthNames[newDate.getMonth()]);
    onDaySelected(newDate.getDate());
};

/*
|-------------------------------------------------------------------------------
| Calendar Data
|-------------------------------------------------------------------------------
*/

// Which Calendar portion are we showing?
const inDisplay = ref<"cal" | "year" | "month">("cal");

const onYearSelected = (year: number): void => {
    selectYear(year);
    inDisplay.value = "month";
};

const onMonthSelected = (month: string): void => {
    selectMonth(month);
    inDisplay.value = "cal";
};

/**
 * Assign the input value
 */
const onDaySelected = (day: number | null): void => {
    let rawDateValue = `${selectedMonth.value + 1}-${day}-${selectedYear.value}`;
    let formatted = `${selectedMonthName.value} ${day}, ${selectedYear.value}`;

    inputValue.value = rawDateValue;
    formattedValue.value = formatted;

    showCal.value = false;
    emit("change", inputValue.value);
};
</script>

<template>
    <TextInputWrapper
        v-bind="props"
        :has-focus="hasFocus"
        v-on-click-outside="() => (showCal = false)"
    >
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
        <input
            v-model="formattedValue"
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
            @change="onChange"
        />
        <div
            class="absolute inset-e-1.5 bottom-1.5 text-muted pointer"
            @click="triggerFocus"
        >
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
            v-if="showCal"
            class="absolute top-10 left-5 z-50 p-2 bg-white rounded-lg border border-slate-300"
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
    </TextInputWrapper>
</template>
