<template>
    <div>
        <div v-if="images" class="w-60">
            <transition-group name="fade">
                <div
                    class="flex items-center justify-between my-1"
                    v-for="(image, index) in images"
                    :key="image.name">
                    <div class="flex items-center justify-start">
                        <figure
                            class="flex items-center justify-center overflow-hidden w-10 h-5 rounded-sm">
                            <img
                                class="w-full object-cover rounded-lg"
                                :src="preview[index]" />
                        </figure>
                        <span class="text-xs text-gray-400 ml-2">
                            {{ stringLimiter(image.name, 20) }}
                        </span>
                    </div>
                    <svg
                        @click="removeImage(index)"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-red-600 cursor-pointer"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg></div
            ></transition-group>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        images: {
            type: Array,
            default: null,
        },
    },
    data() {
        return {
            preview: [],
        }
    },
    methods: {
        removeImage(index) {
            this.$emit('deleteImage', index)
        },
        showImages(images) {
            for (let i = 0; i < images.length; i++) {
                let reader = new FileReader()
                reader.onload = (e) => {
                    this.preview.push(reader.result)
                }

                reader.readAsDataURL(images[i])
            }
        },
    },
    watch: {
        images: function (val) {
            this.showImages(this.images)
        },
    },
    created() {
        this.showImages(this.images)
    },
}
</script>

<style lang="scss" scoped></style>
