<template>
    <div class="relative flex items-center">
        <div
            @click="show()"
            id="dropdown-base-element"
            v-if="withArrow"
            class="flex items-center cursor-pointer">
            <slot name="text"></slot>

            <svg
                v-if="!showDropdown"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 cursor-pointer ml-2"
                viewBox="0 0 20 20"
                fill="currentColor">
                <path
                    fill-rule="evenodd"
                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                    clip-rule="evenodd" />
            </svg>
            <svg
                v-if="showDropdown"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 align-middle cursor-pointer ml-2"
                viewBox="0 0 20 20"
                fill="currentColor">
                <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div
            id="dropdown-base-element"
            @mouseenter="onEnter()"
            @click="show()"
            v-if="onClick"
            class="flex items-center cursor-pointer">
            <slot name="text"></slot>
        </div>
        <transition :name="slide">
            <div
                v-if="showDropdown && !fixed"
                @mouseleave="onLeave()"
                @click="hide()"
                :class="{
                    'right-0': align === 'right',
                    'left-0': align === 'left',
                }"
                :style="{ top: element.height + 'px' }"
                class="absolute z-40">
                <div
                    v-if="hover"
                    :class="{
                        'right-0': align === 'right',
                        'left-0': align === 'left',
                    }"
                    :style="{
                        width: element.width + 'px',
                        height: element.height + 'px',
                        top: '-' + element.height + 'px',
                    }"
                    class="bg-transparent cursor-pointer absolute"></div>
                <slot name="dropDown"></slot>
            </div>
            <div
                v-if="showDropdown && fixed"
                @click="hide()"
                :class="{
                    'right-0': align === 'right',
                    'left-0': align === 'left',
                }"
                class="fixed top-0 z-40">
                <slot name="dropDown"></slot>
            </div>
        </transition>
        <div
            v-if="showDropdown"
            @mouseenter="onLeave()"
            @click="showDropdown = false"
            class="fixed h-screen w-screen left-0 top-0 z-20 bg-transparent"></div>
    </div>
</template>

<script>
export default {
    props: {
        fixed: { type: Boolean, default: false },
        hover: { type: Boolean, default: false },
        align: { type: String, default: 'left' },
        hideOnClick: Boolean,
        onClick: Boolean,
        withArrow: Boolean,
    },
    data: function () {
        return {
            slide: '',
            showDropdown: false,
            element: { width: null, height: null },
        }
    },
    methods: {
        onEnter() {
            if (this.hover) {
                this.showDropdown = true
            }
        },
        onLeave() {
            if (this.hover) {
                this.showDropdown = false
            }
        },
        show() {
            this.showDropdown = true
        },
        hide() {
            if (this.hideOnClick) {
                this.showDropdown = false
            }
        },
    },
    mounted() {
        if (this.align === 'left') {
            this.slide = 'slide-left'
        } else if (this.align === 'right') {
            this.slide = 'slide-right'
        }
        this.element.width = document.getElementById(
            'dropdown-base-element'
        ).offsetWidth
        this.element.height = document.getElementById(
            'dropdown-base-element'
        ).offsetHeight
    },
}
</script>

<style lang="scss" scoped>
.slide-left-enter-active,
.slide-right-enter-active {
    transition: all 0.3s ease;
}
.slide-left-leave-active,
.slide-right-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-right-enter, .slide-right-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
}
.slide-left-enter, .slide-left-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(-10px);
    opacity: 0;
}
</style>
