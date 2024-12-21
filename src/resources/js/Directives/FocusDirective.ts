export const vFocusDirective = {
    mounted: (el: HTMLElement, binding: { value: boolean }) => {
        console.log(el);
        if (binding.value) {
            el.focus();
        }
    },
};
