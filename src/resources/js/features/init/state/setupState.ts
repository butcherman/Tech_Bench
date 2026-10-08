import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const page = usePage();

const activeStep = computed(() => page.props.step ?? 0);

const stepList = ref<InitStep[]>([
    {
        id: 0,
        name: "Welcome",
        completed: false,
    },
    {
        id: 1,
        name: "Application",
        completed: false,
    },
    {
        id: 2,
        name: "Email",
        completed: false,
    },
    {
        id: 3,
        name: "Security",
        completed: false,
    },
    {
        id: 4,
        name: "Administrator",
        completed: false,
    },
    {
        id: 5,
        name: "Review",
        completed: false,
    },
]);

export const useSetupState = () => {
    const markStepComplete = (stepId: number): void => {
        let step = stepList.value.find((st) => st.id === stepId);

        if (step) {
            step.completed = true;
        }

        console.log(step);
    };

    return {
        activeStep,
        stepList,
        markStepComplete,
    };
};
