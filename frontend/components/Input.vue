<template>
  <label class="flex flex-col">
    {{ label }}
    <input
      :type="type"
      v-model="model"
      :class="[
        'text-white px-4 py-2 rounded-full focus:outline-none',
        color === 'primary' ? 'bg-background' : 'bg-secondary',
        disabled === true ? 'opacity-50' : ''
      ]"
      :placeholder="placeholder"
      :disabled="disabled"
    />
  </label>
</template>

<script setup>

    const model = defineModel();


    defineProps({
        label: String,
        type: {
            type: String,
            default: "text"
        },
        placeholder: {
            type: String,
            default: ""
        },
        color: {
            type: String,
            default: "primary"
        },
        disabled: {
          type: Boolean,
          default: false
        }
    })
</script>

<style>

</style>