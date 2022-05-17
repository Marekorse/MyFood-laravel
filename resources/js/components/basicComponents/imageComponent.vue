<template>
    <figure class="flex items-center overflow-hidden">
        <img
            draggable="false"
            @click="showImage()"
            class="w-full"
            :class="{ pointer: showOnClick }"
            :src="imgSrc"
            alt="" />
        <modal v-if="showModal" @closeModal="showModal = false">
            <img
                class="w-full h-auto md:w-5/6 xl:w-3/6 rounded-lg border-4 border-gray-100"
                :src="path + image.url"
                alt="" />
        </modal>
    </figure>
</template>

<script>
import modal from './modal.vue'
export default {
    data: function () {
        return {
            path: this.$storage_path,
            showModal: false,
            imgSrc: null,
        }
    },
    components: {
        modal,
    },
    props: {
        image: {
            type: [Object, String],
            default: null,
        },
        showOnClick: {
            type: Boolean,
            default: false,
        },
        thumb: {
            type: Boolean,
            default: false,
        },
        private: {
            type: Boolean,
            default: false,
        },

        defaultImage: {
            type: String,
            default: 'images/Default/cover.jpg',
        },
    },
    watch: {
        image: {
            handler(newValue, oldValue) {
                if (typeof newValue === 'object') {
                    this.imageCheck()
                } else {
                    this.imgSrc = newValue
                }
            },
            deep: true,
        },
    },
    methods: {
        isImage(i) {
            return i instanceof HTMLImageElement
        },
        imageCheck() {
            if (this.private) {
                this.path = this.$app_url
            }
            if (this.image && this.thumb) {
                this.imgSrc = this.path + this.image.thumbnail_url
            } else if (this.image && !this.thumb) {
                this.imgSrc = this.path + this.image.url
            } else if (!this.image) {
                this.imgSrc = this.path + this.defaultImage
            }
        },
        showImage() {
            if (this.showOnClick && this.defaultImage != this.imgSrc) {
                this.showModal = true
            }
            return
        },
    },
    created() {
        this.imageCheck()
    },
}
</script>
<style lang="scss" scoped>
.pointer {
    cursor: pointer;
}
</style>
