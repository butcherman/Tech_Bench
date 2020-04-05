<template>
    <b-modal id="loading-modal" size="sm" ref="loadingModal" hide-footer hide-header hide-backdrop centered>
        <atom-spinner
            :animation-duration="1000"
            :size="60"
            color="#ff1d5e"
            class="mx-auto"
        />
    </b-modal>
</template>

<script>
    export default {
        props: [

        ],
        data() {
            return {

            }
        },
        created()
        {

        },
        methods:
        {
            show()
            {
                console.log('shown');
            }
        }
    }
</script>
