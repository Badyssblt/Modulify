<template>
  <button class="bg-primary px-4 py-2 rounded-full flex justify-center items-center gap-4">
    <slot/>
    <Loader v-if="state"/>
  </button>
</template>

<script setup>
  defineProps({
    state: {
      type: Boolean,
      default: false
    }
  })
</script>

<style>

</style>