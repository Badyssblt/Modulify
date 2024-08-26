import axios from "axios";
import Cookies from "js-cookie";

export default defineNuxtPlugin((nuxtApp) => {
  const config = nuxtApp.$config
  // Créer une instance d'Axios avec une configuration de base
  const api = axios.create({
    baseURL: config.public.API_URL,
    timeout: 10000,
  });

  // Intercepteurs de requêtes
  api.interceptors.request.use((config) => {
    const token = Cookies.get("token") ? Cookies.get("token") : "";
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  });

  // Intercepteurs de réponses
  api.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response && error.response.status === 401) {
        nuxtApp.$auth?.logout();
      }
      return Promise.reject(error);
    }
  );

  // Injecter l'instance Axios dans l'application sous le nom $api
  nuxtApp.provide("api", api);
});
