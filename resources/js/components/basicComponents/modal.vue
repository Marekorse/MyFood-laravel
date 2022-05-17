<template>
    <div
        class="flex items-center justify-center fixed left-0 top-0 w-screen h-screen z-50">
        <div
            @click="hideModal()"
            class="fixed left-0 top-0 w-full h-full bg-black opacity-80 z-50"></div>
        <transition name="fade">
            <slot v-if="fade"></slot>
        </transition>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            fade: false,
        }
    },
    methods: {
        hideModal() {
            this.$emit('closeModal')
        },
    },
    created() {
        document.body.style.overflow = 'hidden'
        setTimeout(() => {
            this.fade = true
            this.$nextTick(
                () => (this.$slots.default[0].elm.style.zIndex = 999)
            )
        }, 0.6)
    },
    destroyed() {
        document.body.style.overflow = ''
    },
}
</script>

<style lang="scss">
.custom-z-999 {
    z-index: 999;
}
</style>
