export const vFocusDirective = {
    mounted: (el, binding) => {
        if(binding.value)
        {
            el.focus();
        }
    }
}
