import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

type equipmentSelectList = {
    label: string;
    items: {
        label: string;
        value: number;
    };
};

type equipmentProps = {
    availableEquipment: equipmentSelectList[];
} & pageProps;

const page = usePage<equipmentProps>();

/**
 * Categorized list of Equipment Types from DB.
 */
export const availableEquipment = computed<equipmentSelectList[]>(
    () => page.props.availableEquipment
);
