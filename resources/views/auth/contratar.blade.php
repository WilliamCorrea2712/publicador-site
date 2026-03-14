<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contratar</title>
    <style>
        body { font-family: Inter, system-ui, sans-serif; background: #f3f4f6; margin:0; padding:0; }
        .container { width:min(700px,95%); margin:3rem auto; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; }
        label { display:block; margin-top:0.75rem; font-weight:600; }
        input { width:100%; padding:0.5rem; margin-top:0.25rem; border:1px solid #cbd5e1; border-radius:0.35rem; }
        button { margin-top:1rem; background:#1d4ed8; color:#fff; border:none; border-radius:0.35rem; padding:0.65rem 1rem; cursor:pointer; }
        .error { color:#dc2626; font-size:.9rem; margin-top:.2rem; }
        .top { margin-bottom:1rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="top"><h1>Contratar plano</h1><p>Preencha os dados para criar seu cadastro.</p></div>
        @if(session('success'))<div style="padding:.75rem; border:1px solid #10b981; background:#ecfdf3; color:#065f46; border-radius:.35rem; margin-bottom:0.75rem;">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div style="padding:.75rem; border:1px solid #fecaca; background:#fef2f2; color:#b91c1c; border-radius:.35rem; margin-bottom:0.75rem;">
                <ul style="margin:0; padding-left:1rem;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contratar.store') }}">
            @csrf
            <label for="full_name">Nome completo</label>
            <input id="full_name" name="full_name" value="{{ old('full_name') }}" required>

            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="store_name">Nome da loja</label>
            <input id="store_name" name="store_name" value="{{ old('store_name') }}" required>

            <button type="submit">Cadastrar e enviar senha por e-mail</button>
        </form>
        <p style="margin-top:1rem; color:#6b7280;">Após cadastrar, você será redirecionado ao login.</p>
    </div>
</body>
</html>
