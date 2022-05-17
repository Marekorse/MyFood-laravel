<template>
    <div class="relative">
        <div
            @dragover.prevent
            @drop.prevent
            v-if="dropzone"
            class="flex items-center overflow-y-hidden w-full h-80 sm:h-64 md:h-96">
            <label
                @drop="dragFile"
                class="h-full w-full cursor-pointer border-4 border-dashed border-gray-300 rounded-lg"
                for="my-file">
                <div class="flex flex-col justify-center items-center h-full">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-48 w-48 text-gray-200"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h2 class="text-2xl text-gray-400 font-bold">
                        {{ $t('recipeForm.addPhoto') }}
                    </h2>
                </div>
            </label>
        </div>
        <input
            name="file"
            type="file"
            hidden
            @change="uploadFile"
            id="my-file" />
        <div v-if="preview" class="relative">
            <figure
                class="flex items-center overflow-y-hidden w-full h-96 z-50"
                v-if="preview">
                <img id="file" ref="image" :src="preview" class="" />
            </figure>
            <div
                class="bg-gradient-to-r from-green-400 to-green-500 hover:from-green-500 hover:to-green-400 transition duration-200 transform hover:-translate-y-1 rounded-xl text-white text-center px-5 py-1 absolute left-2 bottom-2 md:left-4 md:bottom-9">
                <button
                    class="text-sm font-semibold"
                    @click.prevent="changePhoto">
                    {{ $t('recipeForm.changePhoto') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: { oldImage: String },

    data: function () {
        return {
            preview: this.oldImage,
            image: null,
            dropzone: true,
        }
    },
    methods: {
        previewImage(input) {
            if (input.files) {
                let reader = new FileReader()
                reader.onload = (e) => {
                    this.preview = e.target.result
                }
                this.image = input.files[0]
                reader.readAsDataURL(input.files[0])
            }
            this.$emit('newImage', this.image)
            this.dropzone = false
        },

        changePhoto() {
            document.getElementById('my-file').click()
        },
        uploadFile(e) {
            this.previewImage(e.target)
        },
        dragFile(e) {
            this.previewImage(e.dataTransfer)
        },
    },
    created() {
        if (this.preview) {
            this.dropzone = false
        }
    },
}
</script>
