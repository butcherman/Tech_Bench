<template>
    <div>
        <atom-spinner
            :animation-duration="1000"
            :size="60"
            color="#ff1d5e"
            class="mx-auto"
        />
        <h4 class="text-center">{{text}}</h4>
    </div>
</template>

<script>
    import {AtomSpinner} from 'epic-spinners';
    export default {
        components: { AtomSpinner },
        props: {
            text: {
                type:     String,
                required: false,
                default: 'Loading...',
            }
        },
    }
</script>
