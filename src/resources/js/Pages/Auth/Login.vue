<template>
    <div id="home-wrapper">
        <Head title="Login" />
        <div id="content-wrapper" class="container-fluid">
            <h1 class="text-center text-light">{{ app.name }}</h1>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <img :src="app.logo" class="img-fluid w-50" />
                                <hr
                                    v-if="
                                        welcomeMessage ||
                                        homeLinks.length ||
                                        publicLink
                                    "
                                />
                                <h6 v-if="welcomeMessage" class="text-center">
                                    {{ welcomeMessage }}
                                </h6>
                                <ul
                                    v-if="homeLinks.length || publicLink"
                                    class="list-group"
                                >
                                    <li
                                        v-if="publicLink"
                                        class="list-group-item"
                                    >
                                        <Link :href="publicLink.url">
                                            {{ publicLink.text }}
                                        </Link>
                                    </li>
                                    <li
                                        v-for="link in homeLinks"
                                        :key="link.url"
                                        class="list-group-item"
                                    >
                                        <a :href="link.url">{{ link.text }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="login-form-wrapper">
            <div id="login-form" class="row align-items-center h-100">
                <div class="col">
                    <div v-if="app.flash.length">
                        <div
                            v-for="flash in app.flash"
                            class="alert text-center"
                            :class="`alert-${flash.type}`"
                        >
                            {{ flash.message }}
                        </div>
                    </div>
                    <h5 class="text-center">Tech Login:</h5>
                    <LoginForm />
                    <div class="separator">or</div>
                    <a
                        v-if="allowOath"
                        as="button"
                        :href="$route('azure-login')"
                        class="btn btn-primary w-100 my-1"
                    >
                        Login With Office 365
                    </a>
                    <Link
                        :href="$route('password.request')"
                        as="button"
                        class="btn btn-primary w-100 my-1"
                    >
                        Forgot Password
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/AuthLayout.vue";
import LoginForm from "@/Forms/Auth/LoginForm.vue";
import { useAppStore } from "@/Store/AppStore";

interface homeLinks {
    text: string;
    url: string;
}

defineProps<{
    welcomeMessage?: string;
    homeLinks: homeLinks[];
    publicLink: homeLinks | false;
    allowOath: boolean;
}>();

const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<style scoped lang="scss">
@import "../../../scss/_global_variables.scss";

#home-wrapper {
    min-height: 100%;
    height: 100%;
    padding: 0;
    margin: 0;

    /**
    * Mobile/Small Screens
    */
    @media (max-width: $brk-md) {
        padding-top: 50px;

        #content-wrapper {
            width: 95%;
            margin: auto;
        }
        #login-form-wrapper {
            width: 95%;
            margin: auto;
            margin-top: 1rem;
            border-radius: 5px;
        }
    }
    /**
    * Standard screens
    */
    @media (min-width: $brk-md) {
        display: flex;
        flex-direction: row;
        align-items: center;

        #content-wrapper {
            width: 65%;
        }
        #login-form-wrapper {
            width: 35%;
            max-width: 400px;
            height: 100%;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            padding: 0;
            margin: 0;
        }
    }

    #login-form-wrapper {
        background-color: #ffffff;
        padding: 1rem;
    }
}
</style>
