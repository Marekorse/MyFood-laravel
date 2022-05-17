<template>
    <div class="flex justify-between max-w-screen-lg flex-wrap">
        <article
            class="w-full lg:w-4/6 md:pt-12 md:flex justify-center bg-white sm:rounded-xl py-1 shadow-lg px-0.5 relative">
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
                <div class="md:flex items-center justify-between relative px-4">
                    <h1
                        class="text-2xl font-semibold  sm:text-5xl py-5 break-words">
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
                </div>
                <image-component
                    class="rounded-lg recipe-image"
                    showOnClick
                    :image="recipe.image" />
                <div class="flex items-center p-2">
                    <add-to-recipe-book
                        :disabled="!user"
                        v-if="recipe.confirmation === 1"
                        :book="user.book"
                        :is-in-user-book="recipe.book_recipes_exists"
                        :recipe-id="recipe.id">
                    </add-to-recipe-book>

                    <like-button
                        :disabled="!user"
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
                            <li class="text-center mt-1 text-base">
                                {{ recipe.time }}
                                {{ $t('recipeForm.timeFull') }}
                            </li>
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
                            <li class="text-center mt-1 text-base">
                                {{ recipe.chunk }}
                            </li>
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
                            <li class="text-center text-base mt-1">
                                {{ difficulty }}
                            </li>
                        </div>
                    </div>
                    <div class="block list-none text-2xl">
                        <h2 class="py-5 text-green-500 sm:py-8">
                            {{ $t('recipe.whatWeNeed') }} :
                        </h2>
                        <li
                            v-for="ingredient in JSON.parse(recipe.ingredients)"
                            :key="ingredient.id"
                            class="px-7 text-base border-dashed border-b-2 border-green-500 text-gray-500 break-words">
                            {{ ingredient.name }}
                        </li>
                    </div>
                    <div v-if="recipe.text" class="">
                        <h2 class="text-2xl text-green-500 py-5 sm:py-8">
                            {{ $t('recipe.recipeDescription') }} :
                        </h2>
                        <p class="pl-2 break-words text-gray-500">
                            {{ recipe.text }}
                        </p>
                    </div>
                    <div class="mb-10">
                        <h2 class="text-2xl text-green-500 py-5 sm:py-8">
                            {{ $t('recipe.howWeWillProceed') }} :
                        </h2>
                        <div
                            v-for="(approach, index) in JSON.parse(
                                recipe.approach
                            )"
                            :key="approach.id"
                            class="flex py-4">
                            <li
                                class="flex flex-shrink-0 justify-center items-center custom-gradient w-8 h-8 rounded-full text-white">
                                {{ index + 1 }}
                            </li>
                            <p class="ml-2 py-1 text-gray-500 break-words">
                                {{ approach.name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <aside class="flex flex-col lg:pl-6 w-full lg:w-2/6">
            <user-card
                class="w-full"
                :recipe-user="recipeUser"
                :auth-id="user.id || null" />

            <five-star-rating
                :auth-id="user.id || null"
                :recipe-id="recipe.id" />
        </aside>

        <user-comments
            class="w-full px-0.5"
            :auth-id="user.id"
            :recipe-id="recipe.id"
            :recipe-user-id="recipeUser.id || 0">
        </user-comments>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
import likeButton from './likeButton.vue'
import newMessage from '../conversations/newMessage.vue'
import addToRecipeBook from '../recipeBook/addToRecipeBook.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
import userCard from './userCard.vue'
import userComments from '../userComments.vue'
import fiveStarRating from '../rating/fiveStarRating.vue'

export default {
    components: {
        newMessage,
        addToRecipeBook,
        areYouSure,
        userCard,
        userComments,
        fiveStarRating,
        likeButton,
        imageComponent,
    },

    props: {
        book: Object,
        user: [Number, Object],
        recipeProp: Object,
    },
    data: function () {
        return {
            recipe: this.recipeProp,
            recipeAuthor: false,
            areYouSure: false,
            addToRecipeBook: false,
        }
    },
    methods: {
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
        difficulty: function () {
            switch (this.recipe.difficulty) {
                case 'easy':
                    return this.$t('recipeForm.easy')
                case 'medium':
                    return this.$t('recipeForm.medium')
                case 'hard':
                    return this.$t('recipeForm.hard')
                default:
                    break
            }
        },
        recipeUser: function () {
            return this.recipe.user ?? this.recipe.guest
        },
    },
    created() {
        if (this.user.id) {
            this.recipeCheck()
        }
    },
}
</script>
