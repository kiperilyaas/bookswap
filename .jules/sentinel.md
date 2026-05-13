## 2025-05-15 - IDOR in Listing Deletion
**Vulnerability:** Insecure Direct Object Reference (IDOR) in `deleteListing`. Any logged-in user could delete any listing by providing the `id_listing` in the request, as there was no check to ensure the listing belonged to the requester.
**Learning:** Destructive operations must always verify ownership of the resource being modified. Additionally, using `GET` for state-changing operations is a security risk (CSRF) and an architectural anti-pattern.
**Prevention:** Always include a check for the user ID (from the session) in the `WHERE` clause of `UPDATE` or `DELETE` queries. Use `POST` for any request that modifies server-side state.
