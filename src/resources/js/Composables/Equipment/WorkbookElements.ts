import { computed } from "vue";
import { formattingElements } from "./WorkbookElementGroups/FormattingElements.module";
import { textInputElements } from "./WorkbookElementGroups/InputTextElements.module";
import { specialElements } from "./WorkbookElementGroups/InputSpecialElements.module";

interface workbookElementWrapper {
    [key: string]: workbookElement[];
}

export const elementList = computed<workbookElementWrapper>(() => {
    return {
        formatting: formattingElements,
        textInput: textInputElements,
        specialInput: specialElements,
    };
});
