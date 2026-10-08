import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    step1,
    step2,
    step3,
    step4,
    step5,
    welcome,
} from "@/wayfinder/routes/init";

const activeStep = ref<number>(0);

const stepList = ref<InitStep[]>([
    {
        id: 0,
        name: "Welcome",
        completed: false,
        inProgress: false,
    },
    {
        id: 1,
        name: "Application",
        completed: false,
        inProgress: false,
    },
    {
        id: 2,
        name: "Email",
        completed: false,
        inProgress: false,
    },
    {
        id: 3,
        name: "Security",
        completed: false,
        inProgress: false,
    },
    {
        id: 4,
        name: "Administrator",
        completed: false,
        inProgress: false,
    },
    {
        id: 5,
        name: "Review",
        completed: false,
        inProgress: false,
    },
]);

export const useSetupState = () => {
    const showForm = ref(true);

    const getStep = (stepId: number): InitStep | undefined => {
        return stepList.value.find((st) => st.id === stepId);
    };

    const markStepInProgress = (stepId: number): void => {
        let step = getStep(stepId);

        if (step) {
            step.inProgress = true;
            activeStep.value = stepId;
            showForm.value = true;
        }
    };

    const markStepComplete = (stepId: number): void => {
        let step = getStep(stepId);

        if (step) {
            step.inProgress = false;
            step.completed = true;
        }
    };

    const getStepUrl = (step: InitStep): string => {
        return (
            {
                0: welcome.url(),
                1: step1.url(),
                2: step2.url(),
                3: step3.url(),
                4: step4.url(),
                5: step5.url(),
            }[step.id] ?? welcome.url()
        );
    };

    const onStepSuccess = () => {
        showForm.value = false;
        markStepComplete(activeStep.value);

        let nextStep = getStep(activeStep.value + 1);

        if (nextStep) {
            router.get(getStepUrl(nextStep));
        }
    };

    return {
        activeStep,
        stepList,
        showForm,
        markStepInProgress,
        markStepComplete,
        getStepUrl,
        onStepSuccess,
    };
};
