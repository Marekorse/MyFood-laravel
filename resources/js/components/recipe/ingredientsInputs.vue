<template>
    <div class="mx-auto">
        <draggable
            class="bg-white cursor-pointer"
            v-model="inputs"
            group="people"
            :animation="200"
            @change="sendChanges()">
            <transition-group name="fade">
                <div v-for="(input, index) in inputs" :key="input.id">
                    <div class="flex justify-center items-center">
                        <li
                            class="w-full px-7 border-dashed border-b-2 list-none relative">
                            <input
                                @input="sendChanges()"
                                v-model="input.name"
                                type="text"
                                name="ingredients[name]"
                                id=""
                                class="border-0 rounded-xl w-full break break-words focus:outline-none focus:ring-0 text-xl placeholder-gray-300"
                                :placeholder="
                                    $t('recipeForm.ingredientPlaceholder')
                                " />
                            <input
                                name="ingredients[id]"
                                type="hidden"
                                :value="input.id" />
                        </li>
                        <div>
                            <svg
                                @click="remove(index)"
                                v-show="index || (!index && inputs.length > 1)"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-red-700 cursor-pointer"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                            <svg
                                @click="add(input, index)"
                                v-show="index == inputs.length - 1"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-green-600 cursor-pointer"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </transition-group>
        </draggable>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
export default {
    components: {
        draggable,
    },
    props: { ingredients: Array },
    data: function () {
        return {
            inputs: this.ingredients,
            lastId: 0,
        }
    },
    created() {
        if (this.inputs.length) {
            this.lastId = this.inputs[this.inputs.length - 1].id
        }
    },
    methods: {
        sendChanges() {
            this.$emit('ingredientsChange', this.inputs)
        },
        add(input) {
            this.inputs.push({
                id: (this.lastId += 1),
                name: '',
            })
        },
        remove(input) {
            this.inputs.splice(this.inputs.indexOf(input), 1)
        },
    },
}
</script>
