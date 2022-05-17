<template>
    <div>
        <div class="w-full pt-20">
            <div
                class="w-full p-1 md:flex sm:justify-center items-start sm:m-auto">
                <div
                    class="recept-box w-full md:w-4/5 max-w-2xl md:flex sm:justify-center sm:block bg-white rounded-xl py-1 shadow-lg border-2 border-gray-100">
                    <div class="recept">
                        <div
                            class="flex flex-col sm:flex-row items-center justify-start sm:justify-between relative sm:px-4">
                            <div
                                class="w-full relative flex flex-row items-center self-start">
                                <dropdown
                                    v-if="
                                        auth === recipe.user_id ||
                                        action === 'newRecipe' ||
                                        action === 'editRecipe'
                                    "
                                    class="m-2"
                                    onClick
                                    :fixed="width < 768"
                                    align="left">
                                    <template v-slot:text>
                                        <span
                                            class="text-base rounded-xl font-semibold px-6 py-1 text-white bg-green-400"
                                            >#
                                            {{
                                                $t(
                                                    'recipeForm.categoryPlaceholder'
                                                )
                                            }}
                                        </span>
                                    </template>
                                    <template v-slot:dropDown>
                                        <div
                                            class="w-72 md:w-96 bg-gray-100 shadow-md rounded-lg p-3"
                                            :class="{
                                                'h-screen': width < 768,
                                            }">
                                            <span
                                                class="text-4xl font-corinthia font-semibold text-gradient m-2"
                                                >{{
                                                    $t(
                                                        'recipeForm.selectCategories'
                                                    )
                                                }}</span
                                            >
                                            <div
                                                class="w-full h-0.5 my-2 bg-gradient-to-r from-transparent via-green-400 to-transparent" />
                                            <ul class="inline-flex flex-wrap">
                                                <li
                                                    @click="
                                                        setCategory(category)
                                                    "
                                                    class="font-semibold shadow-md px-5 m-1 text-sm rounded-lg"
                                                    :class="{
                                                        'bg-gradient-to-tr from-green-300 via-green-400 to-green-500 text-white':
                                                            category.selected,
                                                        'bg-white text-gray-500':
                                                            !category.selected,
                                                    }"
                                                    v-for="category in categories"
                                                    :key="category.id">
                                                    {{ category.name }}
                                                </li>
                                            </ul>
                                        </div>
                                    </template>
                                </dropdown>
                                <div
                                    v-if="
                                        auth === recipe.user_id ||
                                        (auth && action === 'newRecipe') ||
                                        (auth && action === 'editRecipe')
                                    "
                                    class="flex items-center justify-center gap-3">
                                    <toggle-button
                                        @unchecked="setPrivacy"
                                        @checked="setPrivacy"
                                        :check="recipe.privacy === 3"
                                        checkedText="Verejný"
                                        uncheckedText="súkromný" />
                                </div>
                                <transition name="fade">
                                    <div v-if="errors.category">
                                        <label
                                            v-for="error in errors.category"
                                            :key="error"
                                            for="category"
                                            class="flex items-center w-64 text-center left-4 top-10 z-10 md:top-16 md:left-16 absolute text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ error }}
                                        </label>
                                    </div>
                                </transition>
                            </div>
                        </div>
                        <div class="relative">
                            <transition name="fade">
                                <div v-if="errors.title">
                                    <label
                                        v-for="error in errors.title"
                                        :key="error"
                                        for="title"
                                        class="flex items-center w-64 text-center top-14 absolute text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ error }}
                                    </label>
                                </div>
                            </transition>
                            <textarea
                                :disabled="
                                    action == 'newImprovedRecipe' ||
                                    action == 'editImprovedRecipe'
                                "
                                @click="errors.title = ''"
                                maxlength="50"
                                rows="2"
                                type="text"
                                v-model="recipe.title"
                                class="sm:text-left text-gray-600 placeholder-gray-300 w-full break-words rounded-xl font-display text-2xl leading-7 font-semibold pt-6 px-2 sm:px-5 sm:text-5xl sm:leading-10 outline-none border-none focus:ring-0 resize-none"
                                placeholder="Názov receptu"></textarea>
                        </div>
                        <div @click="errors.file = ''" class="relative">
                            <transition name="fade">
                                <div v-if="errors.file">
                                    <label
                                        v-for="error in errors.file"
                                        :key="error"
                                        for="file"
                                        class="flex items-center absolute left-2 top-2 w-64 text-center text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ error }}
                                    </label>
                                </div>
                            </transition>
                            <add-image
                                :old-image="recipeImage"
                                @newImage="updateImage"
                                class="w-full">
                            </add-image>
                        </div>

                        <div
                            class="block mt-3 list-none px-2 md:flex md:justify-between md:px-10 md:text-2xl md:text-gray-700">
                            <div
                                class="flex items-center relative text-lg font-semibold text-gray-700 md:block md:text-center md:text-lg">
                                <transition name="fade">
                                    <div v-if="errors.time">
                                        <label
                                            v-for="error in errors.time"
                                            :key="error"
                                            for="time"
                                            class="flex items-center w-44 text-center absolute -top-1 custom-small-text font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ error }}
                                        </label>
                                    </div>
                                </transition>
                                <li>{{ $t('recipeForm.preparation') }}</li>
                                <input
                                    @click="errors.time = ''"
                                    v-model="recipe.time"
                                    maxlength="3"
                                    id=""
                                    class="text-center font-semibold text-green-500 outline-none border-none focus:outline-none focus:ring-0 w-14 md:mt-2 md:text-center md:outline-none md:border-none md:bg-gray-100 md:rounded-xl md:py-2"
                                    placeholder="00" />
                                {{ $t('recipeForm.timeFull') }}
                            </div>
                            <div
                                class="flex items-center relative text-lg font-semibold text-gray-700 md:block md:text-center md:text-lg">
                                <transition name="fade">
                                    <div v-if="errors.chunk">
                                        <label
                                            v-for="error in errors.chunk"
                                            :key="error"
                                            for="chunk"
                                            class="flex items-center w-44 text-center absolute -top-1 custom-small-text font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ error }}
                                        </label>
                                    </div>
                                </transition>
                                <li>{{ $t('recipeForm.numberOfServings') }}</li>
                                <input
                                    @click="errors.chunk = ''"
                                    v-model="recipe.chunk"
                                    maxlength="2"
                                    id=""
                                    class="text-center font-semibold text-green-500 outline-none border-none focus:outline-none focus:ring-0 w-14 md:mt-2 md:text-center md:outline-none md:border-none md:bg-gray-100 md:rounded-xl md:py-2"
                                    placeholder="0" />
                            </div>
                            <div
                                class="flex items-center relative text-lg font-semibold text-gray-700 md:block md:text-center md:text-lg">
                                <transition name="fade">
                                    <div v-if="errors.difficulty">
                                        <label
                                            v-for="error in errors.difficulty"
                                            :key="error"
                                            for="difficulty"
                                            class="flex items-center w-44 text-center absolute -top-1 custom-small-text font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ error }}
                                        </label>
                                    </div>
                                </transition>
                                <li>{{ $t('recipeForm.difficulty') }}</li>
                                <select
                                    @click="errors.difficulty = ''"
                                    id=""
                                    v-model="recipe.difficulty" 
                                    class="text-center font-semibold text-green-500 outline-none border-none focus:outline-none focus:ring-0 w-40 md:mt-2 md:text-center md:outline-none md:border-none md:bg-gray-100 md:rounded-xl md:py-2">
                                    <option disabled :value="''">
                                        {{ $t('recipeForm.difficulty') }}
                                    </option>
                                    <option :value="'easy'">
                                        {{ $t('recipeForm.easy') }}
                                    </option>
                                    <option :value="'medium'">
                                        {{ $t('recipeForm.medium') }}
                                    </option>
                                    <option :value="'hard'">
                                        {{ $t('recipeForm.hard') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div
                            @click="errors.ingredients = ''"
                            class="relative block list-none text-xl px-2 md:px-10 break-words">
                            <h2
                                class="sm:text-base md:text-2xl py-8 text-gray-700 text-lg font-semibold">
                                {{ $t('recipe.whatWeNeed') }} :
                            </h2>
                            <ingredients-inputs
                                :ingredients="recipe.ingredients"
                                @ingredientsChange="updateIngredients">
                            </ingredients-inputs>
                            <transition name="fade">
                                <div v-if="errors.ingredients">
                                    <label
                                        v-for="error in errors.ingredients"
                                        :key="error"
                                        for="ingredients"
                                        class="flex items-center absolute text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ error }}
                                    </label>
                                </div>
                            </transition>
                        </div>
                        <div
                            class="block relative list-none text-xl md:text-2xl px-2 md:px-10 break-words">
                            <h2
                                class="py-8 text-gray-700 text-lg font-semibold">
                                {{ $t('recipe.recipeDescription') }} :
                            </h2>
                            <p>
                                <textarea
                                    ref="descTextarea"
                                    @change="textareaResize()"
                                    maxlength="500"
                                    @click="errors.text = ''"
                                    v-model="recipe.text"
                                    id=""
                                    cols="30"
                                    class="border-0 rounded-xl w-full focus:outline-none focus:ring-0 text-xl placeholder-gray-300 resize-none"
                                    :placeholder="
                                        $t('recipeForm.descriptionPlaceholder')
                                    "></textarea>
                            </p>
                            <transition name="fade">
                                <div v-if="errors.text">
                                    <label
                                        v-for="error in errors.text"
                                        :key="error"
                                        for="text"
                                        class="flex items-center w-64 text-center absolute bottom-8 text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ error }}
                                    </label>
                                </div>
                            </transition>
                        </div>
                        <div
                            @click="errors.approach = ''"
                            class="block relative list-none text-xl md:text-2xl px-2 md:px-10 break-words">
                            <h2
                                class="py-8 text-gray-700 text-lg font-semibold">
                                {{ $t('recipe.howWeWillProceed') }} :
                            </h2>
                            <approach-inputs
                                :approach="recipe.approach"
                                @approachChange="updateAproach">
                                >
                            </approach-inputs>
                            <transition name="fade">
                                <div v-if="errors.approach">
                                    <label
                                        v-for="error in errors.approach"
                                        :key="error"
                                        for="approach"
                                        class="flex items-center absolute text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        {{ error }}
                                    </label>
                                </div>
                            </transition>
                        </div>

                        <div
                            v-if="!auth"
                            class="md:px-10 text-lg font-semibold text-gray-700">
                            <label for="guest"
                                >{{ $t('recipeForm.signature') }} :
                            </label>
                            <input
                                class="placeholder-gray-300 max-w-full rounded-xl font-display text-xl leading-9 font-semibold mt-10 mb-4 px-3 sm:text-xl sm:leading-10 outline-none border-none focus:ring-0"
                                :placeholder="$t('recipeForm.yourName')"
                                type="text"
                                v-model="recipe.guest"
                                id="" />
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="auth && action === 'newRecipe'"
                class="block text-center my-8">
                <button
                    @click.prevent="send()"
                    class="shadow-lg px-8 py-2 rounded-tr-2xl rounded-bl-2xl text-2xl text-white bg-green-600 hover:bg-green-500 transition-colors"
                    type="submit">
                    {{ $t('recipeForm.addRecipeButton') }}
                </button>
            </div>
            <div
                v-if="auth && action === 'newImprovedRecipe'"
                class="block text-center my-8">
                <button
                    @click.prevent="send()"
                    class="shadow-lg px-8 py-2 rounded-tr-2xl rounded-bl-2xl text-2xl text-white bg-green-600 hover:bg-green-500 transition-colors"
                    type="submit">
                    {{ $t('recipesBook.improveRecipe') }}
                </button>
            </div>
            <div v-if="!auth" class="block text-center my-8">
                <button
                    @click.prevent="send()"
                    class="shadow-lg px-8 py-2 rounded-tr-2xl rounded-bl-2xl text-2xl text-white bg-green-600 hover:bg-green-500 transition-colors"
                    type="submit">
                    {{ $t('recipeForm.sendToVerification') }}
                </button>
            </div>
            <div
                v-if="
                    action === 'editRecipe' || action === 'editImprovedRecipe'
                "
                class="block text-center my-8">
                <button
                    @click.prevent="send()"
                    class="shadow-lg px-8 py-2 rounded-tr-2xl rounded-bl-2xl text-2xl text-white bg-green-600 hover:bg-green-500 transition-colors"
                    type="submit">
                    {{ $t('global.save') }}
                </button>
            </div>
        </div>
        <aprroved-modal
            :message="this.message"
            v-if="aprroved"></aprroved-modal>
    </div>
</template>

<script>
import dropdown from '../basicComponents/dropdown.vue'
import addImage from './addImage.vue'
import approachInputs from './approachInputs.vue'
import ingredientsInputs from './ingredientsInputs.vue'
import aprrovedModal from './approvedModal.vue'
import toggleButton from '../basicComponents/toggleButton.vue'

export default {
    components: {
        ingredientsInputs,
        approachInputs,
        addImage,
        aprrovedModal,
        dropdown,
        toggleButton,
    },

    props: {
        withRedirect: Boolean,
        action: String,
        auth: [Number, String],
        editableRecipe: Object,
        book: Object,
        selectedCategories: {
            type: Array,
            default: null,
        },
    },

    data: function () {
        return {
            recipe: {
                title: '',
                time: '',
                chunk: '',
                image: null,
                difficulty: '',
                text: '',
                ingredients: [
                    {
                        id: 1,
                        name: '',
                    },
                ],
                approach: [
                    {
                        id: 1,
                        name: '',
                    },
                ],
                guest: '',
                file: '',
                privacy: 'Private',
            },

            originalRecipe: {},
            recipeImprovement: {},
            width: window.innerWidth,
            errors: [],
            aprroved: false,
            message: '',
            categories: [],
            improvement: {},
        }
    },
    methods: {
        filterImprovement(improvement) {
            const ignoreFields = ['id', 'book_id', 'recipe_id', 'user_id']
            let filtered = improvement
            for (const [key, value] of Object.entries(filtered)) {
                if (ignoreFields.includes(key)) {
                    delete improvement[key]
                }
            }

            return improvement
        },
        withImprovement(improvement) {
            for (const [key, value] of Object.entries(
                this.filterImprovement(improvement)
            )) {
                if (value && this.recipe.hasOwnProperty(key)) {
                    if (
                        this.recipe[key] != value &&
                        this.hasJsonStructure(value)
                    ) {
                        this.recipe[key] = JSON.parse(value)
                    } else if (this.recipe[key] != value) {
                        this.recipe[key] = value
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
        setPrivacy(checker) {
            switch (checker) {
                case 'check':
                    this.recipe.privacy = 3 //3=Public
                    break

                case 'uncheck':
                    this.recipe.privacy = 1 //1=Private
                    break
            }
        },
        onResize(e) {
            this.width = window.innerWidth
        },
        setCategory(selectedCategory) {
            this.categories.map((category) => {
                if (category.id === selectedCategory.id) {
                    category.selected = category.selected != true
                }
            })
        },
        saved(message, url, timeout = 2000) {
            this.message = message
            this.aprroved = true
            setTimeout(() => {
                window.location.href = url
            }, timeout)
        },
        send() {
            let formData = new FormData()

            formData.append(
                'categories',
                JSON.stringify(
                    this.categories.filter(
                        (category) => category.selected === true
                    )
                )
            )
            for (const [name, value] of Object.entries(this.recipe)) {
                if (Array.isArray(value)) {
                    formData.append(
                        name,
                        JSON.stringify(
                            value.filter((value) => value.name !== '')
                        )
                    )
                } else {
                    formData.append(name, value)
                }
            }

            switch (this.action) {
                case 'newRecipe':
                    this.newOrUpdateRecipe(this.action, formData)
                    break

                case 'editRecipe':
                    this.newOrUpdateRecipe(this.action, formData)
                    break

                case 'newImprovedRecipe':
                    this.improveRecipe(this.action, formData)
                    break

                case 'editImprovedRecipe':
                    this.improveRecipe(this.action, formData)
                    break

                default:
                    break
            }
        },
        improveRecipe(action, formData) {
            formData.append('recipe_id', this.recipe.id)
            formData.append('book_id', this.book.id)

            if (!this.recipe.file) {
                formData.append('image', this.recipe.img)
            }

            if (action === 'newImprovedRecipe') {
                formData.append('_method', 'POST')
            } else if (action === 'editImprovedRecipe') {
                formData.append('_method', 'PUT')
            }

            const url = `${this.$app_url}receptar/${this.book.id}/recept/${this.editableRecipe.id}`
            axios
                .post(url, formData)
                .then((res) => {
                    console.log(res.data)
                    const message = this.$t('global.saved')
                    this.saved(message, url)
                })
                .catch((error) => {
                    console.log(error)
                    this.errors = error.response.data.errors
                })
            return
        },
        newOrUpdateRecipe(action, formData) {
            let url = null
            let reqUrl = null
            if (action === 'newRecipe') {
                formData.append('_method', 'POST')
                reqUrl = `${this.$app_url}recept`
                url = this.$app_url
            } else if (action === 'editRecipe') {
                formData.append('_method', 'PUT')
                reqUrl = `${this.$app_url}recept/${this.editableRecipe.id}`
                url = reqUrl
            }
            axios
                .post(reqUrl, formData)
                .then((response) => {
                    const message = this.$t('global.saved')
                    this.saved(message, url)
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
            return
        },
        updateIngredients(data) {
            this.recipe.ingredients = data.filter(function (el) {
                return el != null
            })
        },
        updateAproach(data) {
            this.recipe.approach = data.filter(function (el) {
                return el != null
            })
        },
        textareaResize() {
            this.$refs.descTextarea.style.height = 'auto'
            this.$refs.descTextarea.style.height =
                this.$refs.descTextarea.scrollHeight + 'px'
        },
        updateImage(data) {
            this.recipe.file = data
        },
    },
    computed: {
        recipeImage: function () {
            switch (this.action) {
                case 'editImprovedRecipe':
                    return this.$app_url + this.recipe.image.url
                case 'editRecipe' || 'newImprovedRecipe':
                    return this.$storage_path + this.recipe.image.url
                default:
                    return null
            }
        },
    },

    created() {
        window.addEventListener('resize', this.onResize)

        if (
            this.action === 'editRecipe' ||
            this.action === 'newImprovedRecipe' ||
            this.action === 'editImprovedRecipe'
        ) {
            Object.assign(this.originalRecipe, this.editableRecipe)
            this.recipe = this.editableRecipe
            this.categories = this.selectedCategories
            if (this.action === 'editImprovedRecipe') {
                this.withImprovement(this.recipe.improvement)
            }
        } else {
            axios.get(`${this.$app_url}category`).then((res) => {
                this.categories = res.data
                this.categories.forEach((category) => {
                    this.$set(category, 'selected', false)
                })
            })
        }
    },
    mounted() {
        this.textareaResize()
    },
    destroyed() {
        window.removeEventListener('resize', this.onResize)
    },
}
</script>

<style lang="scss">
.custom-img-width {
    width: 40rem;
}
.custom-small-text {
    font-size: 0.62rem /* 12px */;
    line-height: 1rem /* 16px */;
}
</style>
