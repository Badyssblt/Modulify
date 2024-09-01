<template>
  <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-96 left-2">
    </div>
      <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-44 left-72">
      </div>
      <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-72 right-0">
      </div>
  <div class="px-6 md:px-12">
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
                      <div class="flex gap-2 items-center">
                        <input
                          type="radio"
                          name="date"
                          value="new"
                          id="new"
                          v-model="date"
                          @change="handleDateChange"
                          class="hidden peer"
                        />
                        <label for="new" class="square-radio peer-checked:bg-primary"></label>
                        <label for="new">Du plus récent</label>
                      </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="date" value="old" id="old" v-model="date" @change="handleDateChange" class="hidden peer">
                            <label for="old" class="square-radio peer-checked:bg-primary"></label>
                            <label for="old">Du plus ancien</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="date">Tendance</label>
                    <div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="tendance" value="more_tendance" id="more_tendance" v-model="trending" @change="handleTrendingChange" class="hidden peer"> 
                            <label for="more_tendance" class="square-radio peer-checked:bg-primary"></label>
                            <label for="more_tendance">Du plus tendance</label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="tendance" value="less_tendance" id="less_tendance" v-model="trending" @change="handleTrendingChange" class="hidden peer">
                            <label for="less_tendance" class="square-radio peer-checked:bg-primary"></label>
                            <label for="less_tendance">Du moins tendance</label>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="date">Prix</label>
                    <div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="price" value="more_price" id="more_price" v-model="price" @change="handlePriceChange" class="hidden peer">
                            <label for="more_price" class="square-radio peer-checked:bg-primary"></label>
                            <label for="more_price">Du moins cher</label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="price" value="less_price" id="less_price" v-model="price" @change="handlePriceChange" class="hidden peer">
                            <label for="less_price" class="square-radio peer-checked:bg-primary"></label>
                            <label for="less_price">Du plus cher</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <Wrapper class="mt-4 relative z-20">
        <Card v-for="asset in assets " :key="asset.id" :asset="asset" />
      </Wrapper>
  </div>
</template>

<script setup>


const route = useRoute();

const date = ref('');
const trending = ref('');
const price = ref('');
const query = ref(route.query.title || '');

const config = useRuntimeConfig();
const { data: fetchedAssets } = await useFetch(config.public.API_URL + '/api/assets', {
  key: 'unique-key',
  getCachedData: (key) => null,
  immediate: true, 
  default: () => []
});

const assets = ref([...fetchedAssets.value]);

const sortAssets = () => {
  let sortedAssets = [...fetchedAssets.value]; 

  if (query.value) {
    sortedAssets = sortedAssets.filter(asset =>
      asset.name.toLowerCase().includes(query.value.toLowerCase())
    );
  }

  if (date.value === 'new') {
    sortedAssets.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  } else if (date.value === 'old') {
    sortedAssets.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  }

  if (trending.value === 'more_tendance') {
    sortedAssets.sort((a, b) => b.total_download - a.total_download);
  } else if (trending.value === 'less_tendance') {
    sortedAssets.sort((a, b) => a.total_download - b.total_download);
  }

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
  if (!query.value) {
    date.value = '';
    trending.value = '';
    price.value = '';
    assets.value = [...fetchedAssets.value];  
  }
  sortAssets();
};

onMounted(() => {
  if(query.value !== ''){
    sortAssets();
  }
})

watch(fetchedAssets, () => {
  assets.value = [...fetchedAssets.value];
});

</script>
<style scoped>
.square-radio {
  width: 20px; 
  height: 20px; 
  border: 1px solid rgba(141, 141, 141, 0.64); 
  display: inline-block;
  cursor: pointer;
  border-radius: 2px;
  vertical-align: middle; 
  background: #1C2A33;
}

input[type="radio"].hidden {
  display: none;
}




</style>