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
import { BookOpen, Github, LayoutGrid, Users, Shield, KeyRound, ScrollText, TrendingUp, Database, Settings, Receipt } from 'lucide-vue-next';
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

// Data capture section - forms for capturing different areas data
const capturasItems: NavItem[] = [
    {
        title: 'Capturas',
        icon: Database,
        items: [
            {
                title: 'Ventas',
                href: '/monthly-sales',
                icon: TrendingUp,
            },
            {
                title: 'Facturación',
                href: '/monthly-billing',
                icon: Receipt,
            },
        ],
    },
];

// Datos Brutos section - raw data viewing per area
const datosBrutosItems: NavItem[] = [
    {
        title: 'Datos Brutos',
        icon: ScrollText,
        items: [
            {
                title: 'Ventas',
                href: '/monthly-sales/list',
                icon: TrendingUp,
            },
            {
                title: 'Facturación',
                href: '/monthly-billing/list',
                icon: Receipt,
            },
        ],
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

// Configuration section - system settings and management
const configurationItems: NavItem[] = [
    {
        title: 'Configuración',
        icon: Settings,
        items: [
            {
                title: 'Unidades',
                href: '/units',
                icon: Database,
            },
        ],
    },
];

// Combined navigation items based on permissions
const mainNavItems = computed(() => [
    ...baseMainNavItems,
    ...capturasItems,
    ...datosBrutosItems,
    ...configurationItems,
    ...userManagementItems.value,
]);

// Footer navigation items (some are permission-based)
const footerNavItems = computed(() => {
    const items: NavItem[] = [];
    
    // Logs link - only for users with view_logs permission
    if (hasPermission('view_logs')) {
        items.push({
            title: 'Bitácora',
            href: '/logs',
            icon: ScrollText,
        });
    }
    
    // Always visible items
    items.push(
        {
            title: 'Github Repo',
            href: 'https://github.com/SeSoDi/mpet',
            icon: Github,
        },
        {
            title: 'Documentation',
            href: 'https://laravel.com/docs/starter-kits#vue',
            icon: BookOpen,
        }
    );
    
    return items;
});
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
