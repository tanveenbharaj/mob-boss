<template>
    <div class="styled-checkbox">
        <input
            class="styled-checkbox-input"
            :id="name"
            :name="name"
            :value="value"
            :checked="value"
            type="checkbox"
            @click="checked($event.target.checked)">

            <label :for="name">
                <slot></slot>
            </label>
    </div>
</template>

<script>

    export default {
        props: {
            value: {
                required: true
            } ,
            name: {
                type: String,
                required: true
            }
        },
        methods: {
            checked(value) {
                this.$emit('input', value);
            },
        },
    }
</script>
