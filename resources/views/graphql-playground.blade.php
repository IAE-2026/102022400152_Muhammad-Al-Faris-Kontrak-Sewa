<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GraphQL Playground - Kontrak Sewa Service</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #111827;
            color: #e5e7eb;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 24px;
        }
        h1 { margin: 0 0 8px; }
        p { color: #9ca3af; }
        label {
            display: block;
            margin: 20px 0 8px;
            font-weight: bold;
        }
        input, textarea, pre {
            width: 100%;
            border: 1px solid #374151;
            border-radius: 8px;
            background: #030712;
            color: #e5e7eb;
            padding: 14px;
            font-family: Consolas, monospace;
            font-size: 14px;
        }
        textarea {
            min-height: 260px;
            resize: vertical;
        }
        pre {
            min-height: 250px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }
        button {
            margin-top: 16px;
            margin-right: 10px;
            border: none;
            border-radius: 8px;
            padding: 12px 18px;
            font-weight: bold;
            cursor: pointer;
            background: #22c55e;
            color: #052e16;
        }
        button.secondary {
            background: #374151;
            color: #e5e7eb;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>GraphQL Playground</h1>
        <p>Kontrak Sewa Service</p>

    <label for="apiKey">X-IAE-KEY</label>
    <input id="apiKey" value="102022400152">

    <label for="query">GraphQL Query</label>
    <textarea id="query">{

contracts {
id
contract_number
property_id
tenant_id
status
monthly_rent
}
}</textarea>

    <button id="runButton">Execute Query</button>
    <button id="clearButton" class="secondary">Clear Result</button>

    <label for="result">Response</label>
    <pre id="result">Klik Execute Query untuk menjalankan query.</pre>
</main>

<script>
    const queryInput = document.getElementById('query');
    const apiKeyInput = document.getElementById('apiKey');
    const resultBox = document.getElementById('result');

    document.getElementById('runButton').addEventListener('click', async () => {
        resultBox.textContent = 'Memproses query...';

        try {
            const response = await fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-IAE-KEY': apiKeyInput.value
                },
                body: JSON.stringify({
                    query: queryInput.value
                })
            });

            const result = await response.json();

            resultBox.textContent =
                'HTTP Status: ' + response.status + '\n\n' +
                JSON.stringify(result, null, 2);
        } catch (error) {
            resultBox.textContent = 'Terjadi kesalahan: ' + error.message;
        }
    });

    document.getElementById('clearButton').addEventListener('click', () => {
        resultBox.textContent = '';
    });
</script>

</body>
</html>
