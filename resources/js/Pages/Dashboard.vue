<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedPeriod = ref('hoy');

// Datos de KPIs coincidentes con el diseño
const kpis = [
    {
        title: 'Ventas del día',
        value: '$ 1.280.000',
        change: '+14.5%',
        isPositive: true,
        subtext: 'vs. día anterior'
    },
    {
        title: 'Productos vendidos',
        value: '184',
        change: '+8.2%',
        isPositive: true,
        subtext: '32 transacciones'
    },
    {
        title: 'Stock bajo',
        value: '12',
        change: '4 críticos',
        isPositive: false,
        subtext: 'requiere pedido'
    },
    {
        title: 'Domicilios',
        value: '23',
        change: '19 entregados',
        isPositive: true,
        subtext: '4 en camino'
    }
];

// Datos del gráfico de ventas por horario
const hourlySales = [
    { hour: '4pm', amount: '$ 95.000', sales: 14, heightPercentage: 28 },
    { hour: '6pm', amount: '$ 140.000', sales: 21, heightPercentage: 42 },
    { hour: '8pm', amount: '$ 230.000', sales: 34, heightPercentage: 65 },
    { hour: '10pm', amount: '$ 340.000', sales: 49, heightPercentage: 88 },
    { hour: '12am', amount: '$ 410.000', sales: 62, heightPercentage: 100 }, // Pico máximo
    { hour: '2am', amount: '$ 320.000', sales: 45, heightPercentage: 78 },
    { hour: '4am', amount: '$ 190.000', sales: 28, heightPercentage: 52 },
];

// Alertas de inventario
const inventoryAlerts = [
    {
        id: 1,
        name: 'Cerveza lata',
        stock: 8,
        status: 'danger', // dot rojo
        category: 'Bebidas'
    },
    {
        id: 2,
        name: 'Aguardiente 750ml',
        stock: 5,
        status: 'danger', // dot rojo
        category: 'Licores'
    },
    {
        id: 3,
        name: 'Hielo bolsa',
        stock: 14,
        status: 'success', // dot verde
        category: 'Insumos'
    },
    {
        id: 4,
        name: 'Gaseosa 1.5L',
        stock: 9,
        status: 'danger', // dot rojo
        category: 'Bebidas'
    }
];
</script>

<template>
    <Head title="Panel Principal - PosVenta" />

    <AuthenticatedLayout>
        <!-- TOP HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Panel principal
                </h1>
                <p class="mt-1 text-sm text-slate-500 font-medium">
                    Resumen operativo diario del negocio
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Filtro de período -->
                <div class="inline-flex rounded-xl p-1 bg-white border border-slate-200/90 shadow-sm">
                    <button
                        @click="selectedPeriod = 'hoy'"
                        :class="[
                            selectedPeriod === 'hoy'
                                ? 'bg-purple-600 text-white font-semibold shadow-sm'
                                : 'text-slate-600 hover:text-slate-900',
                            'px-3.5 py-1.5 text-xs rounded-lg transition-all'
                        ]"
                    >
                        Hoy
                    </button>
                    <button
                        @click="selectedPeriod = 'semana'"
                        :class="[
                            selectedPeriod === 'semana'
                                ? 'bg-purple-600 text-white font-semibold shadow-sm'
                                : 'text-slate-600 hover:text-slate-900',
                            'px-3.5 py-1.5 text-xs rounded-lg transition-all'
                        ]"
                    >
                        Esta semana
                    </button>
                </div>

                <!-- Botón de acción rápida -->
                <button
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Nueva Venta</span>
                </button>
            </div>
        </div>

        <!-- 4 KPI CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div
                v-for="(kpi, index) in kpis"
                :key="index"
                class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200 group"
            >
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-slate-500">
                        {{ kpi.title }}
                    </span>
                    <span
                        :class="[
                            kpi.isPositive ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600',
                            'text-[11px] font-bold px-2 py-0.5 rounded-full'
                        ]"
                    >
                        {{ kpi.change }}
                    </span>
                </div>

                <div class="text-2xl sm:text-3xl font-black text-purple-700 tracking-tight mb-1">
                    {{ kpi.value }}
                </div>

                <p class="text-xs text-slate-400 font-medium">
                    {{ kpi.subtext }}
                </p>
            </div>
        </div>

        <!-- MAIN 2-COLUMN GRID (GRÁFICO Y ALERTAS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- VENTAS POR HORARIO (7 COLUMNAS) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">
                                Ventas por horario
                            </h2>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">
                                Distribución horaria del volumen facturado
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 border border-purple-100">
                            Pico: 12:00 am
                        </span>
                    </div>

                    <!-- Gráfico interactivo con barras verticales redondeadas -->
                    <div class="h-64 sm:h-72 w-full pt-8 pb-4 flex items-end justify-between gap-3 sm:gap-6 border-b border-slate-100">
                        <div
                            v-for="(item, idx) in hourlySales"
                            :key="idx"
                            class="flex-1 flex flex-col items-center h-full justify-end group relative"
                        >
                            <!-- Tooltip emergente al pasar el cursor -->
                            <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none bg-slate-900 text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg shadow-lg whitespace-nowrap z-20">
                                {{ item.amount }} ({{ item.sales }} vtas)
                                <div class="w-2 h-2 bg-slate-900 rotate-45 absolute -bottom-1 left-1/2 -translate-x-1/2"></div>
                            </div>

                            <!-- Barra vertical púrpura/lila -->
                            <div class="w-full bg-slate-100 rounded-2xl h-full flex items-end overflow-hidden p-0.5">
                                <div
                                    class="w-full rounded-2xl bg-purple-400 group-hover:bg-purple-600 transition-all duration-300 shadow-sm"
                                    :style="{ height: `${item.heightPercentage}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <!-- Eje X: Horarios -->
                    <div class="flex items-center justify-between gap-3 sm:gap-6 pt-3 px-1">
                        <div
                            v-for="(item, idx) in hourlySales"
                            :key="idx"
                            class="flex-1 text-center text-xs font-semibold text-slate-500"
                        >
                            {{ item.hour }}
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span>Horario comercial nocturno analizado</span>
                    <span class="font-semibold text-purple-700">Total jornada: $ 1.695.000</span>
                </div>
            </div>

            <!-- ALERTAS DE INVENTARIO -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">
                                Alertas de inventario
                            </h2>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">
                                Artículos con stock inferior al mínimo
                            </p>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                    </div>

                    <!-- Lista de alertas en tarjetas suaves -->
                    <div class="space-y-3.5">
                        <div
                            v-for="item in inventoryAlerts"
                            :key="item.id"
                            class="p-4 rounded-2xl bg-slate-50/90 hover:bg-purple-50/60 border border-slate-100 transition-all duration-200 flex items-center justify-between group"
                        >
                            <div class="flex items-center gap-3.5">
                                <!-- Dot rojo o verde según estado -->
                                <div
                                    :class="[
                                        item.status === 'danger' ? 'bg-rose-500 shadow-rose-300/50' : 'bg-emerald-500 shadow-emerald-300/50',
                                        'w-4 h-4 rounded-full shrink-0 shadow-md ring-4 ring-white'
                                    ]"
                                ></div>

                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 group-hover:text-purple-900 transition-colors">
                                        {{ item.name }}
                                    </h3>
                                    <span class="text-xs font-medium text-slate-400">
                                        Stock: <span class="font-bold text-slate-700">{{ item.stock }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Botón o tag de acción rápida -->
                            <button
                                type="button"
                                class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:border-purple-300 hover:text-purple-700 transition-colors shadow-2xs"
                            >
                                Reponer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer de la tarjeta -->
                <div class="pt-6 mt-6 border-t border-slate-100">
                    <button
                        type="button"
                        class="w-full py-3 px-4 rounded-2xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs transition-colors flex items-center justify-center gap-2"
                    >
                        <span>Ver catálogo completo de inventario</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
