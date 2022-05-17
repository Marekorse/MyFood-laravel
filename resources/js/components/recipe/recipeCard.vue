<template>
    <div :id="recipe.id" class="">
        <a @dragstart="dragStart" :href="link">
            <div
                class="p-2 md:p-3 w-36 h-44 relative md:w-52 md:h-60 shadow-lg rounded-2xl bg-gray-50 text-gray-700 transition transform duration-500 hover:-translate-y-1">
                <div v-if="recipe.book_recipes_exists">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 rounded-full gradient-green-blue text-white p-0.5 absolute right-1 bottom-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1
                    class="text-gray-600 mr-3 h-10 flex items-center text-xs md:text-sm leading-tight font-semibold md:mb-2 break-all text-left">
                    {{ stringLimiter(recipe.title, 40) }}
                </h1>
                <div class="relative">
                    <figure
                        class="flex items-center h-20 md:h-28 rounded-xl bg-blue-100 overflow-hidden sm:border-b-2 sm:border-gray-200 border-b-2 border-gray-200">
                        <p
                            class="flex items-center absolute top-0 bg-blue-100 text-gray-800 font-semibold md:py-1 px-1 md:px-3 rounded-br-lg rounded-tl-lg">
                            <span class="flex items-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3 w-3 md:h-5 md:w-5 text-red-500"
                                    viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p
                                    class="text-gray-500 font-semibold text-xs md:text-base">
                                    {{ recipe.likes_count }}
                                </p>
                            </span>
                            <span class="flex items-center ml-1">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-3 w-3 md:h-5 md:w-5 text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                                <p
                                    class="text-gray-500 font-semibold text-xs md:text-base">
                                    {{ recipe.comments_count }}
                                </p>
                            </span>
                        </p>
                        <div
                            class="w-28 h-28 absolute -right-1 -bottom-1 text-white overflow-hidden"
                            v-if="
                                recipe.improvements &&
                                recipe.improvements.length
                            ">
                            <div
                                class="absolute -right-7 -bottom-7 flex items-center justify-center w-full h-full transform -rotate-45">
                                <span
                                    class="bg-indigo-600 px-6 text-white flex flex-col items-center h-6 text-sm"
                                    >upravený</span
                                >
                            </div>
                        </div>
                        <image-component
                            class="w-full object-cover"
                            thumb
                            :image="recipe.image" />
                    </figure>
                </div>
                <div
                    class="flex flex-col justify-start mt-1 mx-1 md:py-0.5 font-semibold">
                    <div
                        class="md:flex md:items-center sm:justify-between mb-1">
                        <li
                            class="info text-xs md:text-sm list-none flex mb-1 md:mb-0 items-center mr-2 md:mr-0">
                            <img
                                class="w-4 h-4 md:w-6 md:h-6 text-green-500 mr-1"
                                :src="
                                    $app_url +
                                    'storage/images/icons/ClockIcon.svg'
                                "
                                alt=""
                                srcset="" />
                            {{ recipe.time + $t('recipe.timeShort') }}
                        </li>
                        <li
                            class="info text-xs md:text-sm list-none flex items-center text-gray-600">
                            <img
                                class="w-4 h-4 md:w-6 md:h-6 text-green-500 mr-1"
                                :src="
                                    $app_url +
                                    'storage/images/icons/ChunkIcon.svg'
                                "
                                alt=""
                                srcset="" />
                            {{ recipe.chunk + ' ' + howMany }}
                        </li>
                    </div>
                    <li
                        class="info text-xs md:text-sm list-none hidden md:flex items-center text-gray-600">
                        <img
                            class="w-4 h-4 md:w-6 md:h-6 text-green-500 mr-1"
                            :src="
                                $app_url +
                                'storage/images/icons/DifficultyIcon.svg'
                            "
                            alt=""
                            srcset="" />

                        {{ difficulty }}
                    </li>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
export default {
    components: {
        imageComponent,
    },
    props: {
        url: {
            type: String,
        },
        recipe: {
            type: Object,
        },
        isInBook: {
            isInBook: Number,
            default: 0,
        },
    },
    data: function () {
        return {
            title: '',
        }
    },
    methods: {
        dragStart(e) {
            e.dataTransfer.setData('card_id', this.recipe.id)
            e.dataTransfer.setData('card_name', this.recipe.title)
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
        link: function () {
            return `${this.url}/${this.recipe.slug}`
        },
        howMany: function () {
            if (this.recipe.chunk <= 4) {
                return this.$tc('recipe.servings', 1)
            } else return this.$tc('recipe.servings', 2)
        },
    },
}
</script>

<style lang="scss">
.text-small {
    font-size: 0.68rem;
    line-height: 1rem;
}
.text-custom {
    font-size: 0.79rem;
}
.rounded-bottom-custom {
    border-bottom-right-radius: 3rem;
    border-bottom-left-radius: 3rem;
}
.rounded-top-custom {
    border-top-right-radius: 3rem;
    border-top-left-radius: 3rem;
}
.roboto {
    font-family: 'Roboto', sans-serif;
    background-color: #fff;
}
.text-custom-color {
    color: #34d399;
}
</style>
