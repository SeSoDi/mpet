<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const openItems = ref<Record<string, boolean>>({});

// Function to check if any sub-item is active
const isSubItemActive = (item: NavItem): boolean => {
    if (!item.items) return false;
    return item.items.some(subItem => 
        subItem.href && urlIsActive(subItem.href, page.url)
    );
};

// Auto-expand sections with active sub-items
const initializeOpenItems = () => {
    props.items.forEach(item => {
        if (item.items && isSubItemActive(item)) {
            openItems.value[item.title] = true;
        }
    });
};

// Watch for route changes and update open items
watch(() => page.url, () => {
    initializeOpenItems();
}, { immediate: true });

const toggleItem = (title: string) => {
    openItems.value[title] = !openItems.value[title];
};

// Computed property to determine if an item should be open
const isItemOpen = (item: NavItem): boolean => {
    return openItems.value[item.title] || isSubItemActive(item);
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <!-- Regular menu item (no sub-items) -->
                <SidebarMenuButton
                    v-if="!item.items"
                    as-child
                    :is-active="item.href ? urlIsActive(item.href, page.url) : false"
                    :tooltip="item.title"
                >
                    <Link :href="item.href!">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>

                <!-- Collapsible menu item (with sub-items) -->
                <Collapsible v-else :open="isItemOpen(item)" @update:open="(open) => openItems[item.title] = open" as-child>
                    <div>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :tooltip="item.title"
                                :is-active="isSubItemActive(item)"
                                @click="toggleItem(item.title)"
                                :class="isSubItemActive(item) ? 'border-l-2 border-sidebar-accent' : ''"
                            >
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                                <ChevronRight 
                                    class="ml-auto transition-transform duration-200"
                                    :class="[
                                        isItemOpen(item) ? 'rotate-90' : '',
                                        isSubItemActive(item) ? 'text-sidebar-accent' : ''
                                    ]"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent as-child>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                    <SidebarMenuSubButton
                                        as-child
                                        :is-active="subItem.href ? urlIsActive(subItem.href, page.url) : false"
                                    >
                                        <Link :href="subItem.href!">
                                            <component :is="subItem.icon" />
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </div>
                </Collapsible>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
