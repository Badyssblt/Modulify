import { useAuth } from "@/store/auth";
import { jwtDecode } from "jwt-decode";  // Importation correcte de jwt-decode

export default defineNuxtRouteMiddleware((to, from) => {
    const store = useAuth();
    const token = store.token;

    // Vérifier si le jeton existe avant de le décoder
    if (!token) {
        console.warn("Jeton manquant, redirection vers la page de connexion.");
        return navigateTo('/login');
    }

    try {
        const decoded = jwtDecode(token);

        // Vérifiez les rôles de l'utilisateur
        if (decoded.roles && !decoded.roles.includes("ROLE_ADMIN")) {
            if (to.path !== '/') {
                console.warn("L'utilisateur n'est pas un administrateur, redirection vers la page d'accueil.");
                return navigateTo('/');
            }
        }
    } catch (error) {
        console.error("Erreur lors du décodage du jeton:", error);
        return navigateTo('/login');
    }
});
