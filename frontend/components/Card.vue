<template>
  <NuxtLink :to="`/asset/${asset.id}`" class="bg-secondary w-full h-fit rounded-md block md:w-96">
    <article class="w-full h-fit">
      <div class="w-full h-48 relative overflow-hidden">
        <img :src="$config.public.API_URL + '/images/asset/' + asset.imageName" alt="" class="absolute inset-0 w-full h-full object-cover rounded-t-md">
      </div>
      <div class="py-3 px-4">
        <div class="flex justify-between">
          <h2 class="font-medium">{{ asset.name }}</h2>
          <h3 class="font-medium">{{ asset.price === 0 ? 'Gratuit' : asset.price + ' €' }}</h3>
        </div>
        <div class="mt-3">
          <div class="flex items-center gap-2">
            <div class="w-12 h-12 relative overflow-hidden">
              <img :src="asset.author.imageName !== 'default' ? config.public.API_URL + '/' + asset.author.imageName : 'images/default.jpg'" alt="" class="absolute inset-0 w-full h-full object-cover rounded-full">
            </div>
            <p>{{ asset.author.name }}</p>
          </div>
        </div>
      </div>
    </article>
  </NuxtLink>
</template>

<script setup>

const config = useRuntimeConfig();
const props = defineProps({
  asset: {
    type: Object,
  }
});


</script>

<style>
</style>
