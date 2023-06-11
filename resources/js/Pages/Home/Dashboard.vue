<template>
    <div>
        <div class="row">
            <div class="col-12">
                <h4 class="text-center text-md-left">Hello World</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">This is a new page</p>
                        <Link
                            as="button"
                            href="/logout"
                            method="POST"
                            class="btn btn-block btn-info w-100"
                            >Logout</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
// import { ref, reactive, onMounted } from 'vue';

// const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
