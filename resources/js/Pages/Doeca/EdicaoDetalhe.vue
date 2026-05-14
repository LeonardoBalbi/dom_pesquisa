<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    edicao: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <Head :title="`Edição ${edicao.numero_edicao}`" />

        <header class="border-b border-slate-200 bg-white shadow-sm">
            <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-4">
                <Link
                    :href="route('doeca.inicio')"
                    class="text-sm font-medium text-emerald-700 hover:underline"
                >
                    ← Voltar ao diário
                </Link>
                <a
                    href="/admin"
                    class="text-sm text-slate-500 hover:text-slate-800"
                >
                    Administração
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 py-10">
            <article class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Edição oficial
                </p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    Edição {{ edicao.numero_edicao }}
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Data de publicação:
                    <time>{{ edicao.data_publicacao }}</time>
                </p>
                <p
                    v-if="edicao.palavras_chave"
                    class="mt-4 text-sm text-slate-700"
                >
                    <span class="font-medium text-slate-900">Palavras-chave:</span>
                    {{ edicao.palavras_chave }}
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    {{ edicao.visualizacoes }} visualizações
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        :href="route('doeca.edicao.download', edicao.id)"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700"
                        target="_blank"
                        rel="noopener"
                    >
                        Abrir PDF
                    </a>
                </div>
            </article>
        </main>
    </div>
</template>
