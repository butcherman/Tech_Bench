<template>
    <div>
        <h4 class="text-center">{{text}}</h4>
        <b-progress :value="percentDone" show-progress animated></b-progress>
    </div>
</template>

<script>
    import { HollowDotsSpinner } from 'epic-spinners'

    export default {
        components: { HollowDotsSpinner },
        props: {
            percentDone: {
                type: Number,
                required: true,
            },
            text: {
                type: String,
                required: false,
                default: 'Upload Progress...',
            }
        },
    }
</script>
