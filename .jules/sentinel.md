## 2026-05-18 - IDOR in Listing Deletion
**Vulnerability:** Insecure Direct Object Reference (IDOR) and improper use of GET for destructive actions in `ListingsController::deleteListing`. Any user could delete any listing by ID via a simple GET request.
**Learning:** The application lacked centralized authorization checks for resource ownership. Standard MVC routing relied on GET parameters for actions, which tempted the use of GET for all operations.
**Prevention:** Always verify resource ownership in the controller or model using the authenticated user's ID from the session. Destructive actions MUST use POST requests and should include CSRF protection (to be implemented).
