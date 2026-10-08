<?php
// Replace java_source() and java_text() in the engine class and insert
// the three java_shortcut_* helper methods there as well. Do not include
// this file directly: these functions are members of the existing class.

function java_ide(&$s, &$p)
{
    $this->tagdivision($s, $p);
    $filename = trim($s);
    $escape = function ($value) { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
    if (!preg_match('/^[^\/\\\\]+\.json$/i', $filename) || strpos($filename, "\0") !== false) {
        $this->write('<span role="alert">Bitte einen JSON-Dateinamen ohne Pfad angeben.</span>');
    } else {
        $file = $this->iRequest->get_SeitePathPublic() . $filename;
        $this->write('<div data-java-shortcut><div>' . $escape($filename) . '</div>'
            . '<button type="button" class="btn btn-info" data-java-action="W">In IDE öffnen</button> '
            . '<span data-java-status role="status" aria-live="polite"></span>'
            . '<script type="application/json" data-java-source>'
            . json_encode(array('workspace' => $file), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
            . '</script></div>');
        $this->java_shortcut_script();
    }
    $this->tagend($s, $p);
}

function java_source(&$s, &$p)
{
    $this->tagdivision($s, $p);
    $filename = basename(str_replace('\\', '/', $s));
    $file = $this->iRequest->get_SeitePathPublic() . $s;
    $parts = $this->java_shortcut_parameters($p);
    $flags = '';
    if ($parts && preg_match('/^[CNI]+$/', trim(end($parts)))) {
        $flags = trim(array_pop($parts));
    }
    $options = $parts ? $parts[0] : '';
    $parameters = $options !== '' ? _paramarray($options) : array();
    if (file_exists($file)) {
        $source = str_replace(array("\r\n", "\r"), "\n", filesystem::LoadFile($file));
        $lines = explode("\n", $source);
        $from = isset($parameters['von']) ? (int)$parameters['von'] : 0;
        $to = isset($parameters['bis']) ? (int)$parameters['bis'] : 0;
        // Preserve the existing range behaviour, including von=0 (whole file).
        if ($from > 0) {
            $lines = array_slice($lines, $from - 1, $to > 0 ? max(0, $to - $from + 1) : null);
        }
        $gutter = isset($parameters['Zeilennummern'])
            && in_array(strtolower(trim((string)$parameters['Zeilennummern'])), array('true', '1'), true);
        $this->java_shortcut_render(implode("\n", $lines), $flags, $filename, $gutter);
    }
    $this->tagend($s, $p);
}

function java_text(&$s, &$p)
{
    $this->tagdivision($s, $p);
    $source = preg_replace('/<p(?:\s+[^>]*)?>/i', '', $s);
    $source = preg_replace('/<\/p\s*>|<br\s*\/?>/i', "\n", $source);
    $source = html_entity_decode($source, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $source = str_replace(array("\xc2\xa0", "\r\n", "\r"), array(' ', "\n", "\n"), $source);
    $parts = $this->java_shortcut_parameters($p);
    $flags = $parts ? trim($parts[count($parts) - 1]) : '';
    if (!preg_match('/^[CNI]+$/', $flags)) $flags = '';
    $filename = 'Program.java';
    if (preg_match('/\b(?:class|interface|enum|record)\s+([A-Za-z_$][A-Za-z0-9_$]*)\b/', $source, $match)) {
        $filename = $match[1] . '.java';
    }
    $this->java_shortcut_render($source, $flags, $filename, false);
    $this->tagend($s, $p);
}

function java_shortcut_parameters(&$p)
{
    $parts = array();
    // Read optional fields without consuming TCML_END, even for empty fields.
    while (substr($this->input, $p, strlen(TCML_SEP)) === TCML_SEP) {
        $p += strlen(TCML_SEP);
        $end = strpos($this->input, TCML_END, $p);
        $next = strpos($this->input, TCML_SEP, $p);
        if ($end === false) $end = strlen($this->input);
        if ($next !== false && $next < $end) $end = $next;
        $parts[] = substr($this->input, $p, $end - $p);
        $p = $end;
    }
    return $parts;
}

function java_shortcut_render($source, $flags, $filename, $gutter)
{
    $escape = function ($value) { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
    $this->write('<div data-java-shortcut><pre class="brush:java;gutter:'
        . ($gutter ? 'true' : 'false') . ';">' . $escape($source) . '</pre>');
    if ($flags !== '') {
        $payload = json_encode(array('code' => $source, 'filename' => $filename),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        $this->write('<script type="application/json" data-java-source>' . $payload . '</script>');
        if (strpos($flags, 'I') !== false) {
            $this->write('<label>Dateiname: <input data-java-filename type="text" value="'
                . $escape($filename) . '" aria-label="Java-Dateiname"></label> ');
        }
        foreach (array('C' => 'Kopieren', 'N' => 'In IDE öffnen', 'I' => 'Zur IDE hinzufügen') as $flag => $label) {
            if (strpos($flags, $flag) !== false) {
                $this->write('<button type="button" class="btn btn-info" data-java-action="'
                    . $flag . '">' . $label . '</button> ');
            }
        }
        $this->write('<span data-java-status role="status" aria-live="polite"></span>');
        $this->java_shortcut_script();
    }
    $this->write('</div>');
    if (!$this->syntaxhighlighter) {
        $this->write('<script type="text/javascript">SyntaxHighlighter.all()</script>');
        $this->syntaxhighlighter = true;
    }
}

function java_shortcut_script()
{
    $this->write(<<<'HTML'
<script>
(function () {
    if (window.netJavaShortcutsInstalled) return;
    window.netJavaShortcutsInstalled = true;
    const runner = new URL('https://net-schulbuch.de/java-ide/sas-book-demo/runner.html');
    const tabName = 'net-schulbuch-java-shortcuts';
    let ideWindow, ready = false, pending = null, timer;
    function status(block, text) { block.querySelector('[data-java-status]').textContent = text; }
    function finish(text) {
        clearTimeout(timer);
        if (pending) {
            pending.button.disabled = false;
            status(pending.block, text);
        }
        pending = null;
    }
    function send() {
        if (!ready || !pending || pending.sent) return;
        pending.sent = true;
        ideWindow.postMessage({type:'sas-book:add-file', code:pending.code,
            filename:pending.filename, requestId:pending.requestId}, runner.origin);
    }
    window.addEventListener('message', event => {
        if (!ideWindow || event.source !== ideWindow || event.origin !== runner.origin) return;
        const message = event.data;
        if (!message || typeof message !== 'object') return;
        if (message.type === 'sas-book:ready') { ready = true; send(); }
        else if (pending && message.requestId === pending.requestId) {
            if (message.type === 'sas-book:added') finish('Datei hinzugefügt.');
            else if (message.type === 'sas-book:error') finish(message.error || 'Datei konnte nicht hinzugefügt werden.');
        }
    });
    async function copy(code) {
        if (navigator.clipboard && window.isSecureContext) {
            try { await navigator.clipboard.writeText(code); return; } catch (error) { /* Fallback below. */ }
        }
        const field = document.createElement('textarea'); field.value = code;
        field.style.cssText = 'position:fixed;left:-9999px;'; document.body.append(field);
        field.select();
        try { if (!document.execCommand('copy')) throw new Error('Kopieren wurde vom Browser blockiert.'); }
        finally { field.remove(); }
    }
    document.addEventListener('click', async event => {
        const button = event.target.closest('[data-java-action]');
        if (!button) return;
        const block = button.closest('[data-java-shortcut]');
        const payload = JSON.parse(block.querySelector('[data-java-source]').textContent);
        const action = button.dataset.javaAction;
        try {
            if (action === 'C') { await copy(payload.code); status(block, 'Kopiert.'); return; }
            if (pending) { status(block, 'Bitte die laufende Dateiübertragung abwarten.'); return; }
            if (action === 'W') {
                const workspace = new URL(payload.workspace, document.baseURI);
                if (!['https:', 'http:'].includes(workspace.protocol)) throw new Error('Ungültige Workspace-Adresse.');
                const url = new URL(runner);
                url.searchParams.set('workspace', workspace.href);
                url.searchParams.set('parentOrigin', location.origin);
                url.searchParams.set('transfer', Date.now() + '-' + Math.random().toString(36).slice(2));
                ready = false;
                ideWindow = window.open(url.href, tabName);
                if (!ideWindow) throw new Error('Bitte Pop-ups für diese Buchseite erlauben.');
                ideWindow.focus();
                status(block, 'Workspace wird geöffnet.');
                return;
            }
            const input = block.querySelector('[data-java-filename]');
            let filename = (input ? input.value : payload.filename).trim();
            if (!filename.endsWith('.java')) filename += '.java';
            if (!/^[\p{ID_Start}_$][\p{ID_Continue}$]*\.java$/u.test(filename)) {
                throw new Error('Bitte einen gültigen Java-Dateinamen angeben.');
            }
            if (input) input.value = filename;
            const url = new URL(runner);
            url.searchParams.set('parentOrigin', location.origin);
            url.searchParams.set('transfer', Date.now() + '-' + Math.random().toString(36).slice(2));
            url.hash = new URLSearchParams({code:payload.code, filename}).toString();
            if (action === 'N') {
                ready = false;
                ideWindow = window.open(url.href, tabName);
                if (!ideWindow) throw new Error('Bitte Pop-ups für diese Buchseite erlauben.');
                ideWindow.focus();
                status(block, 'IDE geöffnet.');
            } else {
                ready = false;
                ideWindow = window.open('', tabName);
                if (!ideWindow) throw new Error('Bitte Pop-ups für diese Buchseite erlauben.');
                ideWindow.focus();
                let blank = false;
                try { blank = ideWindow.location.href === 'about:blank'; } catch (error) { /* Existing cross-origin IDE. */ }
                if (blank) {
                    ideWindow.location.href = url.href;
                    status(block, 'IDE geöffnet.');
                    return;
                }
                pending = {block, button, code:payload.code, filename,
                    requestId:Date.now() + '-' + Math.random().toString(36).slice(2), sent:false};
                button.disabled = true; status(block, 'Datei wird hinzugefügt …');
                timer = setTimeout(() => finish('Keine Antwort von der IDE. Bitte erneut versuchen.'), 20000);
                ideWindow.postMessage({type:'sas-book:ready?'}, runner.origin);
            }
        } catch (error) { status(block, error.message); }
    });
})();
</script>
HTML
    );
}
