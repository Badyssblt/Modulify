<template>
  <div class="px-6">
    <div>
        <div>
            <h2 class="text-lg font-medium mb-2">Rechercher des composants</h2>
            <form>
                <Input placeholder="Symfony template..." color="secondary" v-model="query" @input="handleSearchInput"/>
            </form>
        </div>
        <div class="my-6">
            <p class="text-lg font-medium">Trier par</p>
            <form class="flex flex-col md:flex-row gap-6">
                <div>
                    <label for="date">Date</label>
                    <div>
                        <div>
                            <input type="radio" name="date" value="new" id="new" v-model="date" @change="handleDateChange">
                            <label for="new">Du plus récent</label>
                        </div>
                        <div>
                            <input type="radio" name="date" value="old" id="old" v-model="date" @change="handleDateChange">
                            <label for="old">Du plus ancien</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="date">Tendance</label>
                    <div>
                        <div>
                            <input type="radio" name="tendance" value="more_tendance" id="more_tendance" v-model="trending" @change="handleTrendingChange">
                            <label for="more_tendance">Les plus tendances</label>
                        </div>
                        <div>
                            <input type="radio" name="tendance" value="less_tendance" id="less_tendance" v-model="trending" @change="handleTrendingChange">
                            <label for="less_tendance">Les moins tendances</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="date">Prix</label>
                    <div>
                        <div>
                            <input type="radio" name="price" value="more_price" id="more_price" v-model="price" @change="handlePriceChange">
                            <label for="more_price">Du moins cher</label>
                        </div>
                        <div>
                            <input type="radio" name="price" value="less_price" id="less_price" v-model="price" @change="handlePriceChange">
                            <label for="less_price">Du plus cher</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <Wrapper class="mt-4 relative z-20">
        <Card v-for="asset in assets" :key="asset.id" :asset="asset" />
      </Wrapper>
  </div>
</template>

<script setup>

    const date = ref('');
    const trending = ref('');
    const price = ref('');
    const query = ref('');
    const originalAssets = ref([]);

    const nuxt = useNuxtApp()

    const config = useRuntimeConfig();
    const { data: assets, refresh, pending, error } = await useFetch(config.public.API_URL + '/api/assets', {
      key: 'unique-key',
      getCachedData: (key) => null,
      immediate: true, 
    });
        

    originalAssets.value = [...assets.value];

    const sortAssets = () => {
  let sortedAssets = [...originalAssets.value]; 

  // Filtrer par nom si une requête est présente
  if (query.value) {
    sortedAssets = sortedAssets.filter(asset =>
      asset.name.toLowerCase().includes(query.value.toLowerCase())
    );
  }

  // Trier par date
  if (date.value === 'new') {
    sortedAssets.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  } else if (date.value === 'old') {
    sortedAssets.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  }

  // Trier par tendance
  if (trending.value === 'more_tendance') {
    sortedAssets.sort((a, b) => b.total_download - a.total_download);
  } else if (trending.value === 'less_tendance') {
    sortedAssets.sort((a, b) => a.total_download - b.total_download);
  }

  // Trier par prix
  if (price.value === 'more_price') {
    sortedAssets.sort((a, b) => a.price - b.price);
  } else if (price.value === 'less_price') {
    sortedAssets.sort((a, b) => b.price - a.price);
  }

  assets.value = sortedAssets;
};

const handleDateChange = () => sortAssets();
const handleTrendingChange = () => sortAssets();
const handlePriceChange = () => sortAssets();
const handleSearchInput = () => {
  // Réinitialiser les critères de tri si la recherche est vide
  if (!query.value) {
    date.value = '';
    trending.value = '';
    price.value = '';
  }
  sortAssets();
};



</script>

<style>

</style>