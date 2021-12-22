<template>
    <b-button type="submit" block :variant="button_variant" :disabled="button.disable">
        <span class="spinner-border spinner-border-sm text-danger" v-show="submitted"></span>
        {{button.text}}
    </b-button>
</template>

<script>
    export default {
        props: {
            button_text: {
                type:     String,
                default: 'Submit',
            },
            button_disable: {
                type:    Boolean,
                default: false,
            },
            submitted: {
                type:    Boolean,
                default: false,
            },
            button_variant: {
                type:     String,
                default: 'primary',
            }
        },
        data: function () {
            return {
                button: {
                    text:    this.button_text,
                    disable: this.button_disable,
                }
            }
        },
        watch: {
            submitted()
            {
                if(this.submitted)
                {
                    this.button.text    = "Loading...";
                    this.button.disable = true;
                }
                else
                {
                    this.button.text    = this.button_text;
                    this.button.disable = false;
                }
            },
            button_text()
            {
                this.button.text = this.button_text;
            },
            button_disable()
            {
                this.button.disable = this.button_disable;
            }
        }
    }
</script>
