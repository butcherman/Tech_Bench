export const vFocusDirective = {
    mounted: (el: HTMLElement, binding: { value: boolean }) => {
        if (binding.value) {
            el.focus();
        }
    },
};
