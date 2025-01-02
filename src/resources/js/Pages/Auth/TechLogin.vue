<template>
    <div id="home-wrapper" class="h-full w-full">
        <Head title="Login" />
        <div
            id="content-wrapper"
            class="grid grid-cols-1 md:grid-cols-4 md:h-screen"
        >
            <div class="md:col-span-3 justify-self-center self-center m-3">
                <LogoImage>
                    <div v-if="hasLinks" class="mt-4">
                        <hr class="bg-gray-500" />
                        <h6
                            v-if="welcomeMessage"
                            class="text-center font-semibold"
                        >
                            {{ welcomeMessage }}
                        </h6>
                        <ul
                            v-if="homeLinks.length || publicLink"
                            class="text-center"
                        >
                            <li v-if="publicLink">
                                <Link :href="publicLink.url">
                                    {{ publicLink.text }}
                                </Link>
                            </li>
                            <li
                                v-for="link in homeLinks"
                                :key="link.url"
                                class="list-group-item"
                            >
                                <a :href="link.url" target="_blank">
                                    {{ link.text }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </LogoImage>
            </div>
            <div
                id="login-form-wrapper"
                class="grid place-items-center bg-white md:h-full m-4 md:m-0 p-3"
            >
                <div class="w-full">
                    <AuthFlash />
                    <h3 class="text-center">Tech Login:</h3>
                    <TechLoginForm />
                    <div v-if="allowOath" class="w-full text-center">
                        <div class="separator">or</div>
                        <a :href="$route('azure-login')">
                            <!-- <Button
                                text="Login with Office 365"
                                class="md:w-3/4"
                            /> -->
                            <BaseButton
                                class="md:w-3/4"
                                text="Login with Office 365"
                            />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import AuthFlash from "@/Layouts/Auth/AuthFlash.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
import TechLoginForm from "@/Forms/Auth/TechLoginForm.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import { computed } from "vue";

interface homeLinks {
    text: string;
    url: string;
}

const props = defineProps<{
    welcomeMessage?: string;
    homeLinks: homeLinks[];
    publicLink: homeLinks | false;
    allowOath: boolean;
}>();

/**
 * If there are any links or message that should go under the logo
 */
const hasLinks = computed(
    () => props.welcomeMessage || props.homeLinks.length || props.publicLink
);
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
