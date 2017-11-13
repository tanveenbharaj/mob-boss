<template>
    <div
        class="btn-group custom-btn-rank"
        role="group"
        @mouseover="show = 'all'"
        @mouseleave="show = 'selected'">

        <div
            class="btn-group-vertical vertical-holder"
            :style="boxPosition">
            <button
                v-for="(btn, index) in  buttons"
                type="button"
                :key="btn.value"
                @click=btnPress(btn)
                class="btn btn-xs"
                :class="[
                    btn.className,
                    {
                        invisable: show != 'all'
                    }
                ]"
                ref="dropDownButtons">
                    <i v-for="icon in  btn.value" class="fa fa-exclamation"></i>
            </button>
        </div>

        <button
            type="button"
            class="btn btn-xs selected-button"
            :class="selectedButton.className"
            data-toggle="dropdown">
            <i v-for="icon in  selectedButton.value" class="fa fa-exclamation"></i>
        </button>

    </div>
</template>

<script>

    export default {
        props: ['value'],
        data () {
            return {
                show: 'selected',
                height: 21,
                buttons: [
                    {
                        value: 1,
                        className: 'btn-success',
                        selected: false
                    },
                    {
                        value: 2,
                        className: 'btn-info',
                        selected: true
                    },
                    {
                        value: 3,
                        className: 'btn-warning',
                        selected: false
                    },
                    {
                        value: 4,
                        className: 'btn-danger',
                        selected: false
                    }
                ]
            }
        },
        computed: {
            selectedButton () {
                return this.buttons.filter(button => button.selected).pop()
            },
            selectedIndex () {
                return this.buttons.findIndex(button => button.selected )
            },
            boxPosition () {
                return 'top: ' + this.height * (this.selectedIndex * -1) + 'px'
            },
        },
        methods: {
            btnPress(btn) {
                // this.height = this.$refs.dropDownButtons[0].offsetHeight
                this.height = this.$refs.dropDownButtons[0].clientHeight + this.$refs.dropDownButtons[0].clientTop

                this.buttons.forEach((button) => button.selected = false)

                btn.selected = true
                this.$emit('input', btn.value)
            },
        },
        mounted() {
            if(this.value >= 1 && this.value <= 4) {
                this.buttons.forEach((button) => button.selected = false)
                this.buttons[(this.value -1)].selected = true
            }
        }
    }
</script>
