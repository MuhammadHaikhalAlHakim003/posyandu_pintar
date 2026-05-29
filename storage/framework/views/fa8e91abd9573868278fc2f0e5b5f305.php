<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
    <style>
        :root {
            color-scheme: light;
            --docs-bg: #f4f7fb;
            --docs-card: #ffffff;
            --docs-text: #1f2937;
            --docs-muted: #6b7280;
            --docs-accent: #2563eb;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #eef4ff 0%, var(--docs-bg) 100%);
            color: var(--docs-text);
        }

        .docs-shell {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 20px 40px;
        }

        .docs-header {
            background: var(--docs-card);
            border: 1px solid rgba(37, 99, 235, 0.12);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            margin-bottom: 20px;
        }

        .docs-kicker {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--docs-accent);
            margin-bottom: 10px;
        }

        .docs-title {
            margin: 0 0 8px;
            font-size: 32px;
            line-height: 1.15;
        }

        .docs-subtitle {
            margin: 0;
            color: var(--docs-muted);
            max-width: 760px;
        }

        .docs-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .docs-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 14px;
            font-weight: 600;
        }

        #swagger-ui {
            background: var(--docs-card);
            border-radius: 20px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }
    </style>
</head>
<body>
    <div class="docs-shell">
        <section class="docs-header">
            <span class="docs-kicker">Swagger UI</span>
            <h1 class="docs-title">Posyandu Pintar API</h1>
            <p class="docs-subtitle">
                Dokumentasi endpoint REST API untuk autentikasi, warga, kader, jadwal posyandu, penimbangan, imunisasi, dan verifikasi.
            </p>
            <div class="docs-meta">
                <span class="docs-pill">Base URL: http://127.0.0.1:8000/api</span>
                <span class="docs-pill">Auth: JWT Bearer</span>
                <span class="docs-pill">Public: X-API-Key</span>
            </div>
        </section>

        <div id="swagger-ui"></div>
    </div>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function () {
            window.ui = SwaggerUIBundle({
                url: '/swagger/openapi.json',
                dom_id: '#swagger-ui',
                deepLinking: true,
                displayRequestDuration: true,
                filter: true,
                layout: 'StandaloneLayout',
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset,
                ],
            });
        };
    </script>
</body>
</html>
<?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/docs/swagger.blade.php ENDPATH**/ ?>