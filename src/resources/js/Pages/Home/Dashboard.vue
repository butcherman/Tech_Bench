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
                            href="/logout"
                            as="button"
                            class="btn btn-danger w-100"
                            >Logout</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
