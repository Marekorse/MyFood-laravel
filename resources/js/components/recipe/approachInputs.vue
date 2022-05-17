<template>
    <div class="">
        <draggable
            class="bg-white cursor-pointer"
            v-model="inputs"
            group="people"
            :animation="200"
            @change="sendChanges()">
            <transition-group name="fade" tag="ol">
                <div v-for="(input, index) in inputs" :key="input.id">
                    <div class="flex justify-center items-center w-full">
                        <li class="list-none w-full">
                            <textarea
                                ref="approach"
                                @change="textareaResize(index)"
                                @input="sendChanges()"
                                v-model="input.name"
                                name="approach[]"
                                id=""
                                cols="30"
                                class="w-full border-0 focus:outline-none focus:ring-0 text-xl placeholder-gray-300 border-l-4 border-green-500 focus:border-green-500 my-2 resize-none"
                                :placeholder="
                                    $t('recipeForm.approachPlaceholder')
                                "></textarea>
                        </li>

                        <div>
                            <svg
                                @click="remove(input)"
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
                                @click="add(input)"
                                v-show="index == inputs.length - 1"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-green-600 cursor-pointer hover:scale-110"
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
    props: { approach: Array },
    data: function () {
        return {
            inputs: this.approach,
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
            this.$emit('approachChange', this.inputs)
        },
        textareaResize(index) {
            this.$refs.approach[index].style.height = 'auto'
            this.$refs.approach[index].style.height =
                this.$refs.approach[index].scrollHeight + 'px'
        },
        ResizeAll() {
            for (let index = 0; index < this.inputs.length; index++) {
                this.$refs.approach[index].style.height = 'auto'
                this.$refs.approach[index].style.height =
                    this.$refs.approach[index].scrollHeight + 'px'
            }
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
    mounted() {
        this.ResizeAll()
    },
}
</script>
