<script setup lang="ts">
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { TrendingUp, Users, Calculator, DollarSign, Calendar, Target, FileBarChart, Activity } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Active tab state
const activeTab = ref('ventas')

// Sample data for different areas (placeholder data)
const ventasMetrics = [
    { title: 'Ventas Este Mes', value: '$45,231.89', change: '+20.1%', icon: DollarSign },
    { title: 'Prospectos', value: '2,350', change: '+180.1%', icon: Target },
    { title: 'Tasa de Conversión', value: '12.5%', change: '+19%', icon: TrendingUp },
    { title: 'Meta Mensual', value: '75%', change: '+4.75%', icon: Calendar },
]

const nominaMetrics = [
    { title: 'Empleados Activos', value: '254', change: '+2.5%', icon: Users },
    { title: 'Nómina Mensual', value: '$890,231.00', change: '+3.2%', icon: DollarSign },
    { title: 'Horas Trabajadas', value: '8,924', change: '+12.3%', icon: Activity },
    { title: 'Costo Promedio', value: '$3,504', change: '-1.2%', icon: Calculator },
]

const contabilidadMetrics = [
    { title: 'Ingresos Totales', value: '$1,245,231.89', change: '+15.3%', icon: DollarSign },
    { title: 'Gastos Totales', value: '$892,540.12', change: '+8.7%', icon: Calculator },
    { title: 'Utilidad Neta', value: '$352,691.77', change: '+25.8%', icon: TrendingUp },
    { title: 'Reportes Pendientes', value: '7', change: '-12.5%', icon: FileBarChart },
]
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">📊 Dashboard Ejecutivo</h1>
                <p class="text-gray-600 mt-2">Vista general de todas las áreas de negocio</p>
            </div>

            <Tabs v-model="activeTab" class="space-y-6">
                <TabsList class="grid w-full grid-cols-3">
                    <TabsTrigger value="ventas" class="flex items-center gap-2">
                        <TrendingUp class="h-4 w-4" />
                        Ventas
                    </TabsTrigger>
                    <TabsTrigger value="nomina" class="flex items-center gap-2">
                        <Users class="h-4 w-4" />
                        Nómina
                    </TabsTrigger>
                    <TabsTrigger value="contabilidad" class="flex items-center gap-2">
                        <Calculator class="h-4 w-4" />
                        Contabilidad
                    </TabsTrigger>
                </TabsList>

                <!-- Ventas Dashboard -->
                <TabsContent value="ventas" class="space-y-6">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <Card v-for="metric in ventasMetrics" :key="metric.title">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    {{ metric.title }}
                                </CardTitle>
                                <component :is="metric.icon" class="h-4 w-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ metric.value }}</div>
                                <p class="text-xs text-muted-foreground">
                                    <span :class="metric.change.startsWith('+') ? 'text-green-600' : 'text-red-600'">
                                        {{ metric.change }}
                                    </span>
                                    vs mes anterior
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>📈 Tendencia de Ventas</CardTitle>
                            </CardHeader>
                            <CardContent class="h-[300px] flex items-center justify-center">
                                <p class="text-gray-500">Gráfico de tendencias - próximamente</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>🎯 Top Productos</CardTitle>
                            </CardHeader>
                            <CardContent class="h-[300px] flex items-center justify-center">
                                <p class="text-gray-500">Ranking de productos - próximamente</p>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Nómina Dashboard -->
                <TabsContent value="nomina" class="space-y-6">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <Card v-for="metric in nominaMetrics" :key="metric.title">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    {{ metric.title }}
                                </CardTitle>
                                <component :is="metric.icon" class="h-4 w-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ metric.value }}</div>
                                <p class="text-xs text-muted-foreground">
                                    <span :class="metric.change.startsWith('+') ? 'text-green-600' : 'text-red-600'">
                                        {{ metric.change }}
                                    </span>
                                    vs mes anterior
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>👥 Distribución por Departamento</CardTitle>
                            </CardHeader>
                            <CardContent class="h-[300px] flex items-center justify-center">
                                <p class="text-gray-500">Gráfico de distribución - próximamente</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>📊 Evolución Nómina</CardTitle>
                            </CardHeader>
                            <CardContent class="h-[300px] flex items-center justify-center">
                                <p class="text-gray-500">Gráfico de evolución - próximamente</p>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Contabilidad Dashboard -->
                <TabsContent value="contabilidad" class="space-y-6">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <Card v-for="metric in contabilidadMetrics" :key="metric.title">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    {{ metric.title }}
                                </CardTitle>
                                <component :is="metric.icon" class="h-4 w-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">{{ metric.value }}</div>
                                <p class="text-xs text-muted-foreground">
                                    <span :class="metric.change.startsWith('+') ? 'text-green-600' : 'text-red-600'">
                                        {{ metric.change }}
                                    </span>
                                    vs mes anterior
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>💰 Flujo de Caja</CardTitle>
                            </CardHeader>
                            <CardContent class="h-[300px] flex items-center justify-center">
                                <p class="text-gray-500">Gráfico de flujo de caja - próximamente</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>📋 Estado Financiero</CardTitle>
                            </CardHeader>
                            <CardContent class="h-[300px] flex items-center justify-center">
                                <p class="text-gray-500">Resumen financiero - próximamente</p>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
