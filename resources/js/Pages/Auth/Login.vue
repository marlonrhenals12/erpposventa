<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión - PosVenta" />

    <div class="min-h-screen bg-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <!-- Main Card Container -->
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200/80 grid grid-cols-1 lg:grid-cols-12 min-h-[620px]">
            
            <!-- LEFT SIDE: FORMULARIO DE INICIO DE SESIÓN -->
            <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-between bg-white order-1">
                <div>
                    <!-- Header / Logo -->
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center text-white shadow-md shadow-purple-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-slate-900">Pos<span class="text-purple-600">Venta</span></span>
                            <span class="block text-xs font-medium text-slate-400">Sistema de Gestión</span>
                        </div>
                    </div>

                    <!-- Title & Subtitle -->
                    <div class="mb-8">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Inicio de sesión
                        </h1>
                        <p class="mt-2 text-sm text-slate-500 font-medium">
                            Acceso seguro para administradores y operadores
                        </p>
                    </div>

                    <!-- Session Status Alert -->
                    <div v-if="status" class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm font-medium text-emerald-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ status }}</span>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Email / Usuario -->
                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Correo o Usuario
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="admin@posventa.com"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all duration-200"
                                />
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.email" />
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                                    Contraseña
                                </label>
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-xs font-semibold text-purple-600 hover:text-purple-700 transition-colors"
                                >
                                    ¿Olvidaste tu contraseña?
                                </Link>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition-all duration-200"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                                    tabindex="-1"
                                >
                                    <!-- Eye icon open -->
                                    <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye icon crossed -->
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            <InputError class="mt-1.5" :message="form.errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center cursor-pointer select-none">
                                <Checkbox
                                    name="remember"
                                    v-model:checked="form.remember"
                                    class="rounded text-purple-600 focus:ring-purple-500 border-slate-300"
                                />
                                <span class="ml-2.5 text-sm text-slate-600 font-medium">Recordar sesión</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full py-3.5 px-6 rounded-xl text-white font-semibold text-sm bg-purple-600 hover:bg-purple-700 active:bg-purple-800 shadow-lg shadow-purple-600/30 hover:shadow-purple-600/40 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>{{ form.processing ? 'Ingresando...' : 'Ingresar al sistema' }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer note -->
                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Servidor conectado
                    </span>
                    <span>&copy; {{ new Date().getFullYear() }} PosVenta</span>
                </div>
            </div>

            <!-- RIGHT SIDE: BRANDING & FEATURES PANEL (Púrpura moderno con esferas decorativas) -->
            <div class="lg:col-span-6 bg-gradient-to-br from-purple-700 via-purple-600 to-indigo-800 p-8 sm:p-12 text-white flex flex-col justify-between relative overflow-hidden order-2">
                
                <!-- Círculos decorativos (inspirados en el diseño de referencia) -->
                <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-purple-500/40 blur-2xl pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-indigo-500/30 blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-10 right-10 w-44 h-44 rounded-full bg-purple-400/20 pointer-events-none"></div>
                <div class="absolute top-1/2 left-3/4 -translate-y-1/2 w-28 h-28 rounded-full bg-white/5 backdrop-blur-sm pointer-events-none"></div>

                <!-- Contenido superior -->
                <div class="relative z-10">
                    <!-- Badge superior -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-xs font-semibold tracking-wide uppercase text-purple-100 border border-white/15 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-300"></span>
                        Sistema POS & ERP
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight mb-3">
                        Pos<span class="text-purple-200">Venta</span>
                    </h2>
                    <p class="text-purple-100/90 text-sm sm:text-base font-normal leading-relaxed max-w-md">
                        Control integral de ventas, inventario, caja, facturación y reportes administrativos en tiempo real.
                    </p>
                </div>

                <!-- Feature cards centrales -->
                <div class="relative z-10 my-8 space-y-3.5">
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/15 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0 text-white font-bold text-lg">
                            🛒
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Ventas y Facturación Ágil</h3>
                            <p class="text-xs text-purple-100/80">Procesa pedidos y cobros al instante con interfaz optimizada.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/15 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0 text-white font-bold text-lg">
                            📦
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Control de Stock e Inventario</h3>
                            <p class="text-xs text-purple-100/80">Trazabilidad de existencias, kardex y alertas de reposición.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 hover:bg-white/15 transition-all duration-200">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0 text-white font-bold text-lg">
                            📊
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">Reportes y Métricas</h3>
                            <p class="text-xs text-purple-100/80">Cuadre de caja diario y estadísticas comerciales detalladas.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer inferior derecho -->
                <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-xs text-purple-200/80">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Plataforma Operativa</span>
                    </div>
                    <span>v1.0.0</span>
                </div>
            </div>

        </div>
    </div>
</template>
