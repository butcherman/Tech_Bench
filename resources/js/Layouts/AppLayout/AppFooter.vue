<template>
    <footer class="footer page-footer">
        <div
            class="d-sm-flex justify-content-center justify-content-sm-between"
        >
            <span
                class="text-muted text-center text-sm-left d-block d-sm-inline-block"
            >
                Copyright &copy;
                {{ app.copyright }}
                <span class="d-none d-md-inline">
                    Butcherman - All rights reserved.</span
                >
            </span>
            <span
                class="text-muted float-none float-sm-end d-block mt-1 mt-sm-0 text-center"
            >
                {{ app.version }}
            </span>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page: pageData = usePage();
const app = computed(() => page.props.app);
</script>

<style scoped lang="scss">
@import '../../../scss/Layouts/appLayout.scss';
.footer {
    background-color: $footer-background;
    border-top: $soft-border;
    bottom: 0;
    position: absolute;
    width: 100%;
    @media (min-width: $brk-md) {
        padding: 25px;
    }
    @media (max-width: $brk-md) {
        padding: 10px;
    }
}
</style>
