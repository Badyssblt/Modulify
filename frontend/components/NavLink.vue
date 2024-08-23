<template>
  <NuxtLink :to="to" class="bg-primary px-4 py-2 rounded-full flex justify-center">
    <slot/>
  </NuxtLink>
</template>

<script setup>
  defineProps({
    to: {
      type: String,
      default: "/"
    }
  })
</script>

<style>

</style>