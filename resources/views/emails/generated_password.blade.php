<html>
<body>
    <h2>Olá, {{ $name }}</h2>
    <p>Seu cadastro foi criado com sucesso.</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Senha:</strong> {{ $password }}</p>
    <p>Acesse: <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>
    <p>Depois de logado, acesse <a href="{{ url('/minha-pagina') }}">Minha Página</a>.</p>
</body>
</html>
