<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LayoutGrid } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
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
import type { Auth } from '@/types/auth';
import type { NavItem } from '@/types';

const page = usePage<{ auth: Auth }>();
const homeHref = computed(() => (page.props.auth.user?.is_admin ? '/admin' : '/'));
const homeTitle = computed(() => (page.props.auth.user?.is_admin ? 'Admin Dashboard' : 'Home'));

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: homeTitle.value,
        href: homeHref.value,
        icon: LayoutGrid,
    },
]);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="homeHref">
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
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
