<?php
declare(strict_types=1);

/**
 * A deliberately small router. Routes are declared in app/routes.php as
 * "METHOD /path" => handler, where a path segment written as {name} is passed
 * to the handler as a named argument.
 */
function route_path(): string
{
    $path = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    $path = is_string($path) && $path !== '' ? $path : '/';

    // Trailing slashes are tolerated so /research/ and /research are one page.
    return $path !== '/' ? rtrim($path, '/') : '/';
}

function route_method(): string
{
    return strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

/**
 * Turns "/research/{slug}" into a regex capturing named parameters. Route
 * paths only ever contain letters, digits, "/", "-" and placeholders, so no
 * other character needs quoting.
 */
function route_pattern_to_regex(string $pattern): string
{
    $regex = preg_replace_callback(
        '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
        static fn(array $m): string => '(?P<' . $m[1] . '>[^/]+)',
        $pattern
    );

    return '#^' . (string)$regex . '$#';
}

function route_dispatch(array $routes): void
{
    // A HEAD request must run its GET handler; the web server drops the body.
    // Without this every monitor, link checker and crawler that probes with
    // HEAD gets a 405 where Apache used to answer 200.
    $method = route_method();
    if ($method === 'HEAD') {
        $method = 'GET';
    }

    $path = route_path();
    $pathMatchedOtherMethod = false;

    foreach ($routes as $definition => $handler) {
        [$routeMethods, $routePattern] = explode(' ', $definition, 2);
        $methods = explode('|', strtoupper($routeMethods));

        if (!preg_match(route_pattern_to_regex($routePattern), $path, $matches)) {
            continue;
        }

        if (!in_array($method, $methods, true)) {
            $pathMatchedOtherMethod = true;
            continue;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        $handler($params);
        return;
    }

    route_fail($pathMatchedOtherMethod ? 405 : 404);
}

function route_fail(int $statusCode): void
{
    http_response_code($statusCode);

    if (str_starts_with(route_path(), '/admin')) {
        echo $statusCode === 405 ? 'Method not allowed' : 'Not found';
        return;
    }

    $heading = $statusCode === 405 ? 'Method not allowed' : 'Not found';
    $note = $statusCode === 405
        ? 'That address does not accept this kind of request.'
        : 'There is no page at that address.';

    // The error page reads the site name and footer from the database, so a
    // miss while the database is down would otherwise turn a 404 into an
    // uncaught exception. Fall back to plain text instead.
    try {
        view('pages/error', [
            'statusCode' => $statusCode,
            'heading' => $heading,
            'note' => $note,
        ]);
    } catch (Throwable $exception) {
        header('Content-Type: text/plain; charset=UTF-8');
        echo $heading . "\n" . $note . "\n";
    }
}
