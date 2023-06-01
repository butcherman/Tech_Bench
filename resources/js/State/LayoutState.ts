import { ref } from "vue";

/**
 * Navbar States (for mobile)
 */
export const navbarActive = ref<boolean>(false);
export const toggleNavbar = () => {
    navbarActive.value = !navbarActive.value;
};
export const closeNavbar = () => {
    navbarActive.value = false;
}
