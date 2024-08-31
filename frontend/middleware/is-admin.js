import { useAuth } from "@/store/auth";

export default defineNuxtRouteMiddleware((to, from) => {
  const store = useAuth();
  const user = store.user;


  if (user && user.role && !user.role.includes("ROLE_ADMIN")) {
    if (to.path !== '/') {
      return navigateTo('/');
    }
  }
});
