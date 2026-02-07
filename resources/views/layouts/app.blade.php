<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MarketPlace' }}</title>
    <style>
        :root { --bg: #fff; --fg: #000; --muted:#555; --line:#111; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: Arial, sans-serif; background: var(--bg); color: var(--fg); }
        a { color: var(--fg); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 1100px; margin: 0 auto; padding: 1rem; }
        .header { border-bottom: 2px solid var(--line); padding: 1rem 0; margin-bottom: 1.5rem; }
        .grid { display: grid; gap: 1rem; }
        .grid.products { grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); }
        .card { border: 1px solid var(--line); padding: 1rem; background: #fff; }
        .muted { color: var(--muted); }
        input, select, button {
            width: 100%; padding: .55rem; border:1px solid var(--line); background:#fff; color:#000;
        }
        button { cursor:pointer; font-weight: 700; }
        .filters { display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:.75rem; margin-bottom: 1rem; }
        .price { font-weight: 700; font-size: 1.05rem; }
        .pill { border:1px solid #000; padding:.15rem .4rem; display:inline-block; font-size:.75rem; }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
        <strong>MarketPlace</strong>
        <span class="muted"> — black & white marketplace</span>
    </header>
    @yield('content')
</div>
</body>
</html>
