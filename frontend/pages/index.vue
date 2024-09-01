  <template>
      <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-96 left-2">
      </div>
      
      <div class="hidden md:block absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-72 left-[50%]">
      </div>
      <div class="h-96 px-6 md:flex md:justify-between md:px-12 gap-x-4 md:gap-[10%] md:mt-12">
      <div>
        <h2 class="font-medium text-center text-lg md:text-3xl md:text-start">
          Utiliser des composants prêt à l'emploi dans vos projets d'application web
        </h2>
        <p class="text-sm text-center text-white/60 my-2 mb-4 md:text-base md:text-start">
          Vous pouvez télécharger, ou directement importer
          sur votre repository Github<br/> des composants 
          qui sont prêt à l’emploi
        </p>
        <div class="md:flex md:gap-4">
          <div class="flex items-center md:w-auto w-full">
            <NavLink class="w-full md:w-auto md:px-12 md:text-nowrap" to="/register" v-if="!store.isAuthenticated">
              S'inscrire
            </NavLink>
            <NavLink class="w-full md:w-auto md:px-12 md:text-nowrap" to="/dashboard" v-else>
              Mon compte
            </NavLink>
          </div>
          <Input
            placeholder="Symfony template..."
            class="mt-2 md:mt-0 md:flex-grow md:w-full"
            color="secondary"
            v-model="title"
            @keyup.enter="submitSearch"
          />
        </div>
      </div>
      <div class="hidden lg:flex gap-4 lg:relative lg:z-50">
          <div class="flex flex-col gap-2">
              <div class="w-60 h-48 bg-secondary py-8 px-6 rounded-md">
                <h2 class="text-sm text-center">Partagez vos composants avec la communauté</h2>
              </div>
              <div class="w-60 h-48 bg-primary py-8 px-6 rounded-md">
                <h2 class="text-sm text-center">Intégrez vos composants avec Github</h2>
              </div>
          </div>
          <div class="flex flex-col gap-6 -mb-8 mt-8">
              <div class="w-60 h-48 bg-primary py-8 px-6 rounded-md">
                <h2 class="text-sm text-center">Du simple composant, au template</h2>
              </div>
              <div class="w-60 h-48 bg-secondary py-8 px-6 rounded-md">
                <h2 class="text-sm text-center">De multiples langages</h2>
              </div>
          </div>
          
      </div>
      

    </div>
    <div class="mt-24 md:mt-32 px-6 relative pb-12">

      <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-24 right-4">
      </div>
      <div class="flex flex-col md:flex-row md:justify-center gap-4">
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
      <Wrapper class="mt-12 relative z-20 md:justify-center ">
        <Card v-for="asset in sortedAssets" :key="asset.id" :asset="asset" />
      </Wrapper>
    </div>
  </template>

<script setup>
import { useAuth } from "@/store/auth"

useHead({
  title: 'Accueil'
})

    const title = ref('');

    const store = useAuth();
  const config = useRuntimeConfig();
  const { data: assets } = await useFetch(config.public.API_URL + '/api/assets', {
    key: 'unique-key',
    getCachedData: (key) => null,
    immediate: true
  });

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


  const router = useRouter();
  const submitSearch = () => {
  if (title.value.trim()) {
    router.push({ path: "/assets", query: { title: title.value } });
  }
};
</script>

