<?php
class BookingController {
	private $bookingModel;

	public function __construct() {
		$this->bookingModel = new Booking();
	}

	public function index() {
		$this->render('booking/form');
	}

	public function submit() {
		$required = ['first_name','last_name','email','phone','service_type'];
		foreach ($required as $field) {
			if (empty($_POST[$field])) {
				http_response_code(422);
				return $this->render('booking/form', ['error' => 'Please fill out all required fields.']);
			}
		}
		$data = [
			'user_id' => Auth::isLoggedIn() ? Auth::user()['id'] : null,
			'first_name' => trim($_POST['first_name']),
			'last_name' => trim($_POST['last_name']),
			'email' => trim($_POST['email']),
			'phone' => trim($_POST['phone']),
			'service_type' => $_POST['service_type'],
			'preferred_date' => $_POST['preferred_date'] ?: null,
			'message' => $_POST['message'] ?? '',
			'status' => 'pending'
		];
		$this->bookingModel->create($data);
		$this->render('booking/thankyou');
	}

	private function render($template, $data = []) {
		extract($data);
		$pageTitle = 'Booking | Joanne\'s';
		$viewsDir = dirname(__DIR__) . '/Views';
		ob_start();
		include $viewsDir . "/{$template}.php";
		$content = ob_get_clean();
		include $viewsDir . '/home.php';
	}
}
