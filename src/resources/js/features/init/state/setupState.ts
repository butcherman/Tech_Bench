import { readonly, ref } from "vue";
import { router } from "@inertiajs/vue3";
import {
    step1,
    step2,
    step3,
    step4,
    step5,
    finish,
} from "@/wayfinder/routes/init";

const currentStep = ref<number>(0);
const stepList = ref<SetupStepItem[]>([
    {
        id: 1,
        name: "Basic Settings",
        icon: "fa-cog",
        complete: false,
        route: step1.url(),
    },
    {
        id: 2,
        name: "Email Settings",
        icon: "fa-envelope",
        complete: false,
        route: step2.url(),
    },
    {
        id: 3,
        name: "User Settings",
        icon: "fa-users-cog",
        complete: false,
        route: step3.url(),
    },
    {
        id: 4,
        name: "Secure Admin Login",
        icon: "fa-house",
        complete: false,
        route: step4.url(),
    },
    {
        id: 5,
        name: "Verify Information",
        icon: "fa-certificate",
        complete: false,
        route: step5.url(),
    },
    {
        id: 6,
        name: "Finish",
        icon: "fa-check",
        complete: false,
        route: finish.url(),
    },
]);

export const useSetupState = () => {
    /**
     * Move to the next step, optionally mark the current step completed
     */
    const advanceStep = (markComplete: boolean = false): void => {
        if (markComplete) {
            let current = findStep(currentStep.value);

            if (current) {
                current.complete = true;
            }
        }

        currentStep.value++;
        let next = findStep(currentStep.value);

        if (next) {
            router.get(next?.route);
        }
    };

    /**
     * Find a step
     */
    const findStep = (stepId: number): SetupStepItem | undefined => {
        return stepList.value.find((step) => step.id === stepId);
    };

    return {
        currentStep: readonly(currentStep),
        stepList: readonly(stepList),
        advanceStep,
    };
};
