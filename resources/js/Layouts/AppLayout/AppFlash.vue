<template>
    <Teleport to="body">
        <div class="toast-container translate-middle-x fade show p-3 w-75">
            <TransitionGroup @enter="onEnter" @leave="onLeave">
                <div
                    v-for="alert in flashAlerts"
                    :key="alert.id"
                    class="toast align-items-center w-100"
                >
                    <div
                        class="toast-body text-center"
                        :class="{
                            'text-bg-success': alert.type === 'success',
                            'text-bg-warning': alert.type === 'warning',
                            'text-bg-danger': alert.type === 'danger',
                            'text-bg-info': alert.type === 'info',
                            'text-bg-primary': alert.type === 'status',
                        }"
                    >
                        <div class="row align-items-center m-0 p-0">
                            <div class="col-1 pe-4">
                                <fa-icon
                                    :icon="getAlertIcon(alert.type)"
                                    class="little-larger-text"
                                />
                            </div>
                            <div class="col">
                                {{ alert.message }}
                            </div>
                            <div class="col-1 ps-4">
                                <button
                                    type="button"
                                    class="btn-close float-end"
                                    @click="removeAlert(alert.id)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { watch, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { flashAlerts, pushAlert, removeAlert } from "@/State/LayoutState";
import { gsap } from "gsap";

const page: pageData = usePage();
const flash = computed(() => page.props.flash);

watch(flash, (newFlash) => {
    for (const [type, message] of Object.entries(newFlash)) {
        if (message !== null) {
            pushAlert(type, message);
        }
    }
});

const getAlertIcon = (type: string) => {
    switch (type) {
        case "success":
            return "circle-check";
        case "warning":
            return "triangle-exclamation";
        case "danger":
            return "exclamation-circle";
        default:
            return "circle-info";
    }
};

/**
 * Animations
 */
const onEnter = (el: Element) => {
    gsap.from(el, {
        x: -2000,
        ease: "back.out",
        duration: 0.5,
    });
};

const onLeave = (el: Element) => {
    gsap.to(el, {
        x: 2000,
        ease: "back.in",
    });
};
</script>

<style scoped lang="scss">
.toast-container {
    position: fixed;
    top: 10px;
    left: 50%;
    .toast {
        border-radius: 10px;
        overflow: hidden;
        line-height: 1.2em;
        font-size: 1.2em;
        display: block;
        .badge {
            font-size: 1em;
        }

        .toast-body {
            border-left: 10px solid #0b590b;
            .little-larger-text {
                font-size: 1.8rem;
            }
        }
    }
}
</style>
