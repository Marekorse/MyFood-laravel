<template>
    <button
        @click="event()"
        :class="{
            'bg-gray-300 text-gray-600': disabled,
            'gradient-green-blue text-white transform transition-all duration-300 hover:scale-105':
                !disabled,
        }"
        class="px-8 rounded-full py-0.5"
        :disabled="disabled">
        <a v-if="!disabled && link" :href="link">
            <slot />
        </a>
        <span v-else>
            <slot />
        </span>
    </button>
</template>

<script>
export default {
    methods: {
        event() {
            if (!this.disabled && !this.link) {
                console.log('emitol sa click')
                this.$emit('clickEvent')
            }
        },
    },
    props: {
        disabled: {
            type: Boolean,
            default: false,
        },
        link: {
            type: String,
            default: null,
        },
    },
}
</script>
