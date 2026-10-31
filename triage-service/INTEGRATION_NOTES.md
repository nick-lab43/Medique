# Triage Service Integration Notes

- The current project's main PHP API is `../api.php`.
- The PHP triage route calls `http://localhost:5002/predict_urgency`.
- The service accepts JSON containing a `symptoms` string.
- A successful response includes `urgency`, `confidence`, `needs_review`, `safety_override`, and a disclaimer.
- When the service returns HTTP 422 with `code: invalid_symptom_description`, the PHP route rejects the submission instead of saving an urgency classification.
- If the service is unreachable or returns an unexpected response, the PHP API falls back to its existing keyword-based triage rules.
- The existing database operations, queue logic, and wait-time service on port 5001 are preserved.
- The training examples are synthetic and are not evidence of clinical validation.
