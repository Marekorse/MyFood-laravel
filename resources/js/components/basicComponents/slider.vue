<template>
    <div class="list-none relative flex w-full flex-wrap justify-start">
        <div
            v-if="offsetLeft != 0 && overflow"
            class="absolute left-0 flex items-center justify-center w-10 h-full bg-gradient-to-l from-transparent to-white z-10">
            <svg
                @click="slideLeft(2)"
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 rounded-full p-1 bg-indigo-600 text-white"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </div>
        <span
            ref="slider"
            draggable="false"
            v-bind:style="{ gap: gap + 'px' }"
            id="slider-element"
            class="flex flex-row overflow-x-auto hide-scrollbar">
            <slot> </slot>
        </span>
        <div
            v-if="scrollWidth - offsetRight != 0 && overflow"
            class="absolute right-0 flex items-center justify-center w-10 h-full bg-gradient-to-r from-transparent to-white z-10">
            <svg
                @click="slideRight()"
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 rounded-full p-1 bg-indigo-600 text-white"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        gap: {
            type: Number,
            default: 8,
        },
    },
    data: function () {
        return {
            overflow: false,
            scrollWidth: null,
            offsetLeft: 0,
            offsetRight: null,
            nodeWidth: null,
            isDown: false,
            startX: null,
            isDown: false,
            scrollLeft: null,
        }
    },
    methods: {
        mouseMoveScroll(e) {
            const targetNode = this.$refs.slider
            if (e.pageX > this.oldx) {
                e.preventDefault()
                const x = e.pageX - targetNode.offsetLeft
                const walk = (x - startX) * 3 //scroll-fast
                targetNode.scrollLeft = scrollLeft - walk

                console.log('From Right')
            } else if (e.pageX < this.oldx) {
                targetNode.scrollTo({
                    top: 0,
                    left: e.pageX - this.oldx,
                })

                console.log('From Left')
            }
            this.oldx = e.pageX
        },
        checkOverflow(elem) {
            const elemWidth = elem.clientWidth
            const parentWidth = this.scrollWidth

            return elemWidth < parentWidth
        },
        slideRight() {
            const targetNode = this.$refs.slider
            this.offsetLeft = targetNode.scrollTo({
                top: 0,
                left: (this.offsetLeft =
                    this.offsetLeft + this.nodeWidth * 2 + this.gap),
                behavior: 'smooth',
            })
        },
        slideLeft() {
            const targetNode = this.$refs.slider
            this.offsetLeft = targetNode.scrollTo({
                top: 0,
                left: (this.offsetLeft =
                    this.offsetLeft - this.nodeWidth * 2 + this.gap),
                behavior: 'smooth',
            })
        },
        calcOffset() {
            const targetNode = this.$refs.slider
            this.offsetLeft = targetNode.scrollLeft
            this.offsetRight = targetNode.scrollLeft + targetNode.offsetWidth
        },
        setEventListener() {
            const targetNode = this.$refs.slider
            targetNode.addEventListener('mousemove', this.mouseMoveScroll)
        },
        removeEventListener() {
            const targetNode = this.$refs.slider
            targetNode.removeEventListener('mousemove', this.mouseMoveScroll)
        },
    },
    mounted() {
        const targetNode = this.$refs.slider

        targetNode.addEventListener('scroll', this.calcOffset)

        targetNode.addEventListener('mousedown', (e) => {
            this.isDown = true
            this.startX = e.pageX - targetNode.offsetLeft
            this.scrollLeft = targetNode.scrollLeft
        })
        targetNode.addEventListener('mouseleave', () => {
            this.isDown = false
        })
        targetNode.addEventListener('mouseup', () => {
            this.isDown = false
        })
        targetNode.addEventListener('mousemove', (e) => {
            if (!this.isDown) return
            e.preventDefault()
            const x = e.pageX - targetNode.offsetLeft
            const walk = x - this.startX //scroll-fast
            targetNode.scrollLeft = this.scrollLeft - walk
        })

        const config = { attributes: true, childList: true, subtree: true }
        const callback = (mutationsList, observer) => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    this.overflow = this.checkOverflow(targetNode)
                    this.offsetLeft = targetNode.offsetLeft
                    this.nodeWidth = targetNode.firstChild.offsetWidth
                    this.scrollWidth = targetNode.scrollWidth
                    targetNode.scrollLeft = 0
                }
            }
        }

        const observer = new MutationObserver(callback)

        observer.observe(targetNode, config)
    },

    beforeDestroy() {
        const targetNode = document.getElementById('slider-element')
        document.removeEventListener('scroll')
        document.removeEventListener('mousedown')
        this.observer.disconnect()
    },
}
</script>

<style lang="scss" scoped></style>
