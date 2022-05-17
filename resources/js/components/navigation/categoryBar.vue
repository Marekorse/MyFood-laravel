<template>
    <div>
        <dropdown onClick :fixed="width < 1230" align="left">
            <template v-slot:text>
                <svg
                    v-if="!isActive"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:w-10 md:h-10 relative gradient-green-blue text-white p-1.5 font-bold rounded-full shadow-inner border-green-400 flex items-center custom z-10"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <svg
                    v-if="isActive"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:w-10 md:h-10 relative gradient-red-orange text-white p-1.5 font-bold rounded-full shadow-inner border-green-400 flex items-center custom z-10"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </template>
            <template v-slot:dropDown>
                <div
                    class="overflow-y-auto w-72 bg-gray-100 rounded-2xl shadow-md overflow-x-hidden"
                    :class="{
                        ' h-screen my-2': width < 1230,
                        ' md:max-h-96': width > 1230,
                    }">
                    <h4
                        class="w-full custom-color-logoGray font-corinthia text-4xl font font-semibold text-center text-green-500">
                        {{ $t('nav.categoryBar.title') }}
                    </h4>
                    <div
                        class="w-full h-0.5 rounded-2xl bg-gradient-to-r from-transparent via-green-300 to-transparent"></div>
                    <ul
                        class="list-none inline-flex items-center flex-wrap p-3">
                        <li
                            class="font-semibold shadow-md px-5 m-1 text-sm rounded-md cursor-pointer"
                            :class="{
                                'bg-gradient-to-tr from-green-300 via-green-400 to-green-500 text-white':
                                    category.selected,
                                'bg-white text-gray-500': !category.selected,
                            }"
                            @click.prevent="setCategory(category)"
                            v-for="category in categories"
                            :key="category.id">
                            {{ category.name }}
                        </li>
                    </ul>
                    <div class="w-full flex justify-center">
                        <button
                            v-if="!isActive"
                            class="px-10 py-1 my-2 bg-blue-500 text-white rounded-full font-sm"
                            @click.prevent="search()"
                            type="submit">
                            {{ $t('nav.categoryBar.search') }}
                        </button>
                        <button
                            v-if="isActive"
                            class="px-10 py-1 my-2 gradient-red-orange text-white rounded-full font-sm"
                            @click.prevent="clear()"
                            type="submit">
                            {{ $t('global.cancel') }}
                        </button>
                    </div>
                </div>
            </template>
        </dropdown>
    </div>
</template>

<script>
import dropdown from '../basicComponents/dropdown.vue'
export default {
    props: { width: Number },
    components: { dropdown },
    data: function () {
        return {
            isActive: false,
            categories: [],
        }
    },
    methods: {
        emitCategories(categories) {
            this.$root.$emit('setCategory', this.filterselected(categories))
            this.$root.$emit('clearLeaderboard')
        },
        setCategory(selectedCategory) {
            this.categories.map((category) => {
                if (category.id === selectedCategory.id) {
                    category.selected = category.selected != true
                }
            })

            let isNotSelected = (arr) => arr.every((v) => v.selected === false)

            if (isNotSelected(this.categories)) {
                this.emitCategories(this.categories)
                this.isActive = false
            }
        },
        search() {
            this.isActive = true
            this.emitCategories(this.categories)
        },
        clear() {
            this.isActive = false
            this.categories.filter((category) => {
                category.selected = false
            })
            this.emitCategories(this.categories)
        },
        filterselected(categories) {
            return categories.filter((category) => {
                return category.selected === true
            })
        },
    },
    mounted() {
        this.$root.$on('clearCategoryBar', () => {
            this.isActive = false
            this.categories.filter((category) => {
                category.selected = false
            })
        })
    },
    created() {
        axios.get(`${this.$app_url}category`).then((res) => {
            this.categories = res.data
            this.categories.forEach((category) => {
                this.$set(category, 'selected', false)
            })
        })
    },
}
</script>
