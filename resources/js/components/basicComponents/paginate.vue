<template>
    <div
        v-if="lastPage > 1"
        class="flex justify-center items-center text-base font-semibold text-gray-600 py-2 my-2 list-none">
        <li
            v-if="prevPageUrl"
            @click="paginate(prevPageUrl.substring(prevPageUrl.length - 1))">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 font-semibold text-gray-600 cursor-pointer"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </li>
        <div
            class="flex rounded-full font-semibold"
            :class="{
                'h-7': size === 'medium',
                'h-4': size === 'small',
                'h-12': size === 'large',
            }"
            v-for="number in pages"
            :key="number">
            <li
                @click="paginate(number)"
                :class="{
                    'text-white gradient-green-blue': number === currentPage,
                    'w-7': size === 'medium',
                    'w-4': size === 'small',
                    'w-12': size === 'large',
                }"
                class="flex justify-center items-center cursor-pointer leading-5 transition duration-150 ease-in rounded-full font-semibold">
                {{ number }}
            </li>
        </div>
        <li
            v-if="lastPage != currentPage"
            @click="paginate(nextPageUrl.substring(nextPageUrl.length - 1))">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 font-semibold text-gray-600 cursor-pointer"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7" />
            </svg>
        </li>
    </div>
</template>

<script>
export default {
    props: [
        'last-page',
        'prev-page-url',
        'next-page-url',
        'current-page',
        'size',
    ],
    data() {
        return {
            pages: null,
        }
    },

    methods: {
        paginate(page) {
            this.$emit('paginated', page)
            this.pages = this.PagesRange(page, this.lastPage)
        },
        PagesRange(currentPage, lastPage) {
            let range = []
            let start = 1
            let stop = 10

            if (currentPage > 5) {
                start = currentPage - 5
            }
            if (lastPage < 10) {
                stop = lastPage
            }
            if (
                lastPage > 10 &&
                currentPage > 5 &&
                currentPage + 5 < lastPage
            ) {
                stop = currentPage + 5
            }
            if (
                lastPage > 10 &&
                currentPage > 5 &&
                currentPage + 5 > lastPage
            ) {
                start = lastPage - 10
                stop = lastPage
            }

            for (let index = start; index <= stop; index++) {
                range.push(index)
            }
            return range
        },
    },
    mounted() {
        this.pages = this.PagesRange(this.currentPage, this.lastPage)
    },
}
</script>
