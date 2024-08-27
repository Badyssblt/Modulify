import { useAuth } from "@/store/auth";
import { jwtDecode } from "jwt-decode";

export default defineNuxtRouteMiddleware((to, from) => {
    const store = useAuth();
    const user = store.user;
    const token = store.token;

    const decoded = jwtDecode(token);

    if(decoded.roles && !decoded.roles.includes("ROLE_ADMIN")){
        if(to.path !== '/'){
            return navigateTo('/');
        }
    }

})