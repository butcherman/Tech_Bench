<template>
    <div>
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <span
                    class="nav-link pointer"
                    :class="{ active: active === 'cust' }"
                    @click="showCust"
                >
                    Customers
                </span>
            </li>
            <li class="nav-item">
                <span
                    class="nav-link pointer"
                    :class="{ active: active === 'tips' }"
                    @click="showTips"
                >
                    Tech Tips
                </span>
            </li>
        </ul>
        <div ref="custRef">
            <h5 v-if="!customers.length" class="text-center">
                Nothing to see here...
            </h5>
        </div>
        <div ref="tipsRef" style="display: none; opacity: 0">
            <h5 v-if="!techTips.length" class="text-center">
                Nothing to see here...
            </h5>
            <ul class="list-group">
                <li v-for="tip in techTips" class="list-group-item quick-link">
                    <Link :href="$route('tech-tips.show', tip.slug)">
                        {{ tip.subject }}
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { gsap } from "gsap";

defineProps<{
    techTips: techTip[];
    customers: customer[];
}>();

const active = ref("cust");
const custRef = ref(null);
const tipsRef = ref(null);

const showTips = () => {
    active.value = "tips";
    let timeline = gsap.timeline();

    timeline
        .to(custRef.value, {
            opacity: 0,
            display: "none",
            duration: 0.5,
        })
        .to(tipsRef.value, {
            opacity: 1,
            display: "block",
            duration: 0.5,
        });
};

const showCust = () => {
    active.value = "cust";
    let timeline = gsap.timeline();

    timeline
        .to(tipsRef.value, {
            opacity: 0,
            display: "none",
            duration: 0.5,
        })
        .to(custRef.value, {
            opacity: 1,
            display: "block",
            duration: 0.5,
        });
};
</script>

<style scoped lang="scss">
.quick-link {
    padding: 0;
    a {
        display: block;
        padding: 0.5rem;
        text-decoration: none;
        color: #3a3838;
        &:hover {
            background-color: #ddd5d5;
        }
    }
}
</style>
