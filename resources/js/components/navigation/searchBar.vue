<template>
    <div class="">
        <div
            class="relative rounded-full gradient-green-blue shadow-inner flex items-center p-0.5 z-10 h-8 md:h-10 custom-animate"
            :class="{ 'w-8 md:w-10': !animate, ' w-72 md:w-96': animate }">
            <button
                v-if="!remove && search === ''"
                type="submit"
                @click.prevent="showOrHide()">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:h-10 md:w-10 border text-white rounded-full p-1.5 font-bold absolute -left-0 top-2/4 -mt-4 md:-mt-5 cursor-pointer gradient-green-blue"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            <button
                v-else-if="!remove && search !== ''"
                type="submit "
                @click.prevent="searchResults()">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:h-10 md:w-10 border text-white rounded-full p-1.5 font-bold absolute left-0 top-2/4 -mt-4 md:-mt-5 cursor-pointer bg-green-400 border-green-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            <button v-else-if="showInput && remove" type="submit">
                <svg
                    @click.prevent="clear()"
                    class="h-8 w-8 md:h-10 md:w-10 gradient-red-orange text-white rounded-full cursor-pointer p-2 font-semibold absolute -left-0.5 top-2/4 -mt-4 md:-mt-5 transform transition duration-500 hover:scale-110"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <input
                v-if="showInput"
                @keypress.enter="searchResults()"
                class="pl-12 w-full h-full border-none text-base rounded-full focus:ring-0"
                v-model="search"
                type="search"
                :placeholder="placeHolder" />
        </div>
    </div>
</template>
<script>
export default {
    data: function () {
        return {
            showInput: false,
            search: '',
            animate: false,
            remove: false,
            placeHolder: '',
        }
    },
    methods: {
        showOrHide() {
            if (this.showInput && this.search === '') {
                this.showInput = false
                this.animate = false
            } else {
                this.showInput = true
                this.animate = true
                setTimeout(() => {
                    this.writeAnimation()
                }, 1500)
            }
        },

        searchResults() {
            this.remove = true
            this.$root.$emit('search', this.search)
        },
        clear() {
            this.remove = false
            this.search = ''
            this.placeHolder = ''
            this.$nextTick(() => {
                this.$root.$emit('search', this.search)
            })
        },
        writeAnimation() {
            if (!this.placeHolder.length) {
                let text = this.$t('nav.searchBar.searchPlaceholder')
                text = text.split('')
                for (let i = 0; i < text.length; i++) {
                    setTimeout(() => {
                        this.placeHolder += text[i]
                    }, i * 50)
                }
            }
        },
    },
    mounted() {
        this.$root.$on('resetSearchBar', () => {
            this.remove = false
            this.search = ''
            this.placeHolder = ''
        })
    },
}
</script>

<style lang="scss" scoped>
.custom-w-22rem {
    width: 22rem;
}
.custom-animate {
    transition: width 0.5s;
}
</style>
