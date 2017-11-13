<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header" v-if="$slots['header']">
                        <slot name="header"></slot>
                    </div>

                    <div class="modal-body">
                        <slot></slot>
                    </div>

                    <div class="modal-footer" v-if="buttonType == 'yesNo'">
                        <button class='btn btn-default btn-sm' @click="returnFalse">No</button>
                        <button class='btn btn-primary btn-sm' @click="returnTrue">Yes</button>
                    </div>
                    <div class="modal-footer" v-else>
                        <button class='btn btn-default btn-sm' @click="returnFalse">Cancel</button>
                        <button class='btn btn-primary btn-sm' @click="returnTrue">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>

    export default {
        props: {
            value: "",
            buttonType: {
                default: 'standard'
            }
        },
        data() {
            return {
                manualShow: true,
            }
        },
        methods: {

            returnFalse () {
                this.display = false
                this.$emit('confirm', false);
            },
            returnTrue () {
                this.display = false
                this.$emit('confirm', true);
            }
        }

    }

</script>