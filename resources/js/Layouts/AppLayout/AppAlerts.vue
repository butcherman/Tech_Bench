<template>
    <div class="alert-wrapper">
        <div v-if="Object.keys(errors).length > 0" class="alert alert-danger text-center">
            <div v-for="(err, name) in errors">
                <span v-if="name == 'link'">
                    <Link :href="err">More Information</Link>
                </span>
                <span v-else>
                    {{ err }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page: pageData = usePage();
const errors = computed(() => page.props.errors);
</script>
