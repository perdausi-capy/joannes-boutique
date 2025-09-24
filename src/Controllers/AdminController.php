<?php
class AdminController {
    public function dashboard() {
        Auth::requireAdmin();
        $user = Auth::user();
        $this->render('admin/dashboard', ['user' => $user]);
    }

    private function render($template, $data = []) {
        extract($data);
        $pageTitle = ucfirst(str_replace(['admin/', '/'], ['', ' - '], $template)) . ' | Joanne\'s';
        $viewsDir = dirname(__DIR__) . '/Views';
        ob_start();
        include $viewsDir . "/{$template}.php";
        $content = ob_get_clean();
        include $viewsDir . '/layout.php';
    }
}
