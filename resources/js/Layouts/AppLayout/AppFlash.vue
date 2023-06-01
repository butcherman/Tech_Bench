<template>
    <Teleport to="body">
        <div class="toast-container p-3">
            <TransitionGroup @enter="onEnter" @leave="onLeave">
                <div
                    v-for="alert in flashAlerts"
                    :key="alert.id"
                    class="toast align-items-center fade show"
                >
                    <div
                        class="d-flex"
                        :class="{
                            'text-bg-success': alert.type === 'success',
                            'text-bg-warning': alert.type === 'warning',
                            'text-bg-danger': alert.type === 'danger',
                            'text-bg-info': alert.type === 'info',
                        }"
                    >
                        <div class="toast-body w-100">
                            <div class="row m-0 p-0">
                                <div class="col-2">
                                    <span class="badge p-0 pt-1 m-0">
                                        <fa-icon
                                            :icon="getAlertIcon(alert.type)"
                                            class="float-start"
                                        />
                                    </span>
                                </div>
                                <div class="col text-center">
                                    {{ alert.message }}
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="btn-close me-2 m-auto"
                            @click="removeAlert(alert.id)"
                        ></button>
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
const onEnter = (el:HTMLElement) => {
    gsap.to(el, {
        x: 200,
    });
};

const onLeave = (el:HTMLElement) => {
    gsap.from(el, {
        opacity: 0,
        delay: 0.5,
    });
};
</script>

<style scoped lang="scss">
.toast-container {
    position: fixed;
    top: 10px;
    left: -150px;
    .toast {
        border-radius: 10px;
        overflow: hidden;
        line-height: 1.2em;
        font-size: 1.2em;
        .badge {
            font-size: 1em;
        }
    }
}
</style>
