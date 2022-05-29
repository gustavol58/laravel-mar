<!DOCTYPE html>

<html>

<body>
    <h1>{{ $contenido['titulo_para_email'] }}</h1>
    {!! $contenido['titulo_para_usuario'] !!}
    {!! $contenido['cuerpo_para_usuario'] !!}
    {!! $contenido['cuerpo_para_email'] !!}
    <p>Fin del catch.</p>
</body>
</html>