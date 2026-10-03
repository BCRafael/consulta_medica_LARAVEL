<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistema de Saúde')</title>
    <style>
        :root { color-scheme: light; font-family: Arial, Helvetica, sans-serif; color: #18302d; background: #f3f7f6; }
        * { box-sizing: border-box; }
        body { margin: 0; }
        header { background: #fff; border-bottom: 1px solid #dce7e4; }
        .topbar, main, footer { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        .topbar { min-height: 68px; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
        .brand { color: #126b5b; font-size: 1.15rem; font-weight: 700; text-decoration: none; }
        nav { display: flex; flex-wrap: wrap; gap: 18px; }
        nav a, a { color: #087563; }
        nav a { text-decoration: none; font-weight: 600; }
        main { padding: 30px 0 48px; min-height: calc(100vh - 130px); }
        h1 { margin-top: 0; font-size: clamp(1.7rem, 4vw, 2.3rem); }
        h2 { margin-top: 0; font-size: 1.25rem; }
        p { line-height: 1.55; }
        .hero, .card, .panel { background: #fff; border: 1px solid #dce7e4; border-radius: 14px; padding: 24px; }
        .hero { padding: clamp(28px, 6vw, 64px); background: linear-gradient(120deg, #e7f6f1, #fff 70%); }
        .hero h1 { margin-bottom: 8px; }
        .eyebrow { text-transform: uppercase; color: #087563; font-weight: 700; letter-spacing: .08em; font-size: .8rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 20px; }
        .panel { margin-bottom: 22px; }
        .actions, .inline-form { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
        .actions { margin: 14px 0 22px; }
        .button, button, input[type=submit] { display: inline-block; border: 0; border-radius: 7px; padding: 10px 14px; background: #087563; color: #fff; text-decoration: none; font-weight: 700; cursor: pointer; }
        .button.secondary, button.secondary, input[type=submit].secondary { background: #e9f2ef; color: #155d51; }
        .button.danger, button.danger, input[type=submit].danger { background: #a93232; }
        form { margin: 0; }
        label { display: inline-block; margin: 8px 6px 6px 0; font-weight: 600; }
        input:not([type=checkbox]), select { min-height: 38px; padding: 8px 10px; border: 1px solid #b9cbc6; border-radius: 6px; max-width: 100%; }
        .field { margin-bottom: 14px; }
        .field > label { display: block; }
        .checkboxes label { font-weight: 400; }
        .table-wrap { overflow-x: auto; border: 1px solid #dce7e4; border-radius: 10px; background: #fff; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; }
        th, td { padding: 12px; border-bottom: 1px solid #e4ece9; text-align: left; vertical-align: top; }
        th { background: #edf5f2; color: #25564d; }
        tr:last-child td { border-bottom: 0; }
        .notice { padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; }
        .notice.success { background: #e1f5e9; color: #185b32; }
        .notice.error { background: #fff0ed; color: #8b2d20; }
        .muted { color: #637773; }
        footer { padding: 18px 0 28px; color: #637773; border-top: 1px solid #dce7e4; }
        @media (max-width: 640px) { .topbar { align-items: flex-start; flex-direction: column; padding: 14px 0; } nav { gap: 12px; } }
    </style>
</head>
<body>
    <header>
        <div class="topbar">
            <a class="brand" href="{{ route('home') }}">Sistema de Saúde</a>
            <nav aria-label="Navegação principal">
                <a href="{{ route('fila.index') }}">Fila e consultas</a>
                <a href="{{ route('pacientes.create') }}">Novo paciente</a>
                <a href="{{ route('medicos.index') }}">Médicos</a>
            </nav>
        </div>
    </header>
    <main>
        @if (session('success'))
            <div class="notice success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="notice error" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="notice error" role="alert">
                <strong>Revise os dados informados:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
    <footer>Sistema acadêmico de fila de espera e consultas médicas.</footer>
</body>
</html>
