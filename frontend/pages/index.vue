<template>
  <div class="h-96">
    <h2 class="font-medium text-center text-lg">
      Utiliser des composants prêt à l'emploi dans vos projets d'application web
    </h2>
    <p class="text-sm text-center text-white/60 my-2 mb-4">
      Vous pouvez télécharger, ou directement importer <br />
      sur votre repository Github des composants <br />
      qui sont prêt à l’emploi
    </p>
    <div>
      <Button class="w-full">S'inscrire</Button>
      <Input placeholder="Symfony template..." class="mt-2" />
    </div>
  </div>
  <div class="mt-24">
    <div class="flex flex-col gap-4">
      <Button
        :class="{
          'bg-primary border border-primary': activeSort === 'created_at',
          'bg-transparent border border-primary': activeSort !== 'created_at'
        }"
        @click="activeSort = 'created_at'"
      >
        Dernier ajout
      </Button>
      <Button
        :class="{
          'bg-primary border border-primary': activeSort === 'total_download',
          'bg-transparent border border-primary': activeSort !== 'total_download'
        }"
        @click="activeSort = 'total_download'"
      >
        Populaires
      </Button>
    </div>
    <Wrapper class="mt-4">
      <Card v-for="asset in sortedAssets" :key="asset.id" :asset="asset" />
    </Wrapper>
  </div>
</template>

<script setup>

const config = useRuntimeConfig();
const { data: assets } = useFetch(config.public.API_URL + '/api/assets');

const activeSort = ref('created_at');

const sortedAssets = computed(() => {
  if (!assets.value) return []; 

  return [...assets.value].sort((a, b) => {
    if (activeSort.value === 'created_at') {
      return new Date(b.created_at) - new Date(a.created_at); 
    } else if (activeSort.value === 'total_download') {
      return b.total_download - a.total_download; 
    }
    return 0;
  });
});
</script>

