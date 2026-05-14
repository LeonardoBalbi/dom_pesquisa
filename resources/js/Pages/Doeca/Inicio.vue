<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const doeca = computed(() => page.props.doeca);
const auth = computed(() => page.props.auth);

const props = defineProps({
    edicoes: {
        type: Object,
        required: true,
    },
    filtro: {
        type: String,
        default: '',
    },
});

const form = useForm({
    q: props.filtro ?? '',
});

function buscar() {
    form.get(route('doeca.inicio'), {
        preserveState: true,
        replace: true,
    });
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <Head title="Diário Oficial" />

        <header class="border-b border-slate-200 bg-white shadow-sm">
            <div
                class="mx-auto flex max-w-5xl flex-col gap-4 px-4 py-6 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <img src="/images/logo_governo_azul.png" alt="Logo" class="h-10 w-auto">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Diário Oficial Eletrônico
                        </p>
                    </div>
                    <h1 class="text-2xl font-semibold text-slate-900">
                        {{ doeca?.municipio ?? 'Município' }}
                        <span
                            v-if="doeca?.estado"
                            class="text-slate-500"
                        >
                            — {{ doeca.estado }}
                        </span>
                    </h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <Link
                        v-if="auth?.user"
                        :href="route('dashboard')"
                        class="text-sm font-medium text-slate-600 underline-offset-4 hover:text-slate-900 hover:underline"
                    >
                        Área logada
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
                        >
                            Entrar
                        </Link>
                    </template>
                    <a
                        href="/admin"
                        class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Administração
                    </a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 py-10">
            <form
                class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end"
                @submit.prevent="buscar"
            >
                <div class="flex-1">
                    <label
                        for="q"
                        class="mb-1 block text-sm font-medium text-slate-700"
                    >
                        Buscar edições
                    </label>
                    <input
                        id="q"
                        v-model="form.q"
                        type="search"
                        name="q"
                        placeholder="Número, palavras-chave ou termo no conteúdo…"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500"
                    />
                </div>
                <button
                    type="submit"
                    class="rounded-md bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Buscar
                </button>
            </form>

            <p
                v-if="filtro"
                class="mb-4 text-sm text-slate-600"
            >
                Resultados para
                <strong>{{ filtro }}</strong>
            </p>

            <ul
                v-if="edicoes.data?.length"
                class="divide-y divide-slate-200 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm"
            >
                <li
                    v-for="ed in edicoes.data"
                    :key="ed.id"
                    class="transition hover:bg-slate-50"
                >
                    <Link
                        :href="route('doeca.edicao.show', ed.id)"
                        class="flex flex-col gap-1 px-4 py-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="font-semibold text-slate-900">
                                Edição {{ ed.numero_edicao }}
                            </p>
                            <p class="text-sm text-slate-500">
                                Publicada em
                                {{ ed.data_publicacao }}
                            </p>
                            <p
                                v-if="ed.palavras_chave"
                                class="mt-1 line-clamp-2 text-xs text-slate-500"
                            >
                                {{ ed.palavras_chave }}
                            </p>
                        </div>
                        <span
                            class="text-sm font-medium text-emerald-700"
                        >
                            Ver detalhes →
                        </span>
                    </Link>
                </li>
            </ul>

            <p
                v-else
                class="rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-slate-600"
            >
                Nenhuma edição encontrada.
            </p>

            <nav
                v-if="edicoes.links?.length > 3"
                class="mt-8 flex flex-wrap justify-center gap-1"
                aria-label="Paginação"
            >
                <template
                    v-for="(link, i) in edicoes.links"
                    :key="i"
                >
                    <Link
                        v-if="link.url && !link.active"
                        :href="link.url"
                        class="min-w-[2.25rem] rounded px-3 py-1 text-center text-sm bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50"
                        preserve-scroll
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="min-w-[2.25rem] rounded px-3 py-1 text-center text-sm"
                        :class="
                            link.active
                                ? 'bg-slate-900 font-semibold text-white'
                                : 'cursor-default text-slate-400'
                        "
                        v-html="link.label"
                    />
                </template>
            </nav>
        </main>

        <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
            {{ doeca?.rodape }}
        </footer>
    </div>
</template>
