<template>
    <div class="w-full h-full">
        <div ref="container" class="max-w-screen-lg h-full-lg m-auto">
            <div
                class="w-full h-full flex justify-center"
                v-if="recipes.data <= 0">
                <div
                    class="bg-white h-full w-full md:w-2/3 lg:w-2/4 p-4 m-2 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="font-semibold text-blue-400">
                        {{ errorMessage }}
                    </span>
                </div>
            </div>
            <transition-group
                name="fade"
                mode="in-out"
                class="flex gap-3 justify-center flex-wrap">
                <div
                    class="relative"
                    v-for="recipe in recipes.data"
                    :key="recipe.id">
                    <recipe-card :url="cardUrl" :recipe="recipe"> </recipe-card>
                </div>
            </transition-group>
            <paginate
                v-if="recipes.data"
                size="medium"
                :next-page-url="recipes.next_page_url"
                :prev-page-url="recipes.prev_page_url"
                :last-page="recipes.last_page"
                :current-page="recipes.current_page"
                @paginated="paginate"></paginate>
        </div>
    </div>
</template>

<script>
import paginate from '../basicComponents/paginate.vue'
import recipeCard from './recipeCard.vue'

export default {
    components: {
        recipeCard,
        paginate,
    },
    props: { userId: Number, baseUrl: String, cardUrl: String },

    data: function () {
        return {
            errorMessage: '',
            categories: '',
            search: '',
            user: this.userId || '',
            recipes: [],
        }
    },
    methods: {
        paginate(pageNumber = 1) {
            axios
                .get(
                    encodeURI(
                        `${this.baseUrl}?page=${pageNumber}&category=${this.categories}&search=${this.search}&user=${this.user}`
                    )
                )
                .then((response) => {
                    this.recipes = ''
                    if (response.data.recipes.length) {
                    } else this.showErrorMessage()
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.recipes = response.data.recipes
                        }, 300)
                    })
                })
            this.scrollToTop()
        },
        scrollToTop() {
            window.scrollTo(0, 0)
        },
        showErrorMessage() {
            if (
                this.search === '' &&
                this.categories === '' &&
                this.user === ''
            ) {
                this.errorMessage = this.$t('global.recipesNotFound')
            } else if (
                this.search === '' &&
                this.categories === '' &&
                this.user !== ''
            ) {
                this.errorMessage = this.$t('global.recipesByUserNotFound')
            } else if (this.search === '' && this.categories !== '') {
                this.errorMessage = this.$t('global.recipesNotFound')
            } else if (this.search !== '') {
                this.errorMessage = this.$t('global.recipesNotFound')
            }
        },
    },
    computed: {
        isInBook() {
            return this.data
        },
    },
    mounted() {
        this.$root.$on('addedToBook', (id) => {
            this.recipes.data.map((recipe) => {
                if (recipe.id == id) {
                    recipe.book_recipes_exists = true
                }
            })
        })
        this.$root.$on('removedFromBook', (id) => {
            const recipe = this.recipes.data.find((recipe) => {
                return recipe.id == id
            })
            console.log(this.recipes.data.indexOf(recipe))
            this.recipes.data.splice(this.recipes.data.indexOf(recipe), 1)
        })
        this.$root.$on('setCategory', (data) => {
            this.categories = ''
            if (!Array.isArray(data)) {
                this.categories += data
            } else {
                data.forEach((category, index) => {
                    if (index == data.length - 1) {
                        this.categories += category.name
                    } else this.categories += category.name + ','
                })
            }

            this.search = ''
            this.$root.$emit('resetSearchBar')
            this.paginate(1, data)
        })

        this.$root.$on('search', (data) => {
            this.search = data
            this.paginate()
        })
    },
    created() {
        this.paginate()
    },
}
</script>
