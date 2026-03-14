<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer {{ $customer->name }}</h2>
            <a href="{{ route('admin.customers') }}" class="text-sm text-blue-500 hover:text-blue-700">Voltar</a>
        </div>
    </x-slot>
    <div class="py-6"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p><strong>ID:</strong> {{ $customer->id }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Role:</strong> {{ $customer->role }}</p>
        <p><strong>Endereço:</strong> {{ $customer->address_line ?? '--' }}, {{ $customer->city ?? '--' }}, {{ $customer->state ?? '--' }}, {{ $customer->zipcode ?? '--' }}, {{ $customer->country ?? '--' }}</p>
        <p><strong>Minha página:</strong>
            @if($customer->landingPage)
                <a class="text-blue-600" href="{{ route('customer.page', ['slug' => $customer->landingPage->slug]) }}" target="_blank">{{ route('customer.page', ['slug' => $customer->landingPage->slug]) }}</a>
            @else
                --
            @endif
        </p>
        <p class="mt-3"><a class="text-blue-600" href="{{ route('profile.edit') }}">Editar meu perfil</a> (customer autenticado)</p>
    </div></div></div>
</x-app-layout>