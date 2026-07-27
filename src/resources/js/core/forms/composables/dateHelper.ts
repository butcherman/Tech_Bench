import { computed, ref } from "vue";

export const useDateHelper = () => {
    const date = new Date();
    /**
     * Names of all 12 Months
     */
    const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    /**
     * Names of Days of Week with abreviations
     */
    const dayOfWeek = [
        {
            name: "Sunday",
            short: "Su",
        },
        {
            name: "Monday",
            short: "Mo",
        },
        {
            name: "Tuesday",
            short: "Tu",
        },
        {
            name: "Wednesday",
            short: "We",
        },
        {
            name: "Thursday",
            short: "Th",
        },
        {
            name: "Friday",
            short: "Fr",
        },
        {
            name: "Saturday",
            short: "Sa",
        },
    ];

    /**
     * variables that will build the full date
     */
    const selectedYear = ref(date.getFullYear());
    const startYear = ref(selectedYear.value - 9);
    const selectedMonth = ref(date.getMonth());
    const selectedMonthName = computed(() => monthNames[selectedMonth.value]);

    /**
     * List of Years to show as selectable
     */
    const yearList = computed(() => {
        const end = startYear.value + 19;

        return Array.from(
            { length: end - startYear.value + 1 },
            (_, index) => startYear.value + index,
        );
    });

    /**
     * Days in month with offset for day of the week
     */
    const daysInMonth = computed(() => {
        // Get first day of the month
        const firstDayIndex = new Date(
            selectedYear.value,
            selectedMonth.value,
            1,
        ).getDay();

        // Get total number of days in the month
        const totalDays = new Date(
            selectedYear.value,
            selectedMonth.value + 1,
            0,
        ).getDate();

        const daysArray = [];

        // Add empty/null offsets for days before the 1st of the month
        for (let i = 0; i < firstDayIndex; i++) {
            daysArray.push(null);
        }

        // Add actual days of the month
        for (let d = 1; d <= totalDays; d++) {
            daysArray.push(d);
        }

        return daysArray;
    });

    /*
    |---------------------------------------------------------------------------
    | Assignment Functions
    |---------------------------------------------------------------------------
    */

    /**
     * Assign the year
     */
    const selectYear = (year: number): void => {
        selectedYear.value = year;
    };

    /**
     * Assign the month
     */
    const selectMonth = (month: string): void => {
        let index = monthNames.findIndex((m) => m === month);
        selectedMonth.value = index;
    };

    const increaseYear = () => {
        startYear.value += 5;
    };

    const decreaseYear = () => {
        startYear.value -= 5;
    };

    const increaseMonth = () => {
        if (selectedMonth.value === 11) {
            selectedYear.value++;
            selectedMonth.value = 0;
            return;
        }

        selectedMonth.value++;
    };

    const decreaseMonth = () => {
        if (selectedMonth.value === 0) {
            console.log("next year");
            selectedYear.value--;
            selectedMonth.value = 11;
            return;
        }

        selectedMonth.value--;
    };

    const increase = (type: "year" | "month" | "cal") => {
        if (type === "year") {
            increaseYear();
            return;
        }

        increaseMonth();
    };

    const decrease = (type: "year" | "month" | "cal") => {
        if (type === "year") {
            decreaseYear();
            return;
        }

        decreaseMonth();
    };

    return {
        daysInMonth,
        dayOfWeek,
        monthNames,
        selectedMonth,
        selectedMonthName,
        selectedYear,
        yearList,
        increase,
        decrease,
        selectMonth,
        selectYear,
    };
};
