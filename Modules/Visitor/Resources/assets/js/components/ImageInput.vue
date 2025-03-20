<template>
    <label for="" class="file_upload">
        <input type="file" class="file_upload__input" @change="uploadingFile" ref="uploadFile">
        <img :src="imagePreview ?? '/' + props.image" v-if="imagePreview ?? props.image" alt="" width="100" height="100"
             class="file_upload__preview img-circle">
    </label>
</template>

<script setup>

import {computed, ref} from "vue";

const imageFile = ref();
const props = defineProps({
    modelValue: File | String,
    image: String
})

const imagePreview = ref()

const emit = defineEmits(['update:modelValue'])
const uploadingFile = (e) => {
    imageFile.value = e.target.files[0]
    if (imageFile.value) {
        emit('update:modelValue', e.target.files[0])
        imagePreview.value = URL.createObjectURL(imageFile.value)
    }
}
</script>

