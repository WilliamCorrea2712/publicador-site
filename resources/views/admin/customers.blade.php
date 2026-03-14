<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customers Cadastrados</h2>
            <a href="{{ route('admin.profile') }}" class="text-sm text-blue-500 hover:text-blue-700">Meu admin</a>
        </div>
    </x-slot>
    <div class="py-6"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <table class="min-w-full text-left border">
            <thead><tr class="bg-gray-100"><th class="px-3 py-2 border">ID</th><th class="px-3 py-2 border">Nome</th><th class="px-3 py-2 border">Email</th><th class="px-3 py-2 border">Minha Página</th><th class="px-3 py-2 border">Ações</th></tr></thead>
            <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td class="px-3 py-2 border">{{ $customer->id }}</td>
                    <td class="px-3 py-2 border">{{ $customer->name }}</td>
                    <td class="px-3 py-2 border">{{ $customer->email }}</td>
                    <td class="px-3 py-2 border">
                        @if($customer->landingPage)
                            <a href="{{ route('customer.page', ['slug' => $customer->landingPage->slug]) }}" class="text-blue-600" target="_blank">{{ route('customer.page', ['slug' => $customer->landingPage->slug]) }}</a>
                        @else
                            <span class="text-gray-500">não criada</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 border">
                        @if($customer->landingPage)
                            <a href="{{ route('customer.page', ['slug' => $customer->landingPage->slug]) }}" class="text-green-600 mr-2" target="_blank">Ver página</a>
                        @endif
                        <a href="{{ route('admin.customer.show', $customer) }}" class="text-blue-600">Ver admin</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div></div></div>
</x-app-layout>