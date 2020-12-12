<?php /** @noinspection PhpIncludeInspection */

/*
 * Base Controller
 * Loads the models and views
 */

class Controller
{
    public function __construct(){}

    // Load model
    public function model($model)
    {
        // Require model file
        require_once '../app/models/' . ucwords($model) . '.php';

        // Instantiate model
        return new $model();
    }

    // Load view
    public function view(string $view, array $payload): void
    {
        // Check for view file
        extract($payload);
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else if (file_exists('../app/views/' . $view . '.html')) {
            require_once "../app/views/$view.html";
        } else {
            // View does not exist: redirect to custom error page with error code 1000
            redirect('errors/index/1000');
        }
    }

    public function view_2(string $view, string $controller, array $payload = [])
    {
        // Check for view file
        $viewFilePath = '../app/views/' . $controller . '/' .$view;
        extract($payload, EXTR_OVERWRITE);
        if (file_exists("$viewFilePath.php" )) {
            require_once "$viewFilePath.php";
        } else if (file_exists("$viewFilePath.html")) {
            require_once "$viewFilePath.html";
        } else {
            redirect('errors/index/1000');
        }
    }
}
