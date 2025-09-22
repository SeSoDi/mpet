<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { usePermissions } from '@/composables/usePermissions';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Github, LayoutGrid, Users, Shield, KeyRound } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const { canManageUsers, canManageRoles, hasPermission } = usePermissions();

// Base navigation items
const baseMainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

// User management section - only show if user has any user management permissions
const userManagementItems = computed((): NavItem[] => {
    if (!canManageUsers.value) return [];
    
    const userAdminItems: NavItem[] = [];
    
    // Add Users submenu if user can view users
    if (hasPermission('view_users')) {
        userAdminItems.push({
            title: 'Usuarios',
            href: '/users',
            icon: Users,
        });
    }
    
    // Add Roles submenu if user can manage roles
    if (canManageRoles.value) {
        userAdminItems.push({
            title: 'Roles',
            href: '/roles',
            icon: Shield,
        });
    }
    
    // Add Permissions submenu if user can manage roles (permissions are managed through roles)
    if (canManageRoles.value) {
        userAdminItems.push({
            title: 'Permisos',
            href: '/permissions',
            icon: KeyRound,
        });
    }
    
    // Only show the section if there are items to display
    if (userAdminItems.length === 0) return [];
    
    return [{
        title: 'Administración de Usuarios',
        icon: Users,
        items: userAdminItems,
    }];
});

// Combined navigation items based on permissions
const mainNavItems = computed(() => [
    ...baseMainNavItems,
    ...userManagementItems.value,
]);

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/SeSoDi/mpet',
        icon: Github,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
