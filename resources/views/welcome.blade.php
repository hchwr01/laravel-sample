<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        :root {
            color-scheme: light dark;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f6f7f9;
            color: #1f2937;
        }

        main {
            width: min(92vw, 560px);
            padding: 32px;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
        }

        p {
            margin: 0 0 24px;
            color: #6b7280;
            line-height: 1.7;
        }

        nav {
            display: grid;
            gap: 12px;
        }

        a {
            display: block;
            padding: 14px 16px;
            border-radius: 12px;
            background: #111827;
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.15s ease, opacity 0.15s ease;
        }

        a:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        a.secondary {
            background: #e5e7eb;
            color: #111827;
        }

        footer {
            margin-top: 24px;
            font-size: 13px;
            color: #9ca3af;
            text-align: center;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #0f172a;
                color: #e5e7eb;
            }

            main {
                background: #111827;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            }

            p {
                color: #9ca3af;
            }

            a {
                background: #f9fafb;
                color: #111827;
            }

            a.secondary {
                background: #374151;
                color: #f9fafb;
            }

            footer {
                color: #6b7280;
            }
        }
    </style>
</head>
<body>
    <main>
        <h1>{{ config('app.name', 'Laravel') }}</h1>
        <p>
            Laravel 側のメニューです。使いたいページを選んでください。
        </p>

        <nav>
            <a href="{{ config('app.url') }}/todos">
                Todo アプリを開く
            </a>

            <a href="https://hachiware-eng.com/" class="secondary">
                サイトトップへ戻る
            </a>
        </nav>

        <footer>
            Laravel app under /laravel
        </footer>
    </main>
</body>
</html>