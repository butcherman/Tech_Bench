<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import prettyMilliseconds from "pretty-ms";
import verifyModal from "@/core/features/verifyModal";
import { computed, onMounted, onUnmounted, ref } from "vue";
import { reboot } from "@/wayfinder/routes/maint/status";
import { usePoll, router } from "@inertiajs/vue3";

const props = defineProps<{
    summary: ContainerSummary[];
}>();

const now = ref(Date.now());
let timer: ReturnType<typeof setInterval>;

/**
 * Poll the server for new status on the Docker images
 */
usePoll(15000, {
    only: ["summary"],
});

onMounted(() => {
    timer = setInterval(() => {
        now.value = Date.now();
    }, 1000);
});

onUnmounted(() => {
    clearInterval(timer);
});

/**
 * Number of containers that have reported as "healthy"
 */
const healthyContainers = computed(
    () =>
        Object.values(props.summary).filter(
            (containter) => containter.health === "healthy",
        ).length,
);

/**
 * Background color based on how many containers are reported as "healthy"
 */
const getHealthyClass = computed(() => {
    if (healthyContainers.value === 8) {
        return "bg-green-300/40";
    }

    if (healthyContainers.value > 5) {
        return "bg-yellow-300/40";
    }

    return "bg-red-300/40";
});

/**
 * Text to show in container status badge.
 */
const getStatusText = (container: ContainerSummary): string => {
    if (container.health && container.health === "starting") {
        return "starting";
    }

    return container.status;
};

/**
 * Background Color of container status badge
 */
const getStatusVariant = (container: ContainerSummary): string => {
    if (container.health && container.health === "starting") {
        return "bg-yellow-300/40";
    }

    return {
        created: "bg-purple-300/40",
        dead: "bg-red-500/40",
        exited: "bg-orange-300/40",
        paused: "bg-yellow-300/40",
        removing: "bg-violet-300/40",
        restarting: "bg-emerald-300/40",
        running: "bg-green-300/40",
    }[container.status];
};

/**
 * Background Color of dot next to status badge
 */
const getHealthVariant = (health?: ContainerHealth): string => {
    if (health) {
        return {
            healthy: "bg-green-500",
            unhealthy: "bg-red-500",
            starting: "bg-orange-500",
        }[health];
    }

    return "bg-orange-500";
};

/**
 * Calculate the uptime of a container
 */
const getUptime = (startedAt: string): string => {
    const started = new Date(startedAt).getTime();
    const seconds = Math.max(0, Math.floor(Date.now() - started));

    return prettyMilliseconds(seconds);
};

const restartContainer = (container: ContainerSummary): void => {
    verifyModal("This will cause service disruption").then((res) => {
        if (res) {
            router.post(reboot.url(container.service));
        }
    });
};
</script>

<template>
    <Card>
        <template #title>
            <div class="flex gap-2">
                <div class="flex items-center">
                    <h1>
                        <fa-icon icon="fa-server" />
                    </h1>
                </div>
                <div class="grow">
                    <h3 class="text-black">Service Status</h3>
                    <p class="text-muted">
                        Montior and manage application services
                    </p>
                </div>
                <div class="flex items-center">
                    <BaseBadge
                        :text="`${healthyContainers} / ${summary.length} Healthy`"
                        :class="getHealthyClass"
                        variant="none"
                        circle
                    />
                </div>
            </div>
        </template>
        <div class="grid grid-cols-3">
            <div class="bg-slate-300/40 p-1 text-muted">Service</div>
            <div class="bg-slate-300/40 p-1 text-muted">Status</div>
            <div class="bg-slate-300/40 p-1 text-muted text-end pe-5">
                Action
            </div>

            <template v-for="container in summary" :key="container.service">
                <div class="py-0.5 px-1 border-t-slate-300 border-t">
                    <h5>{{ container.name }}</h5>
                    <p class="text-muted text-sm">
                        {{ container.description }}
                    </p>
                </div>
                <div class="py-0.5 px-1 border-t-slate-300 border-t">
                    <div class="flex items-center gap-1">
                        <div
                            class="h-3 w-3 rounded-full"
                            :class="getHealthVariant(container.health)"
                            v-tooltip="container.health ?? 'unknown'"
                        />
                        <BaseBadge
                            :text="getStatusText(container)"
                            circle
                            variant="none"
                            class="uppercase"
                            :class="getStatusVariant(container)"
                        />
                    </div>
                    <p>Uptime: {{ getUptime(container.started_at) }}</p>
                </div>
                <div class="py-0.5 px-1 border-t-slate-300 border-t">
                    <div class="flex items-center justify-end h-full">
                        <BaseButton
                            class="text-muted"
                            text="Restart"
                            icon="arrows-rotate"
                            size="sm"
                            variant="none"
                            :disabled="!container.restartable"
                            :class="{
                                'bg-slate-300/40': !container.restartable,
                            }"
                            @click="restartContainer(container)"
                        />
                    </div>
                </div>
            </template>
        </div>
    </Card>
</template>
