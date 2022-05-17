<template>
    <div class="flex flex-shrink-0 items-center">
        <div
            @click="checkerHandle()"
            class="w-12 h-7 rounded-full relative transition-colors duration-500"
            :class="{ 'bg-gray-400': !checked, 'bg-green-300': checked }">
            <span
                class="absolute h-7 w-7 rounded-full transition-all duration-200"
                :class="{
                    'bg-gray-100 left-0': !checked,
                    'bg-green-500 right-0': checked,
                }">
            </span>
        </div>
        <span class="text-lg font-semibold text-gray-500 ml-5">
            {{ message }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        checkedText: {
            type: String,
            default: '',
        },
        check: {
            type: Boolean,
        },
        uncheckedText: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            checked: false,
            message: null,
        }
    },
    methods: {
        checkerHandle() {
            switch (this.checked) {
                case true:
                    this.message = this.uncheckedText
                    this.checked = false
                    this.$emit('unchecked', 'uncheck')
                    break

                case false:
                    this.message = this.checkedText
                    this.checked = true
                    this.$emit('checked', 'check')
                    break
            }
        },
    },
    mounted() {
        if (this.check) {
            this.checked = true
            this.message = this.checkedText
            this.checkerHandle()
        } else this.checkerHandle()
    },
}
</script>
