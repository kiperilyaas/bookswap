## 2026-05-13 - [IDOR in Listing Deletion]
**Vulnerability:** Insecure Direct Object Reference (IDOR) on the `deleteListing` endpoint. Any user could delete any listing by providing its ID via a GET request.
**Learning:** Destructive actions were implemented using GET requests without checking if the resource belonged to the authenticated user.
**Prevention:** Always use POST for state-changing operations and verify resource ownership in the SQL query or controller logic using the session-based user ID.
