<template>
    <div class="fixed h-14 md:h-16 w-full px-4 sm:px-6 bg-white z-30">
        <nav
            class="w-full md:w-10/12 h-14 md:h-16 top-0 mx-auto flex items-center relative"
            :class="{
                'justify-between': searchAllowed,
                'justify-end': !searchAllowed,
            }">
            <div class="flex items-center" v-if="searchAllowed">
                <category-bar :width="width" class="mr-1"></category-bar>
                <search-bar></search-bar>
            </div>
            <div
                class="absolute w-32 md:w-48 md:botom-4 left-2/4 -ml-16 md:-ml-24">
                <div
                    class="container relative overflow-hidden bg-white w-32 h-20 md:h-32 md:w-52 border-b-4 border-secondary-color">
                    <main-logo
                        link
                        class="absolute w-32 md:w-48 bottom-3 md:bottom-1 left-2/4 -ml-16 md:-ml-24" />
                </div>
            </div>
            <navigation-menu :width="width" :user="user"></navigation-menu>
        </nav>
        <div
            class="w-full h-0.5 rounded-2xl bg-gradient-to-r from-transparent via-green-300 to-transparent"></div>
    </div>
</template>

<script>
import categoryBar from './categoryBar.vue'
import mainLogo from './logo.vue'
import searchBar from './searchBar.vue'
import navigationMenu from './navigationMenu.vue'
export default {
    props: {
        searchAllowed: Boolean,
        user: [Object, Number],
    },
    components: {
        mainLogo,
        searchBar,
        categoryBar,
        navigationMenu,
    },
    data: function () {
        return {
            width: window.innerWidth,
            height: window.innerHeight,
        }
    },

    created() {
        window.addEventListener('resize', this.onResize)
    },
    destroyed() {
        window.removeEventListener('resize', this.onResize)
    },
    methods: {
        onResize(e) {
            this.width = window.innerWidth
            this.height = window.innerHeight
        },
    },
}
</script>

<style lang="scss">
.container {
    border-bottom-left-radius: 50% 40%;
    border-bottom-right-radius: 50% 40%;
}
.negative-margin-logo {
    margin-left: -4.5rem;
}

@media (min-width: 768px) {
    .negative-margin-logo {
        margin-left: -6rem;
    }
}
</style>
