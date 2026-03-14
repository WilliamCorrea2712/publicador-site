<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Painel Admin</h2>
            <a href="{{ route('admin.customers') }}" class="text-sm text-blue-500 hover:text-blue-700">Ver Customers</a>
        </div>
    </x-slot>
    <div class="py-6"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @if(session('success')) <div class="mb-3 p-2 bg-green-100 text-green-700">{{ session('success') }}</div> @endif

        <h3 class="font-bold">Atualizar dados</h3>
        <form method="post" action="{{ route('admin.profile.update') }}">
            @csrf
            <div class="grid gap-2 md:grid-cols-2 mt-2">
                <input name="name" value="{{ old('name', $user->name) }}" class="border p-2 rounded" placeholder="Nome"/>
                <input name="email" value="{{ old('email', $user->email) }}" class="border p-2 rounded" placeholder="Email"/>
            </div>
            <div class="grid gap-2 md:grid-cols-2 mt-2">
                <input name="address_line" value="{{ old('address_line', $user->address_line) }}" class="border p-2 rounded" placeholder="Endereço"/>
                <input name="city" value="{{ old('city', $user->city) }}" class="border p-2 rounded" placeholder="Cidade"/>
                <input name="state" value="{{ old('state', $user->state) }}" class="border p-2 rounded" placeholder="Estado"/>
                <input name="zipcode" value="{{ old('zipcode', $user->zipcode) }}" class="border p-2 rounded" placeholder="CEP"/>
                <input name="country" value="{{ old('country', $user->country) }}" class="border p-2 rounded" placeholder="País"/>
            </div>
            <button type="submit" class="mt-3 px-3 py-2 bg-blue-600 text-white rounded">Salvar dados</button>
        </form>

        <h3 class="font-bold mt-6">Alterar senha</h3>
        <form method="post" action="{{ route('admin.password.change') }}">
            @csrf
            <div class="grid gap-2 md:grid-cols-2 mt-2">
                <input name="current_password" type="password" class="border p-2 rounded" placeholder="Senha atual" required />
                <input name="new_password" type="password" class="border p-2 rounded" placeholder="Nova senha" required />
                <input name="new_password_confirmation" type="password" class="border p-2 rounded" placeholder="Confirmação" required />
            </div>
            <button type="submit" class="mt-3 px-3 py-2 bg-green-600 text-white rounded">Alterar senha</button>
        </form>
    </div></div></div>
</x-app-layout>