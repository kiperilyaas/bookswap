## 2025-05-15 - [BOLA/IDOR in Listing Deletion]
**Vulnerability:** Any logged-in user could delete any listing by simply providing the `id_listing` in a `GET` request.
**Learning:** The application lacked ownership verification on destructive actions and used `GET` instead of `POST`.
**Prevention:** Always verify that the resource being modified or deleted belongs to the currently authenticated user by checking the owner's ID against the session's user ID. Use `POST` for all state-changing operations.
