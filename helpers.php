<?php 


/**
 * Get the BasePath(RootPath) of the project
 *
 * @param string $path relative path
 *
 * @return string
 */
function basePath(string $path = ""): string {
    return __DIR__ . '/' . $path;
}

/**
 * Loads a view(php view file) in index.php
 *
 * @param string $name view name
 *
 * @return void
 */
function loadView(string $name): void {
    $viewPath = basePath("views/{$name}.view.php");
    
    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        inspectAndDie("View {$name} does not exist...");
    }
}

/**
 * Loads a partial(php partial file) in a view
 *
 * @param string $name partial name
 *
 * @return void
 */
function loadPartial(string $name): void {
    $partialPath = basePath("views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        require basePath("views/partials/{$name}.php");
    } else {
        inspectAndDie("Partial {$name} does not exist...");
    }

}

/**
 * Inspect a value
 *
 * @param $value value
 *
 * @return void
 */
function inspect(mixed $value): void {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}

function inspectAndDie(mixed $value): void {
    /**
     * Inspect a value and die
     *
     * @param $value value
     *
     * @return void
     */
    echo "<pre>";
    die(print_r($value));
    echo "</pre>";
}

?>