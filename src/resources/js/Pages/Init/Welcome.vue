<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Welcome to the Tech Bench</div>
                    <p class="text-center">
                        Looks like this is your first time here.
                    </p>
                    <p class="text-center">
                        Before you can start using the Tech Bench, we need to do
                        some basic setup first.
                    </p>
                    <p class="text-center">
                        <Link
                            :href="$route('init.step-1')"
                            class="btn btn-primary mt-4"
                        >
                            Setup Tech Bench
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
export default { layout: InitLayout };
</script>
