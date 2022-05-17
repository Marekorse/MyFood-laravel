<template>
    <div class="flex justify-between max-w-screen-lg flex-wrap">
        <article
            class="w-full lg:w-4/6 md:pt-12 md:flex justify-center bg-white sm:rounded-xl py-1 shadow-lg px-0.5 relative mb-6">
            <div
                class="md:absolute rounded-full inline-block border-4 border-white md:-top-10 md:left-4 shadow-xl">
                <figure
                    class="flex items-center gradient-green-blue w-16 max-h-16 overflow-hidden p-2 rounded-full">
                    <img
                        class="w-16 font-semibold"
                        :src="$app_url + 'storage/images/icons/category-2.svg'"
                        alt="" />
                </figure>
            </div>
            <div class="recept w-full text-gray-700 font-semibold">
                <div class="inline-flex items-center flex-wrap">
                    <a
                        v-for="category in recipe.categories"
                        :key="category.id"
                        :href="
                            encodeURI(
                                $app_url + 'recepty?category=' + category.name
                            )
                        ">
                        <span
                            class="shadow-md px-5 m-1 text-sm rounded-lg gradient-green-blue text-white"
                            >{{ category.name }}</span
                        >
                    </a>
                </div>
                <div
                    class="md:flex items-center justify-between relative px-4 flex-shrink-0">
                    <h1
                        class="text-xl font-semibold  sm:text-5xl py-5 break-words">
                        {{ recipe.title }}
                    </h1>

                    <div v-if="recipeAuthor" class="flex">
                        <a
                            :href="$app_url + 'recept/' + recipe.id + '/edit'"
                            class="text-gray-500 group underline cursor-pointer rounded-md inline-flex items-center text-base font-medium hover:text-gray-900 focus:outline-none mr-2"
                            >{{ $t('global.edit') }}</a
                        >
                        <a
                            @click.prevent="areYouSure = true"
                            href=""
                            class="text-gray-500 group underline cursor-pointer rounded-md inline-flex items-center text-base font-medium hover:text-gray-900 focus:outline-none mr-2"
                            >{{ $t('global.delete') }}</a
                        >
                        <are-you-sure
                            v-if="areYouSure"
                            @submitEvent="deleteRecipe()"
                            @closeEvent="areYouSure = false"
                            :button="$t('global.delete')"
                            :message="
                                $t('areYouSure.deleteRecipe')
                            "></are-you-sure>
                    </div>
                    <div v-if="!recipeAuthor && shared" class="flex m-2">
                        <a
                            v-if="!improved"
                            :href="
                                $app_url +
                                'receptar/' +
                                book.id +
                                '/recept/' +
                                recipe.id +
                                '/create'
                            "
                            class="w-24 text-sm text-center font-semibold px-6 py-1 bg-indigo-600 shadow-md text-white rounded-full border-r-2 border-b-2 border-indigo-700"
                            >{{ $t('recipesBook.improveRecipe') }}</a
                        >
                        <a
                            v-if="improved"
                            :href="
                                $app_url +
                                'receptar/' +
                                book.id +
                                '/recept/' +
                                recipe.id +
                                '/edit'
                            "
                            class="w-24 text-sm text-center font-semibold px-6 py-1 bg-indigo-600 shadow-md text-white rounded-full border-r-2 border-b-2 border-indigo-700"
                            >{{ $t('global.edit') }}</a
                        >
                    </div>
                </div>
                <figure
                    class="flex items-center overflow-y-hidden w-full h-80 md:max-h-96 bg-blue-100 rounded-xl">
                    <transition name="fade" mode="out-in">
                        <image-component
                            :key="'orig'"
                            v-if="!improvement.image"
                            class="rounded-lg recipe-image"
                            showOnClick
                            :image="recipe.image" />
                    
                    
                        <image-component
                            :key="'improved'"
                            v-if="improvement.image"
                            class="rounded-lg recipe-image"
                            private
                            showOnClick
                            :image="improvement.image" />
                    </transition>
                </figure>
                <div class="flex items-center p-2">
                    <add-to-recipe-book
                        v-if="logged && recipe.confirmation === 1"
                        :book="user.book"
                        :is-in-user-book="recipe.book_recipes_exists"
                        :recipe-id="recipe.id">
                    </add-to-recipe-book>

                    <like-button
                        v-if="recipe.confirmation === 1"
                        class="ml-1"
                        :recipe-id="recipe.id"
                        :auth-id="user.id">
                    </like-button>
                </div>
                <div class="p-3 text-sm sm:text-base sm:px-10">
                    <div class="flex list-none justify-between">
                        <div>
                            <li
                                class="flex flex-col md:flex-row justify-center items-center">
                                <img
                                    class="w-10 h-10 text-green-500 mr-1"
                                    :src="
                                        $app_url +
                                        'storage/images/icons/ClockIcon.svg'
                                    "
                                    alt=""
                                    srcset="" />
                                <span class="text-green-500">{{
                                    $t('recipe.preparation')
                                }}</span>
                            </li>
                            <transition name="fade" mode="out-in">
                                <li
                                    v-if="!improvement.time"
                                    class="text-center mt-1 text-base"
                                    key="original">
                                    {{ recipe.time }}
                                    {{ $t('recipeForm.timeFull') }}
                                </li>
                                <li
                                    v-else
                                    class="text-center mt-1 text-base"
                                    key="improved">
                                    {{ improvement.time }}
                                    {{ $t('recipeForm.timeFull') }}
                                    <improvement-label />
                                </li>
                            </transition>
                        </div>
                        <div>
                            <li
                                class="flex flex-col md:flex-row justify-center items-center">
                                <img
                                    class="w-10 h-10 text-green-500 mr-1"
                                    :src="
                                        $app_url +
                                        'storage/images/icons/ChunkIcon.svg'
                                    "
                                    alt=""
                                    srcset="" />
                                <span class="text-green-500">{{
                                    $t('recipe.numberOfServings')
                                }}</span>
                            </li>
                            <transition name="fade" mode="out-in">
                                <li
                                    v-if="!improvement.chunk"
                                    class="text-center mt-1 text-base"
                                    key="original">
                                    {{ recipe.chunk }}
                                </li>
                                <li
                                    v-else
                                    class="text-center mt-1 text-base"
                                    key="improved">
                                    {{ improvement.chunk }}
                                    <improvement-label />
                                </li>
                            </transition>
                        </div>
                        <div>
                            <li
                                class="flex flex-col md:flex-row justify-center items-center">
                                <img
                                    class="w-10 h-10 text-green-500 mr-1"
                                    :src="
                                        $app_url +
                                        'storage/images/icons/DifficultyIcon.svg'
                                    "
                                    alt=""
                                    srcset="" />
                                <span class="text-green-500">{{
                                    $t('recipe.difficulty')
                                }}</span>
                            </li>
                            <transition name="fade" mode="out-in">
                                <li
                                    v-if="!improvement.difficulty"
                                    class="text-center mt-1 text-base"
                                    key="original">
                                    {{ recipe.difficulty }}
                                </li>
                                <li
                                    v-else
                                    class="text-center mt-1 text-base"
                                    key="improved">
                                    {{ improvement.difficulty }}
                                    <improvement-label />
                                </li>
                            </transition>
                        </div>
                    </div>
                    <div class="block list-none text-2xl">
                        <h2 class="py-5 text-green-500 sm:py-8">
                            {{ $t('recipe.whatWeNeed') }} :
                        </h2>
                        <transition-group
                            name="fade"
                            mode="out-in"
                            v-if="!improvement.ingredients">
                            <li
                                v-for="ingredient in JSON.parse(
                                    recipe.ingredients
                                )"
                                :key="ingredient.id"
                                class="px-7 text-base border-dashed border-b-2 border-green-500 text-gray-500 break-words">
                                {{ ingredient.name }}
                            </li>
                        </transition-group>
                        <transition-group
                            name="fade"
                            mode="out-in"
                            v-if="improvement.ingredients">
                            <li
                                v-for="ingredient in improvement.ingredients"
                                :key="ingredient.id"
                                class="px-7 relative text-base border-dashed border-b-2 border-green-500 text-gray-500 break-words">
                                <span>{{ ingredient.name }}</span>

                                <improvement-label
                                    rotate
                                    v-if="ingredient.improved"
                                    class="absolute right-4 top-0" />
                            </li>
                        </transition-group>
                    </div>
                    <div v-if="recipe.text" class="">
                        <h2 class="text-2xl text-green-500 py-5 sm:py-8">
                            {{ $t('recipe.recipeDescription') }} :
                        </h2>
                        <transition name="fade" mode="out-in">
                            <p
                                v-if="!improvement.text"
                                class="pl-2 break-words text-gray-500">
                                {{ recipe.text }}
                            </p>
                            <div
                                v-if="improvement.text"
                                class="pl-2 break-words text-gray-500 relative">
                                <span>{{ recipe.text }}</span>
                                <improvement-label
                                    rotate
                                    class="absolute right-4 top-0" />
                            </div>
                        </transition>
                    </div>
                    <div class="mb-10">
                        <h2 class="text-2xl text-green-500 py-5 sm:py-8">
                            {{ $t('recipe.howWeWillProceed') }} :
                        </h2>
                        <transition-group
                            name="fade"
                            mode="out-in"
                            v-if="!improvement.approach">
                            <div
                                v-for="(approach, index) in JSON.parse(
                                    recipe.approach
                                )"
                                :key="approach.id"
                                class="flex py-4">
                                <li
                                    class="flex flex-shrink-0 justify-center items-center gradient-green-blue w-8 h-8 rounded-full text-white">
                                    {{ index + 1 }}
                                </li>
                                <p class="ml-2 py-1 text-gray-500 break-words">
                                    {{ approach.name }}
                                </p>
                            </div>
                        </transition-group>
                        <transition-group
                            name="fade"
                            mode="out-in"
                            v-if="improvement.approach">
                            <div
                                v-for="(
                                    approach, index
                                ) in improvement.approach"
                                :key="approach.id"
                                class="flex py-4 relative">
                                <li
                                    class="flex flex-shrink-0 justify-center items-center gradient-green-blue w-8 h-8 rounded-full text-white">
                                    {{ index + 1 }}
                                </li>
                                <p class="ml-2 py-1 text-gray-500 break-words">
                                    {{ approach.name }}
                                </p>

                                <improvement-label
                                    rotate
                                    v-if="approach.improved"
                                    class="absolute right-4 top-0" />
                            </div>
                        </transition-group>
                    </div>
                </div>
            </div>
        </article>
        <aside class="flex flex-col lg:pl-6 w-full lg:w-2/6">
            <improvements-card
                @changeImprovement="showImprovement"
                v-if="recipe.improvements && recipe.improvements.length"
                :bookId="book.id"
                :auth-id="user.id"
                :improvements="recipe.improvements" />
            <user-card
                class="w-full"
                :recipe-user="recipeUser"
                :auth-id="user.id || null" />

            <five-star-rating :auth-id="user.id" :recipe-id="recipe.id" />
        </aside>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
import likeButton from '../recipe/likeButton.vue'
import newMessage from '../conversations/newMessage.vue'
import addToRecipeBook from '../recipeBook/addToRecipeBook.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
import userCard from '../recipe/userCard.vue'
import userComments from '../userComments.vue'
import fiveStarRating from '../rating/fiveStarRating.vue'
import improvementsCard from './improvementsCard.vue'
import improvementLabel from './improvmentLabel.vue'

export default {
    components: {
        newMessage,
        addToRecipeBook,
        areYouSure,
        userCard,
        userComments,
        fiveStarRating,
        likeButton,
        improvementsCard,
        improvementLabel,
        imageComponent
    },

    props: {
        book: Object,
        shared: Boolean,
        user: [Number, Object],
        recipeProp: Object,
    },
    data: function () {
        return {
            recipe: this.recipeProp,
            recipeAuthor: false,
            areYouSure: false,
            addToRecipeBook: false,
            improvement: {
                image: null,
                time: null,
                chunk: null,
                difficulty: null,
                text: null,
                ingredients: {},
                approach: {},
            },
        }
    },
    methods: {
        filterImprovement(improvement) {
            const ignoreFields = ['book_id', 'recipe_id', 'user_id']
            let filtered = improvement
            for (const [key, value] of Object.entries(filtered)) {
                if (ignoreFields.includes(key)) {
                    delete improvement[key]
                }
            }

            return improvement
        },
        showImprovement(improvement) {
            if (improvement === 'original') {
                Object.keys(this.improvement).map((key) => {
                    if (this.improvement[key] instanceof Array) {
                        this.improvement[key] = []
                    } else this.improvement[key] = null
                })
                return
            }
            for (const [key, value] of Object.entries(
                this.filterImprovement(improvement)
            )) {
                if (value && this.recipe.hasOwnProperty(key)) {
                    if (
                        this.recipe[key] != value &&
                        this.hasJsonStructure(value)
                    ) {
                        let originalRecipe = JSON.parse(this.recipe[key])
                        let improvedRecipe = (this.improvement[key] =
                            JSON.parse(value))
                        improvedRecipe.filter((improved) => {
                            if (
                                !originalRecipe.some(
                                    (original) => improved.id === original.id
                                )
                            ) {
                                improved.improved = true
                            }
                        })
                    } else if (
                        this.recipe[key] != value &&
                        !this.hasJsonStructure(value)
                    ) {
                        this.improvement[key] = value
                    }
                }
            }
        },
        hasJsonStructure(str) {
            if (typeof str !== 'string') return false
            try {
                const result = JSON.parse(str)
                const type = Object.prototype.toString.call(result)
                return type === '[object Object]' || type === '[object Array]'
            } catch (err) {
                return false
            }
        },
        addItemToArray(event, array) {
            array.push(event)
        },
        removeItemFromArray(event, array) {
            let item = array.find((key) => {
                return key.id === event
            })
            let index = array.indexOf(item)

            array.splice(index, 1)
        },

        deleteRecipe() {
            axios.delete(`${this.$app_url}recept/${this.recipe.id}`)
            this.redirect()
        },
        recipeCheck() {
            if (this.user.id === this.recipe.user_id) {
                this.recipeAuthor = true
            }
            if (this.book && this.book.user_id === this.user.id) {
                this.addToRecipeBook = true
            }
        },
        redirect() {
            window.location.href = this.$app_url
        },
    },
    computed: {
        improved: function () {
            return this.recipeProp.improvements.find((improvement) => {
                return improvement.user_id === this.user.id
            })
        },
        recipeUser: function () {
            return this.recipe.user ?? this.recipe.guest
        },
    },
    created() {
        if (this.user.id) {
            this.logged = true
            this.recipeCheck()
        }
        if (this.improved) {
            this.showImprovement(this.improved)
        }
    },
}
</script>
