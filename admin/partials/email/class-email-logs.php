<?php

class Aben_Email_Logs
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aben_email_logs';
    }

    /**
     * Log an email entry into the database.
     *
     * @param string $to The recipient of the email.
     * @param string $subject The subject of the email.
     * @param string $message The message content of the email.
     * @param string $status The status of the email (e.g., "sent", "failed").
     */
    public function log_email($to, $subject, $message, $status)
    {
        global $wpdb;

        // Sanitize input data
        $data = [
            'email_to' => sanitize_text_field($to),
            'subject' => sanitize_text_field($subject),
            'message' => wp_kses_post($message),
            'status' => sanitize_text_field($status),
            'sent_at' => current_time('mysql'), // Get the current time in MySQL format
        ];

        // Insert the log entry into the table
        $wpdb->insert(
            $this->table_name,
            $data,
            [
                '%s', // email_to
                '%s', // subject
                '%s', // message
                '%s', // status
                '%s', // sent_at
            ]
        );
    }

    /**
     * Retrieve email logs from the database with optional filters and pagination.
     *
     * @param int $limit The number of records to retrieve (default is 10).
     * @param int $offset The offset (default is 0).
     * @param array $filters Optional filters to apply to the query.
     *
     * @return array The retrieved email logs.
     */
    public function get_logs($limit = 10, $offset = 0, $filters = [])
    {
        global $wpdb;

        // Initialize the WHERE clause and parameters
        $where = '';
        $params = [];

        // Apply filters (if any)
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    // For security, sanitize the filter value
                    $where .= " AND $key LIKE %s";
                    $params[] = '%' . sanitize_text_field($value) . '%'; // Use LIKE for partial matches
                }
            }
        }

        // Build the SQL query with filtering, pagination, and sorting by sent_at
        $query = "
            SELECT * FROM $this->table_name
            WHERE 1=1 $where
            ORDER BY sent_at DESC
            LIMIT %d OFFSET %d
        ";

        // Add limit and offset to the query parameters
        $params[] = $limit;
        $params[] = $offset;

        // Execute the query and return the results
        return $wpdb->get_results($wpdb->prepare($query, ...$params));
    }

    /**
     * Get the total number of email logs for pagination purposes.
     *
     * @param array $filters Optional filters to apply to the query.
     *
     * @return int The total count of email logs.
     */
    public function get_total_logs_count($filters = [])
    {
        global $wpdb;

        // Initialize the WHERE clause and parameters
        $where = '';
        $params = [];

        // Apply filters (if any)
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    // For security, sanitize the filter value and add a placeholder for it
                    $where .= " AND $key LIKE %s";
                    $params[] = '%' . sanitize_text_field($value) . '%'; // Use LIKE for partial matches
                }
            }
        }

        // Build the SQL query to count the total number of records
        $query = "
        SELECT COUNT(*) FROM $this->table_name
        WHERE 1=1 $where
    ";

        // Execute the query and return the total count
        return $wpdb->get_var($wpdb->prepare($query, ...$params));
    }
}
