<?php
require_once 'config/db_connection.php';

class DynamicFormCreator {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /*
     * Validating data in mate styles !!
     * */
    private function validateFormData($formData) {
        $errors = array();

        // Check if required fields are present
        foreach ($formData['fields'] as $field) {
            if (isset($field['validation']['required']) && $field['validation']['required'] && empty($field['value'])) {
                $errors[] = "Field '{$field['name']}' is required.";
            }
        }

        // Validate input length
        foreach ($formData['fields'] as $field) {
            if (isset($field['validation']['max_length']) && strlen($field['value']) > $field['validation']['max_length']) {
                $errors[] = "Field '{$field['name']}' exceeds maximum length of {$field['validation']['max_length']} characters.";
            }
        }

        if (!empty($errors)) {
            return array("success" => false, "errors" => $errors);
        }

        return array("success" => true);
    }

    private function sendEmail($formData) {
        $to = 'kawishfazal@hotmail.com';
        $subject = 'Form Submission';

        // Compose email body with form data
        $message = '<html><body>';
        $message .= '<h2>Form Submission</h2>';
        foreach ($formData['fields'] as $field) {
            if (isset($field['send_email']) && $field['send_email']) {
                $message .= '<p><strong>' . ucfirst($field['name']) . ':</strong> ' . $field['value'] . '</p>';
            }
        }
        $message .= '</body></html>';

        // Set headers for HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // Additional headers
        $headers .= 'From: kawishfazalvmi@gmail.com' . "\r\n";

        // Send email
        mail($to, $subject, $message, $headers);
    }

    /* Now sending mails are with mail is some thing gud stuff develop by kawish
       but yes after creating the froms lol
    */
    public function createForm($formData) {
        // Validate form data
        $validationResult = $this->validateFormData($formData);

        // Check if validation succeeded
        if ($validationResult['success']) {
            // Store form data in the database
            $formDataJson = json_encode($formData);
            $stmt = $this->db->prepare("INSERT INTO forms (form_data) VALUES (?)");
            $stmt->bind_param("s", $formDataJson);
            $stmt->execute();
            $stmt->close();

            $this->sendEmail($formData); // Trigger email sending process

            // Return success response
            return array("message" => "Form created successfully");
        } else {
            // Return error response if validation fails
            return array("success" => false, "errors" => $validationResult['errors']);
        }
    }

}

// Handle API request...
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = json_decode(file_get_contents('php://input'), true);

    if ($formData) {
        $dynamicFormCreator = new DynamicFormCreator($db);
        $result = $dynamicFormCreator->createForm($formData);

        // Return response
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(array("success" => false, "message" => "Invalid data"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Method Not Allowed"));
}
?>
