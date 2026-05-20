<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Suas Páginas</h2>
                <p class="text-sm text-gray-500 mt-1">Crie, edite e publique páginas com banners, cards e HTML aberto.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('landing.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="background-color:#2563eb;color:#ffffff;">Criar Nova Página</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <!--<div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-5 text-blue-900">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="font-semibold">Comece criando sua primeira página.</p>
                            <p class="text-sm text-blue-800 mt-1">Clique no botão abaixo para criar uma página com banner, cards e conteúdo HTML.</p>
                        </div>
                        <div>
                            <a href="{{ route('landing.create') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="background-color:#2563eb;color:#ffffff;">Criar Nova Página</a>
                        </div>
                    </div>
                </div>--!>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                @if($pages->isEmpty())
                    <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-gray-600">
                        <p class="text-lg font-medium">Nenhuma página criada ainda.</p>
                        <p class="mt-2">Clique em "Criar Nova Página" para começar a montar sua primeira página.</p>
                        <div class="mt-6">
                            <a href="{{ route('landing.create') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">Criar Nova Página</a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-gray-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Nome</th>
                                    <th class="px-4 py-3">URL</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Atualizado</th>
                                    <th class="px-4 py-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($pages as $page)
                                    <tr>
                                        <td class="px-4 py-4 font-medium text-gray-900">{{ $page->name }}</td>
                                        <td class="px-4 py-4 text-blue-600">
                                            <a href="{{ route('customer.page', ['slug' => $page->slug]) }}" target="_blank">/loja/{{ $page->slug }}</a>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($page->is_published)
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Publicado</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">Rascunho</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-gray-500">{{ $page->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-4 space-x-2">
                                            <a href="{{ route('landing.page.edit', $page) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700" style="background-color:#2563eb;color:#ffffff;">Editar</a>
                                            <a href="{{ route('customer.page', ['slug' => $page->slug]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-700 text-white rounded-md hover:bg-slate-800" style="background-color:#334155;color:#ffffff;">Visualizar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
