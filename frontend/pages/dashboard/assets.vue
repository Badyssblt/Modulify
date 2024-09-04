<template>
    <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-96 left-2">
      </div>
        <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-44 left-72">
        </div>
        <div class="absolute w-32 h-32 bg-primary rounded-full blur-[90px] top-72 right-0">
        </div>
    <div class="px-6 md:flex relative z-50">
         <DashboardAside current="assets"/>
          <div class="md:flex md:flex-col md:items-center w-full">
              <h2 class="text-center text-lg mt-8 font-medium">Mes assets suivis</h2>
              <Warning v-if="assets && assets.length === 0" class="mt-4 w-96">Vous ne suivez aucun asset</Warning>
              <Wrapper class="mt-4 relative z-20 justify-center">
                <Card v-for="asset in assets" :asset="asset">
                </Card>
              </Wrapper>
          </div>
    </div>
  </template>
  
  <script setup>
  import { useAuth } from "@/store/auth"
  
      const store = useAuth();
  
      const name = ref('');
      const email = ref('');
      const password = ref('');
  
      const isDisabled = ref(false);
      const error = ref(null);

      const { $api } = useNuxtApp();
      const config = useRuntimeConfig();

      const { data: assets, error: fetchError } = useFetch(config.public.API_URL + '/api/assets/followed', {
        onRequest({ request, options }) {
          options.headers = options.headers || {};
          options.headers.authorization = store.token ? `Bearer ${store.token}` : '';
        }
      });


      const handleSubmit = async () => {
        error.value = null;
        if(email.value === '' || name.value === '' || password.value === ''){
          error.value = 'Veuillez remplir tous les champs';
          return;
        }
       try {
          const response = await $api.patch('/api/user', {
            name: name.value,
            email: email.value,
            password: password.value
          });
          store.logout();
          navigateTo('/login');
        } catch (error) {
          console.log(error);
          error.value = 'Une erreur est survenue lors de la modification de vos informations, veuillez essayez de vous reconnecter...'
        }
      }
  
      onMounted(() => {
        if(store.user.githubToken){
          isDisabled.value = true;
        }
  
        email.value = store.user.email;
        name.value = store.user.name;

        if(!assets.value){
          console.error("Assets data are null");
        }
      })
  
  </script>
  
  <style>
  
  </style>