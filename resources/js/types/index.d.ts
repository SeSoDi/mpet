import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href?: string;
}

export interface NavItem {
    title: string;
    href?: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    items?: NavItem[];
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles?: Role[];
}

export interface Role {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
    permissions?: Permission[];
    users?: User[];
    users_count?: number;
}

export interface Permission {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
    roles?: Role[];
    roles_count?: number;
}

export type BreadcrumbItemType = BreadcrumbItem;
