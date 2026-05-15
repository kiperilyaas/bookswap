## 2025-05-15 - [IDOR in Listing Deletion]
**Vulnerability:** Insecure Direct Object Reference (IDOR) in `ListingsController::deleteListing`.
**Learning:** Destructive actions were exposed via GET requests and lacked server-side ownership verification, allowing any authenticated user to delete any listing by providing its ID.
**Prevention:** Always use POST for state-changing operations and verify that the resource belongs to the currently logged-in user before proceeding.
