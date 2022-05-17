<template>
    <div class="w-full h-full">
        <p
            class="text-2xl custom-color-logoGray font-semibold py-2 sm:text-5xl lg:py-5 break-words overflow-hidden">
            {{ recipe.title }}
        </p>
        <image-component
            class="rounded-lg recipe-image"
            showOnClick
            :image="recipe.image" />
        <div class="p-3 text-sm sm:text-xl sm:px-10">
            <div class="flex list-none justify-between">
                <div>
                    <li class="flex md:flex-col justify-center items-center">
                        <i
                            class="mr-0.5 hidden sm:block far fa-clock text-xl text-yellow-500 text-center align-baseline"></i>
                        {{ $t('recipe.preparation') }}
                    </li>
                    <li class="text-center text-sm">
                        {{ recipe.time }} {{ $t('recipeForm.timeFull') }}
                    </li>
                </div>
                <div>
                    <li class="flex md:flex-col justify-center items-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mr-0.5 h-6 w-6 text-xl text-yellow-900 text-center align-baseline"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        {{ $t('recipe.numberOfServings') }}
                    </li>
                    <li class="text-center text-sm">{{ recipe.chunk }}</li>
                </div>
                <div>
                    <li class="flex md:flex-col justify-center items-center">
                        <i
                            v-show="recipe.difficulty === 'Jednoduchý'"
                            class="fas fa-circle text-green-300 mr-1"></i>
                        <i
                            v-show="recipe.difficulty === 'Stredná'"
                            class="fas fa-adjust text-yellow-700 mr-1"></i>
                        <i
                            v-show="recipe.difficulty === 'Tažký'"
                            class="far fa-circle text-red-700 mr-1"></i>
                        {{ $t('recipe.difficulty') }}
                    </li>
                    <li class="text-center">{{ recipe.difficulty }}</li>
                </div>
            </div>
            <div class="block list-none text-xl">
                <h2 class="py-5 sm:py-8">
                    {{ $t('recipe.numberOfServings') }} :
                </h2>
                <li
                    v-for="ingredient in recipe.ingredients"
                    :key="ingredient.id"
                    class="px-7 border-dashed border-b-2 border-green-500 text-gray-500 break-words overflow-hidden">
                    {{ ingredient.name }}
                </li>
            </div>
            <div v-if="recipe.text" class="">
                <h2 class="text-2xl py-5 sm:py-8">
                    {{ $t('recipe.recipeDescription') }} :
                </h2>
                <p
                    class="pl-2 whitespace-pre-wrap break-all text-gray-500 break-words overflow-hidden">
                    {{ recipe.text }}
                </p>
            </div>
            <div class="mb-10">
                <h2 class="text-2xl py-5 sm:py-8">
                    {{ $t('recipe.howWeWillProceed') }} :
                </h2>
                <div
                    v-for="(approach, index) in recipe.approach"
                    :key="approach.id"
                    class="flex py-4">
                    <li
                        class="flex flex-shrink-0 justify-center items-center bg-gradient-to-r from-green-400 to-green-300 w-8 h-8 rounded-full text-white break-words overflow-hidden">
                        {{ index + 1 }}
                    </li>
                    <p class="ml-2 py-1 text-gray-500">
                        {{ approach.name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
export default {
    components: {
        imageComponent,
    },
    props: { recipe: Object },
}
</script>
