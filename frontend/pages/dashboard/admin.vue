<template>
  <p>Vous êtes dans la partie admin</p>
</template>

<script setup>
    definePageMeta({
        middleware: 'is-admin'
    })
</script>

<style>

</style>