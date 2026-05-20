<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Você está logado!") }}
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold">Minha Página</h3>
                    <p class="mt-2 text-sm text-gray-600">Acesse a lista de páginas em "Minhas Páginas" e clique em "Criar Nova Página" para adicionar componentes.</p>
                    <div class="mt-4 space-x-2">
                        <a href="{{ route('landing.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Minhas Páginas</a>
                        <a href="{{ route('landing.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md">Criar Nova Página</a>
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md">Ver Página Pública</a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md">Editar Perfil / Senha</a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.customers') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md">Admin: Todos Customers</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
